<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'comment' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',
        ]);

        // Здесь сохраняете заказ в БД (например, в таблицу orders)
        // Либо отправляете уведомление на почту

        // Пока просто возвращаем успех
        return response()->json(['success' => true]);
    }
}