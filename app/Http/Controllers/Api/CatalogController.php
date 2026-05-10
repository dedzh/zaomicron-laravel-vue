<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    // Бренды и единицы измерения для фильтров
    public function filters()
    {
        return Cache::remember('catalog:filters', now()->addHour(), function () {
            return [
                'brands' => Brand::orderBy('name')->pluck('name'),
                'types'  => Unit::orderBy('name')->pluck('name'),
            ];
        });
    }

    // Дерево категорий
    public function categories()
    {
        return Cache::remember('catalog:categories:tree', now()->addHour(), function () {
            $roots = Category::whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            return $roots->map(fn($root) => $this->mapCategoryNode($root, true))->values();
        });
    }

    // Список товаров с фильтрацией, сортировкой и пагинацией
  public function products(Request $request)
{
    $perPage = min(48, max(1, (int) $request->get('per_page', 12)));
    $query = Product::with(['brand', 'unit', 'images']);

    // Фильтр по категории
    if ($categoryId = $request->get('category_id')) {
        $category = Category::find($categoryId);
        if ($category) {
            $descendantIds = $this->getDescendantIds($category);
            $query->whereIn('category_id', $descendantIds);
        }
    }

    // Фильтр по брендам
    if ($brands = $request->get('brands')) {
        $brands = is_array($brands) ? array_values(array_filter($brands)) : [];
        if (!empty($brands)) {
            $allNumeric = collect($brands)->every(fn($v) => is_numeric($v));
            if ($allNumeric) {
                $query->whereIn('brand_id', $brands);
            } else {
                $query->whereHas('brand', fn($q) => $q->whereIn('name', $brands));
            }
        }
    }

    // Фильтр по типам
    if ($types = $request->get('types')) {
        $query->whereHas('unit', fn($q) => $q->whereIn('name', $types));
    }

    // Фильтр по цене
    if ($minPrice = $request->get('minPrice')) {
        $query->where('price', '>=', (float) $minPrice);
    }
    if ($maxPrice = $request->get('maxPrice')) {
        $query->where('price', '<=', (float) $maxPrice);
    }

    // ===== НОВОЕ: поиск по названию, бренду или old_code =====
    if ($search = $request->get('q')) {
        $search = mb_substr(trim($search), 0, 120);
        if ($search !== '') {
            $like = '%' . addcslashes($search, '%_') . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', $like))
                  ->orWhere('old_code', 'like', $like);
            });
        }
    }
    

    // Сортировка
    switch ($request->get('sort')) {
        case 'price_asc':
            $query->orderBy('price');
            break;
        case 'price_desc':
            $query->orderByDesc('price');
            break;
        default:
            $query->orderBy('name');
    }

    $items = $query->paginate($perPage);
    $items->getCollection()->transform(fn($p) => $this->mapProductRow($p));

    return response()->json([
        'data' => $items->items(),
        'meta' => [
            'current_page'  => $items->currentPage(),
            'last_page'     => $items->lastPage(),
            'per_page'      => $items->perPage(),
            'total'         => $items->total(),
            'category_name' => $categoryId
                ? optional(Category::find($categoryId))->name
                : 'Каталог',
        ],
    ]);
}
    // Featured товары
    public function featured(Request $request)
    {
        $limit = min(30, max(1, (int) $request->get('limit', 12)));

        return Cache::remember("catalog:featured:{$limit}", now()->addMinutes(10), function () use ($limit) {
            $products = Product::with('images')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            return $products->map(fn($p) => $this->mapProductRow($p))->values();
        });
    }

    // Поиск
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $like = '%' . addcslashes($q, '%_') . '%';
        $products = Product::where('name', 'like', $like)
            ->orWhereHas('brand', fn($q) => $q->where('name', 'like', $like))
            ->orWhere('old_code', 'like', $like)
            ->orderBy('name')
            ->limit(40)
            ->get();

        return response()->json(
            $products->map(fn($p) => $this->mapProductRow($p))->values()
        );
    }

    // Детальная карточка товара (по old_code)
    public function product($id)
    {
        $product = Product::with(['images', 'brand', 'unit', 'category'])
            ->where('old_code', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        $images = $product->images
            ->pluck('image_path')
            ->map(fn($path) => $this->normalizeImagePath($path))
            ->filter()
            ->values();

        return response()->json([
            'id'               => $product->old_code,
            'name'             => $product->name,
            'sku'              => (string) $product->old_code,
            'price'            => (float) $product->price,
            'image'            => $images->first(),
            'images'           => $images->values()->all(),
            'stock'            => 999, // заглушка — при необходимости заменить на реальный остаток
            'shortDescription' => '',
            'fullDescription'  => '',
            'specs'            => array_filter([
                'Бренд'    => $product->brand?->name,
                'Артикул'  => (string) $product->old_code,
                'Ед. изм.' => $product->unit?->name,
                'Масса'    => $product->weight ? ($product->weight . ' кг') : null,
            ]),
            'docs'       => [],
            'threeDUrl'  => null,
            'isFavorite' => false,
        ]);
    }

    // Избранное – товары по массиву идентификаторов
    public function favoritesProducts(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = [];
        }

        $products = Product::whereIn('old_code', $ids)
            ->with(['images', 'brand', 'unit'])
            ->get();

        return response()->json(
            $products->map(fn($p) => $this->mapProductRow($p))->values()
        );
    }

    // Промо-блоки для главной (популярные категории)
    public function mainPromo()
    {
        $categories = Category::has('products')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(4)
            ->get();

        return response()->json(
            $categories->map(fn($c) => [
                'id'          => $c->id,
                'title'       => $c->name,
                'description' => 'Категория: ' . $c->name,
                'link'        => '/catalog/' . $c->id,
                'image'       => '/img/category-placeholder.png',
            ])->values()
        );
    }

    // Категория с подкатегориями или товарами (для старого формата каталога)
    public function category($id, Request $request)
    {
        $perPage = max(1, (int) config('catalog.per_page', 12));

        // Корневая категория – обычно id=1
        if ((int) $id === 1) {
            $rootCategory = Category::find(1);
            if ($rootCategory) {
                $categoryData = $this->mapCategoryNode($rootCategory, true);
            } else {
                // Если категории с id=1 нет – создаём виртуальный корень из всех root-категорий
                $categoryData = [
                    'id'       => 1,
                    'name'     => 'Каталог',
                    'title'    => 'Каталог',
                    'children' => Category::whereNull('parent_id')
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                                    ->get()
                                    ->map(fn($c) => $this->mapCategoryNode($c, false))
                                    ->values(),
                ];
            }

            // Товары у корня обычно не нужны, вернём пустой пагинатор
            $items = Product::whereNull('id')->paginate($perPage);
            return response()->json([
                'category' => $categoryData,
                'items'    => $items,
            ]);
        }

        $category = Category::withCount('products')->find($id);
        if (!$category) {
            abort(404);
        }

        $categoryData = $this->mapCategoryNode($category, false);

        if ($category->products_count > 0) {
            $query = $category->products()->with(['brand', 'unit', 'images']);

            if ($request->sort === 'price_asc') {
                $query->orderBy('price');
            } elseif ($request->sort === 'price_desc') {
                $query->orderByDesc('price');
            } else {
                $query->orderBy('name');
            }

            $items = $query->paginate($perPage);
            $items->getCollection()->transform(fn($p) => $this->mapProductRow($p));

            return response()->json([
                'category' => $categoryData,
                'items'    => $items,
                'is_leaf'  => false,
            ]);
        }

        // Если товаров нет — отдаём дочерние категории
        $children = $category->children()
            ->get()
            ->map(fn($child) => $this->mapCategoryNode($child, false))
            ->values();

        return response()->json([
            'category' => $categoryData,
            'children' => $children,
            'is_leaf'  => true,
        ]);
    }

    // -----------------------------------------------------------------
    // Приватные вспомогательные методы
    // -----------------------------------------------------------------

    private function mapCategoryNode(Category $category, bool $includeChildren = false): array
    {
        $data = [
            'id'       => $category->id,
            'name'     => $category->name,
            'title'    => $category->name,
            'children' => [],
        ];

        if ($includeChildren) {
            $data['children'] = $category->children()
                ->get()
                ->map(fn($child) => $this->mapCategoryNode($child, false))
                ->values()
                ->all();
        }

        return $data;
    }

    private function mapProductRow(Product $product): array
    {
        $imageUrl = $product->image_url ? $this->normalizeImagePath($product->image_url) : null;

        return [
            'id'        => $product->old_code,
            'name'      => $product->name,
            'sku'       => (string) $product->old_code,
            'price'     => (float) $product->price,
            'brand'     => $product->brand?->name,
            'measure'   => $product->unit?->name,
            'stock'     => 999,
            'image'     => $imageUrl,
            'image_url' => $imageUrl,
        ];
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $normalized = '/' . ltrim(trim($path), '/');
        $normalized = preg_replace('#^/_images/_images/#', '/_images/', $normalized);

        return $normalized;
    }

    private function getDescendantIds(Category $category): array
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }
}