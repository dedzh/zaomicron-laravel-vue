<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\SiteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:5000',
        ]);

        try {
            SiteMail::sendHtml(
                'Сообщение с сайта: обратная связь',
                [
                    ['label' => 'Имя', 'value' => $validated['name']],
                    ['label' => 'Телефон', 'value' => $validated['phone']],
                    ['label' => 'Email', 'value' => $validated['email']],
                    ['label' => 'Сообщение', 'value' => $validated['message'] ?? '—'],
                ],
                $validated['email'],
                $validated['name']
            );
        } catch (Throwable $e) {
            Log::error('Mail send failed (feedback)', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось отправить сообщение. Попробуйте позже или напишите на почту напрямую.',
            ], 503);
        }

        return response()->json(['success' => true]);
    }

    public function partner(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'contactPerson' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'comment' => 'nullable|string|max:5000',
        ]);

        try {
            SiteMail::sendHtml(
                'Сообщение с сайта: заявка партнёра',
                [
                    ['label' => 'Компания', 'value' => $validated['company']],
                    ['label' => 'Контактное лицо', 'value' => $validated['contactPerson']],
                    ['label' => 'Телефон', 'value' => $validated['phone']],
                    ['label' => 'Email', 'value' => $validated['email']],
                    ['label' => 'Комментарий', 'value' => $validated['comment'] ?? '—'],
                ],
                $validated['email'],
                $validated['contactPerson']
            );
        } catch (Throwable $e) {
            Log::error('Mail send failed (partner)', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось отправить заявку. Попробуйте позже.',
            ], 503);
        }

        return response()->json(['success' => true]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        try {
            SiteMail::sendHtml(
                'Сообщение с сайта: подписка на рассылку',
                [
                    ['label' => 'Email подписчика', 'value' => $validated['email']],
                ],
                $validated['email']
            );
        } catch (Throwable $e) {
            Log::error('Mail send failed (subscribe)', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось оформить подписку. Попробуйте позже.',
            ], 503);
        }

        return response()->json(['success' => true]);
    }
}
