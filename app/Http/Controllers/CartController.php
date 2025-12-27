<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Show Cart Page
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.cart', compact('cartItems', 'total'));
    }

    // 2. Add Item to Cart
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $selectedAttributes = $request->input('attributes', []);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->where('attributes', $selectedAttributes)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => $request->quantity ?? 1,
                'attributes' => $selectedAttributes
            ]);
        }

        return redirect()->back()->with('success', 'Haryt sebede goşuldy!');
    }

    // 3. Remove Item
    public function destroy($id)
    {
        CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Haryt sebetden aýryldy.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Sebet täzelendi.');
    }
}
