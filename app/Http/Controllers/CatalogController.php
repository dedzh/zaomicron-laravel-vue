<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function categories()
    {
        $categories = CatalogCategory::active()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort')
            ->get();

        return response()->json($categories);
    }

    public function category($id, Request $request)
    {
        $category = CatalogCategory::active()->with('children')->findOrFail($id);
        $items = CatalogItem::active()
            ->where('pat_id', $id)
            ->when($request->sort, function ($query, $sort) {
                if ($sort === 'price_asc') $query->orderBy('price_1', 'asc');
                if ($sort === 'price_desc') $query->orderBy('price_1', 'desc');
            })
            ->paginate(12);

        // Приводим поля к формату, который ожидает фронт (ProductCard).
        $items->getCollection()->transform(function (CatalogItem $item) {
            $item->sku = $item->article;
            $item->price = $item->price_1;
            $item->image = $this->getImageUrl($item->main_img);
            $item->isFavorite = false;
            return $item;
        });

        return response()->json([
            'category' => $category,
            'items' => $items,
            // alias для совместимости со старым фронтом/кодом
            'products' => $items
        ]);
    }

    public function product($id)
    {
        $product = CatalogItem::active()->with('category')->findOrFail($id);
        // Формируем URL изображения
        $product->image_url = $this->getImageUrl($product->main_img);
        $product->image = $product->image_url;

        // Маппинг под поля, которые использует Product.vue.
        $product->sku = $product->article;
        $product->price = $product->price_1;
        $product->stock = $product->stock_quantity ?? $product->in_stock ?? 0;
        $product->shortDescription = $product->descr_1 ?? '';
        $product->fullDescription = $product->descr_2 ?? $product->descr_3 ?? '';
        $product->specs = [
            'Модель' => $product->model,
            'Артикул' => $product->article,
        ];
        $product->docs = [];
        $product->icon = null;

        return response()->json($product);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = CatalogItem::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('model', 'like', "%{$query}%")
            ->orWhere('article', 'like', "%{$query}%")
            ->limit(20)
            ->get();

        foreach ($products as $product) {
            $product->image_url = $this->getImageUrl($product->main_img);
            $product->image = $product->image_url;
            $product->sku = $product->article;
            $product->price = $product->price_1;
        }

        return response()->json($products);
    }

    private function getImageUrl($hash)
    {
        if (!$hash) return null;
        // Проверяем возможные пути (настройте под свой проект)
        $paths = [
            public_path("uploads/{$hash}.jpg"),
            public_path("uploads/{$hash}.png"),
            public_path("assets/_images/{$hash}.jpg"),
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return asset(str_replace(public_path(), '', $path));
            }
        }
        return null;
    }
}