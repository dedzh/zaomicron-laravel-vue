<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\SiteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        $lines = [];
        foreach ($validated['items'] as $item) {
            $lines[] = sprintf(
                '%s × %s — %s ₽ (арт. %s)',
                $item['name'],
                $item['quantity'],
                number_format((float) $item['price'], 2, '.', ' '),
                $item['id']
            );
        }

        $rows = [
            ['label' => 'Имя', 'value' => $validated['name']],
            ['label' => 'Телефон', 'value' => $validated['phone']],
            ['label' => 'Email', 'value' => $validated['email']],
            ['label' => 'Комментарий', 'value' => $validated['comment'] ?? '—'],
            ['label' => 'Товары', 'value' => implode("\n", $lines)],
            ['label' => 'Сумма заказа', 'value' => number_format((float) $validated['total'], 2, '.', ' ') . ' ₽'],
        ];

        try {
            SiteMail::sendHtml(
                'Заказ с сайта',
                $rows,
                $validated['email'],
                $validated['name']
            );
        } catch (Throwable $e) {
            Log::error('Mail send failed (order)', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось отправить заказ. Попробуйте позже.',
            ], 503);
        }

        return response()->json(['success' => true]);
    }
}
