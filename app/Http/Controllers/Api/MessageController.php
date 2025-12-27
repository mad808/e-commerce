<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['status' => true, 'data' => $messages]);
    }

    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $msg = Message::create([
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'is_admin_reply' => false,
            'is_read' => false,
        ]);

        return response()->json(['status' => true, 'data' => $msg]);
    }
}