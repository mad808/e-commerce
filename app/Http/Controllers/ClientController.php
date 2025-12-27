<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ClientController extends Controller
{
    // 1. List all orders for the logged-in user
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items') // Count items efficiently
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    // 2. Show details of a specific order
    public function orderDetail($id)
    {
        // Security: Ensure the order belongs to the logged-in user!
        $order = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->findOrFail($id);

        return view('client.orders.show', compact('order'));
    }

    // 3. Show Chat/Contact Page
    public function chat()
    {
        $messages = \App\Models\Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        return view('client.chat', compact('messages'));
    }

    // 4. Send Message to Admin
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        \App\Models\Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => false, // Sent by client
            'is_read' => false, // Admin hasn't read it yet
        ]);

        return back();
    }

    public function deleteMessage($id)
    {
        $message = \App\Models\Message::where('user_id', Auth::id())->findOrFail($id);

        $message->delete();

        return back()->with('success', 'Message deleted.');
    }

    // Show Profile Page
    public function profile()
    {
        return view('client.profile');
    }

    // Update Profile Logic
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update Basic Info
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Update Password (if provided)
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
