<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;

class OrderController extends Controller
{
    // Checkout Logic
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = $request->user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Cart is empty'], 400);
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        try {
            DB::transaction(function () use ($request, $user, $total, $cartItems) {
                // 1. Create Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'total_price' => $total,
                    'status' => 'pending',
                    'notes' => $request->notes,
                ]);

                // 2. Add Items
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                    // Decrease stock
                    $item->product->decrement('stock', $item->quantity);
                }

                // 3. Clear Cart
                CartItem::where('user_id', $user->id)->delete();
            });

            return response()->json(['status' => true, 'message' => 'Order placed successfully']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
            
        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->find($id);

        if(!$order) return response()->json(['status' => false], 404);
        
        // Fix images in order items
        $order->items->map(function($item){
            $item->product->image = asset('storage/' . $item->product->image);
            return $item;
        });

        return response()->json(['status' => true, 'data' => $order]);
    }
}