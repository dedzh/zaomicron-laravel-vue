<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\JamCatalogItem;
use App\Models\JamCatalogItemsParam;
use App\Models\JamCatalogParam;
use App\Models\JamCatalogParamsValue;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CatalogController extends Controller
{
    public function filters()
    {
        return Cache::remember('catalog:filters', now()->addHour(), function () {
            $brands = Brand::orderBy('name')->pluck('name')->values()->all();
            $types  = Unit::orderBy('name')->pluck('name')->values()->all();

            $params = JamCatalogParam::where('show_filter', 1)
                ->orderBy('sort')
                ->get()
                ->map(function ($param) {
                    return [
                        'id'     => $param->id,
                        'name'   => $param->name,
                        'code'   => $param->code,
                        'values' => JamCatalogParamsValue::where('param_id', $param->id)
                            ->where('trash', 0)
                            ->orderBy('value')
                            ->pluck('value')
                            ->filter()
                            ->values()
                            ->toArray(),
                    ];
                });

            return [
                'brands' => $brands,
                'types'  => $types,
                'params' => $params,
            ];
        });
    }

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

    public function browse(?string $id = null)
    {
        if ($id !== null && $id !== '' && !ctype_digit((string) $id)) {
            abort(404);
        }

        if ($id === null || $id === '') {
            $current = null;
            $nodes = Category::whereNull('parent_id')
                ->withCount('children')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            $breadcrumbs = [];
        } else {
            $current = Category::find((int) $id);
            if (!$current) {
                abort(404);
            }

            $nodes = $current->children()
                ->withCount('children')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $breadcrumbs = $this->breadcrumbsFor($current);
        }

        $children = $nodes->map(fn(Category $c) => [
            'id'           => $c->id,
            'name'         => $c->name,
            'has_children' => ($c->children_count ?? 0) > 0,
        ])->values();

        $directProductsCount = $current
            ? (int) $current->products()->count()
            : 0;

        return response()->json([
            'category'              => $current ? [
                'id'        => $current->id,
                'name'      => $current->name,
                'parent_id' => $current->parent_id,
            ] : null,
            'children'              => $children,
            'breadcrumbs'           => $breadcrumbs,
            'direct_products_count' => $directProductsCount,
        ]);
    }

    public function products(Request $request)
    {
        $perPage = min(48, max(1, (int) $request->get('per_page', 12)));
        $query = Product::with([
            'brand',
            'unit',
            'jamItem:id,article,price_1,stock_quantity,in_stock',
        ]);

        if ($categoryId = $request->get('category_id')) {
            $category = Category::find($categoryId);
            if ($category) {
                if ($request->boolean('category_direct_only')) {
                    $query->where('category_id', $category->id);
                } else {
                    $descendantIds = $this->getDescendantIds($category);
                    $query->whereIn('category_id', $descendantIds);
                }
            }
        }

        $brands = $this->normalizeRequestStringList($request, 'brands');
        if (!empty($brands)) {
            $allNumeric = collect($brands)->every(fn($v) => is_numeric($v));
            if ($allNumeric) {
                $query->whereIn('brand_id', $brands);
            } else {
                $query->whereHas('brand', fn($q) => $q->whereIn('name', $brands));
            }
        }

        $types = $this->normalizeRequestStringList($request, 'types');
        if (!empty($types)) {
            $query->whereHas('unit', fn($q) => $q->whereIn('name', $types));
        }

        $minRaw = $request->input('minPrice');
        if ($minRaw !== null && $minRaw !== '' && is_numeric($minRaw)) {
            $query->where('price', '>=', (float) $minRaw);
        }

        $maxRaw = $request->input('maxPrice');
        if ($maxRaw !== null && $maxRaw !== '' && is_numeric($maxRaw)) {
            $query->where('price', '<=', (float) $maxRaw);
        }

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

        // Фильтрация по дополнительным атрибутам
        if ($attrs = $request->get('attrs')) {
            foreach ($attrs as $paramId => $values) {
                if (empty($values) || !is_array($values)) {
                    continue;
                }

                $values = array_values(array_filter(array_map('strval', $values), fn($v) => $v !== ''));
                if (empty($values)) {
                    continue;
                }

                $valueIds = JamCatalogParamsValue::query()
                    ->where('param_id', $paramId)
                    ->where('trash', 0)
                    ->whereIn('value', $values)
                    ->pluck('id')
                    ->all();

                $query->whereHas('jamItem.params', function ($q) use ($paramId, $values, $valueIds) {
                    $q->where('jam_catalog_items_params.param_id', $paramId)
                        ->where('jam_catalog_items_params.trash', 0)
                        ->where(function ($sub) use ($values, $valueIds) {
                            $sub->whereIn('jam_catalog_items_params.value', $values);
                            if (!empty($valueIds)) {
                                $sub->orWhereIn('jam_catalog_items_params.value_int', $valueIds);
                            }
                        });
                });
            }
        }

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

    public function featured(Request $request)
    {
        $limit = min(30, max(1, (int) $request->get('limit', 12)));

        return Cache::remember("catalog:featured:{$limit}", now()->addMinutes(10), function () use ($limit) {
            $products = Product::with([
                'brand',
                'unit',
                'jamItem:id,article,price_1,stock_quantity,in_stock',
            ])
                ->inRandomOrder()
                ->limit($limit)
                ->get();

            return $products->map(fn($p) => $this->mapProductRow($p))->values();
        });
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $like = '%' . addcslashes($q, '%_') . '%';

        $products = Product::with([
            'brand',
            'unit',
            'jamItem:id,article,price_1,stock_quantity,in_stock',
        ])
            ->where('name', 'like', $like)
            ->orWhereHas('brand', fn($q) => $q->where('name', 'like', $like))
            ->orWhere('old_code', 'like', $like)
            ->orderBy('name')
            ->limit(40)
            ->get();

        return response()->json(
            $products->map(fn($p) => $this->mapProductRow($p))->values()
        );
    }

    public function favoritesProducts(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = [];
        }

        $products = Product::whereIn('old_code', $ids)
            ->with([
                'brand',
                'unit',
                'jamItem:id,article,price_1,stock_quantity,in_stock',
            ])
            ->get();

        return response()->json(
            $products->map(fn($p) => $this->mapProductRow($p))->values()
        );
    }

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

    public function category($id, Request $request)
    {
        $perPage = max(1, (int) config('catalog.per_page', 12));

        if ((int) $id === 1) {
            $rootCategory = Category::find(1);
            if ($rootCategory) {
                $categoryData = $this->mapCategoryNode($rootCategory, true);
            } else {
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
            $query = $category->products()->with([
                'brand',
                'unit',
                'jamItem:id,article,price_1,stock_quantity,in_stock',
            ]);

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

    public function product($id)
    {
        $product = Product::with([
            'brand',
            'unit',
            'category',
            'jamItem.params' => fn($q) => $q->where('jam_catalog_items_params.trash', 0),
        ])
            ->where('old_code', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        // Старый, надёжный способ получения картинок
        $images = [];
        $directory = public_path('images/foto/' . $product->old_code);
        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $images[] = '/images/foto/' . $product->old_code . '/' . $file->getFilename();
                }
            }
        }
        if (empty($images)) {
            $images[] = '/images/foto/nofoto.jpg';
        }

        $jamItem = $product->jamItem;

        return response()->json([
            'id'               => $product->old_code,
            'name'             => $product->name,
            'sku'              => (string) $product->old_code,
            'brand'            => $product->brand?->name,
            'price'            => $this->resolveProductPrice($product),
            'image'            => $images[0],
            'images'           => $images,
            'stock'            => $this->resolveProductStock($product),
            'shortDescription' => $jamItem?->descr_short ? trim(strip_tags((string) $jamItem->descr_short)) : '',
            'fullDescription'  => $jamItem?->descr_1 ?? '',
            'specs'            => $this->buildProductSpecs($product, $jamItem),
            'docs'             => [],
            'threeDUrl'        => null,
            'isFavorite'       => false,
        ]);
    }

    private function mapProductRow(Product $product): array
    {
        $imageUrl = '/images/foto/' . $product->old_code . '/1.jpg';

        return [
            'id'        => $product->old_code,
            'name'      => $product->name,
            'sku'       => (string) $product->old_code,
            'price'     => $this->resolveProductPrice($product),
            'brand'     => $product->brand?->name,
            'measure'   => $product->unit?->name,
            'stock'     => $this->resolveProductStock($product),
            'image'     => $imageUrl,
            'image_url' => $imageUrl,
        ];
    }

    private function resolveProductStock(Product $product): int
    {
        $jamItem = $product->relationLoaded('jamItem')
            ? $product->jamItem
            : $product->jamItem()->first();

        if (!$jamItem) {
            return 0;
        }

        return max(0, (int) $jamItem->stock_quantity);
    }

    private function resolveProductPrice(Product $product): float
    {
        $jamItem = $product->relationLoaded('jamItem')
            ? $product->jamItem
            : $product->jamItem()->first();

        if ($jamItem && $jamItem->price_1 !== null && (float) $jamItem->price_1 > 0) {
            return (float) $jamItem->price_1;
        }

        return (float) $product->price;
    }

    private function buildProductSpecs(Product $product, ?JamCatalogItem $jamItem): array
    {
        $specs = array_filter([
            'Бренд'    => $product->brand?->name,
            'Артикул'  => (string) $product->old_code,
            'Ед. изм.' => $product->unit?->name,
            'Масса'    => $product->weight ? ($product->weight . ' кг') : null,
        ]);

        if (!$jamItem) {
            return $specs;
        }

        $itemParams = $jamItem->relationLoaded('params')
            ? $jamItem->params
            : JamCatalogItemsParam::query()
            ->where('item_id', $jamItem->id)
            ->where('trash', 0)
            ->get();

        if ($itemParams->isEmpty()) {
            return $specs;
        }

        $paramDefs = JamCatalogParam::query()
            ->whereIn('id', $itemParams->pluck('param_id')->unique())
            ->where('show_item', 1)
            ->where('trash', 0)
            ->orderBy('sort')
            ->get()
            ->keyBy('id');

        $valueIds = $itemParams
            ->pluck('value_int')
            ->filter()
            ->unique()
            ->values();

        $selectValues = $valueIds->isEmpty()
            ? collect()
            : JamCatalogParamsValue::query()
            ->whereIn('id', $valueIds)
            ->where('trash', 0)
            ->get()
            ->keyBy('id');

        foreach ($paramDefs as $paramId => $paramDef) {
            $itemParam = $itemParams->firstWhere('param_id', $paramId);
            if (!$itemParam) {
                continue;
            }

            $value = $this->resolveParamValue($itemParam, $selectValues);
            if ($value === null || $value === '') {
                continue;
            }

            $label = $paramDef->name;
            if (!empty($paramDef->unit_name)) {
                $label .= ', ' . $paramDef->unit_name;
            }

            $specs[$label] = $value;
        }

        return $specs;
    }

    private function resolveParamValue(JamCatalogItemsParam $itemParam, $selectValues): ?string
    {
        if ($itemParam->value_int) {
            return $selectValues->get($itemParam->value_int)?->value;
        }

        if ($itemParam->value !== null && $itemParam->value !== '') {
            return (string) $itemParam->value;
        }

        if ($itemParam->value_dec !== null) {
            return rtrim(rtrim((string) $itemParam->value_dec, '0'), '.');
        }

        if (!empty($itemParam->value_text)) {
            return trim(strip_tags((string) $itemParam->value_text));
        }

        return null;
    }

    private function getDescendantIds(Category $root): array
    {
        $ids = [];
        $queue = [(int) $root->id];

        while (!empty($queue)) {
            $batch = array_splice($queue, 0, 200);
            $ids = array_merge($ids, $batch);

            $next = Category::query()
                ->whereIn('parent_id', $batch)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();

            $queue = array_merge($queue, $next);
        }

        return array_values(array_unique($ids));
    }

    private function breadcrumbsFor(Category $category): array
    {
        $items = [];
        $current = $category;

        while ($current) {
            array_unshift($items, [
                'id'   => $current->id,
                'name' => $current->name,
            ]);

            if (!$current->parent_id) {
                break;
            }

            $current = Category::query()->find($current->parent_id);
        }

        return $items;
    }

    public function related($id)
    {
        $product = Product::where('old_code', $id)->firstOrFail();
        $categoryId = $product->category_id;

        $related = Product::where('category_id', $categoryId)
            ->where('old_code', '!=', $id)
            ->with([
                'brand',
                'unit',
                'jamItem:id,article,price_1,stock_quantity,in_stock',
            ])
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(fn($p) => $this->mapProductRow($p))
            ->values();

        return response()->json($related);
    }

    private function normalizeRequestStringList(Request $request, string $key): array
    {
        $raw = $request->input($key);

        if ($raw === null) {
            return [];
        }

        if (is_array($raw)) {
            return array_values(array_filter(array_map('strval', $raw), fn($v) => $v !== ''));
        }

        if (is_string($raw)) {
            $raw = trim($raw);
            return $raw === '' ? [] : [$raw];
        }

        return [];
    }
}
