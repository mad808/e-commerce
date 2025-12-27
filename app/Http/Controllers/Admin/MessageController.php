<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // 1. List of Conversations (Grouped by User)
    public function index()
    {
        // Get all users who have sent a message
        // We look for IDs in the messages table
        $userIds = Message::distinct()->pluck('user_id');

        $users = User::whereIn('id', $userIds)
            ->with(['orders']) // Optional: show order count
            ->get()
            ->map(function ($user) {
                // Get latest message for preview
                $user->last_message = Message::where('user_id', $user->id)->latest()->first();
                // Count unread messages from this user
                $user->unread_count = Message::where('user_id', $user->id)
                    ->where('is_admin_reply', false)
                    ->where('is_read', false)
                    ->count();
                return $user;
            })
            ->sortByDesc('last_message.created_at'); // Sort by newest activity

        return view('admin.messages.index', compact('users'));
    }

    // 2. Show Conversation with specific User
    public function show($userId)
    {
        $user = User::findOrFail($userId);

        // Mark user's messages as read
        Message::where('user_id', $userId)
            ->where('is_admin_reply', false)
            ->update(['is_read' => true]);

        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messages.show', compact('userId'));
    }

    // 3. Reply to User
    public function reply(Request $request, $userId)
    {
        $request->validate(['message' => 'required|string']);

        Message::create([
            'user_id' => $userId,
            'message' => $request->message,
            'is_admin_reply' => true, // Sent by Admin
            'is_read' => false, // Client hasn't read it (optional logic)
        ]);

        return back();
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Message removed.');
    }
}
