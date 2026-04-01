<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function filters()
{
    $brands = CatalogItem::active()
        ->whereNotNull('BRAND')
        ->distinct()
        ->pluck('BRAND')
        ->filter()
        ->values()
        ->toArray();

    // Типы пока статичны, так как в таблице нет поля type
    $types = ['Цифровые', 'Аналоговые', 'Промышленные'];

    return response()->json(['brands' => $brands, 'types' => $types]);
}
    public function categories()
    {
        // В CHEESECAKE_CATS_TREE корневые категории часто являются "детьми" ID=1
        // (при этом самой записи с ID=1 может не быть).
        $categories = CatalogCategory::active()
            ->whereNull('PARENT_ID')
            ->with('children')
            ->orderBy('NAME')
            ->get();

        if ($categories->isEmpty()) {
            $categories = CatalogCategory::active()
                ->where('PARENT_ID', 1)
                ->with('children')
                ->orderBy('NAME')
                ->get();
        }

        // Ограничиваем глубину дерева, чтобы меню и страница каталога не грузились очень долго.
        return response()->json($categories->map(fn ($cat) => $this->mapCategoryNode($cat, 1)));
    }

    public function category($id, Request $request)
    {
        // Пытаемся найти категорию по ID. В cheesecake-данных может не быть записи с ID=1,
        // при этом фронт использует 1 как "корень" каталога.
        $category = CatalogCategory::active()->with('children')->find($id);

        if (! $category) {
            if ((int) $id === 1) {
                // Синтетическая корневая категория "Каталог"
                $category = new CatalogCategory();
                // Синтетическая root-категория "Каталог" (не сохраняем в БД)
                $category->ID = 1;
                $category->PARENT_ID = null;
                $category->NAME = 'Каталог';
                // На страницу каталога дочерние категории не нужны (их грузит Navigation.vue).
                $category->setRelation('children', collect());
            } else {
                abort(404);
            }
        }

        if ((int) $id === 1) {
            // Не грузим дерево категорий при открытии /catalog, чтобы страница не зависала.
            $category->setRelation('children', collect());
        }

        // Для root-категории "Каталог" показываем товары (пагинация), а дерево категорий ограничиваем глубиной.
        if ((int) $id === 1 && $category->ID === 1) {
            $items = CatalogItem::active()
                ->when($request->sort, function ($query, $sort) {
                    if ($sort === 'price_asc') {
                        $query->orderBy('PRICE', 'asc');
                    }
                    if ($sort === 'price_desc') {
                        $query->orderBy('PRICE', 'desc');
                    }
                })
                ->paginate(12);
        } else {
            $items = CatalogItem::active()
                ->where('GROUP_ID', $id)
                ->when($request->sort, function ($query, $sort) {
                    if ($sort === 'price_asc') {
                        $query->orderBy('PRICE', 'asc');
                    }
                    if ($sort === 'price_desc') {
                        $query->orderBy('PRICE', 'desc');
                    }
                })
                ->paginate(12);
        }

        // Приводим поля к формату, который ожидает фронт (ProductCard).
        $items->getCollection()->transform(function (CatalogItem $item) {
            // Vue-компоненты ожидают lower-case поля: id/name/sku/price/image.
            $item->id = $item->ID;
            $item->name = $item->NAME;
            $item->sku = (string) $item->ID;
            $item->price = $item->PRICE;
            $item->image = null;
            $item->isFavorite = false;
            return $item;
        });

        return response()->json([
            'category' => $this->mapCategoryNode($category, 1),
            'items' => $items,
            // alias для совместимости со старым фронтом/кодом
            'products' => $items,
        ]);
    }

    public function product($id)
    {
        $product = CatalogItem::active()->with('category')->findOrFail($id);

        $product->image_url = null;
        $product->image = null;

        // Маппинг под поля, которые использует Product.vue.
        $product->id = $product->ID;
        $product->name = $product->NAME;
        $product->sku = (string) $product->ID;
        $product->price = $product->PRICE;
        $product->stock = (int) ($product->REST_FREE ?? $product->REST_FACT ?? 0);
        $product->shortDescription = '';
        $product->fullDescription = '';
        $product->specs = [
            'Бренд' => $product->BRAND,
            'Артикул' => $product->ID,
        ];
        $product->docs = [];
        $product->icon = null;

        return response()->json($product);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = CatalogItem::active()
            ->where('NAME', 'like', "%{$query}%")
            ->orWhere('BRAND', 'like', "%{$query}%")
            ->limit(20)
            ->get();

        foreach ($products as $product) {
            $product->image_url = null;
            $product->image = null;
            $product->id = $product->ID;
            $product->name = $product->NAME;
            $product->sku = (string) $product->ID;
            $product->price = $product->PRICE;
        }

        return response()->json($products);
    }

    private function getImageUrl($hash)
    {
        return null; // в cheesecake-товарах нет main_img/хеша
    }

    private function mapCategoryNode(CatalogCategory $cat, int $depth = 1): array
    {
        // Vue ожидает: id/name/title и children.
        if ($depth <= 0) {
            return [
                'id' => $cat->ID,
                'name' => $cat->NAME,
                'title' => $cat->NAME,
                'children' => [],
            ];
        }

        $children = $cat->children ?? collect();

        return [
            'id' => $cat->ID,
            'name' => $cat->NAME,
            'title' => $cat->NAME,
            'children' => $children
                ->map(fn ($ch) => $this->mapCategoryNode($ch, $depth - 1))
                ->values()
                ->all(),
        ];
    }
}

