<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'message' => 'nullable|string',
        ]);
        // Сохраняем в БД или отправляем email
        Feedback::create($validated + ['type' => 'feedback']);
        return response()->json(['success' => true]);
    }

    public function partner(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string',
            'contactPerson' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'comment' => 'nullable|string',
        ]);
        Feedback::create($validated + ['type' => 'partner']);
        return response()->json(['success' => true]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        Feedback::create($validated + ['type' => 'subscribe']);
        return response()->json(['success' => true]);
    }
}