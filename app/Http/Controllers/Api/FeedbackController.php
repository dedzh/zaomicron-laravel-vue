<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

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


        return response()->json(['success' => true]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);


        return response()->json(['success' => true]);
    }
}

