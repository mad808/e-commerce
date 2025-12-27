<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function($item) {
                $item->product->image = asset('storage/' . $item->product->image);
                return $item;
            });

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return response()->json([
            'status' => true,
            'data' => $cartItems,
            'total_price' => $total
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $userId = $request->user()->id;
        $qty = $request->quantity ?? 1;

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $qty);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $qty
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Added to cart']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Ensure user owns this item
        $item = CartItem::where('user_id', $request->user()->id)
                ->where('id', $request->cart_id)
                ->firstOrFail();

        $item->update(['quantity' => $request->quantity]);

        return response()->json(['status' => true, 'message' => 'Cart updated']);
    }

    public function destroy(Request $request, $id)
    {
        CartItem::where('user_id', $request->user()->id)->where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Item removed']);
    }
    
    public function clear(Request $request) 
    {
        CartItem::where('user_id', $request->user()->id)->delete();
        return response()->json(['status' => true, 'message' => 'Cart cleared']);
    }
}