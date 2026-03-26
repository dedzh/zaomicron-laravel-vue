<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();

        $data = [
            'id'               => $product->code,
            'name'             => $product->name,
            'sku'              => $product->code,
            'price'            => $product->price,
            'stock'            => $product->quantity ?? 0,
            'image'            => $product->getImageUrl(),
            'shortDescription' => $product->product ?? '',
            'fullDescription'  => $product->product ?? '',
            'specs'            => [],
            'docs'             => [],
        ];

        return response()->json($data);
    }
}