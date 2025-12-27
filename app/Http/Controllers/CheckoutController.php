<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Location;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $locations = Location::where('is_active', true)->get();
        $user = Auth::user();

        return view('shop.checkout', compact('cartItems', 'total', 'user', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location_id' => 'required|exists:locations,id',
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home');
        }

        $location = Location::findOrFail($request->location_id);

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $finalTotal = $subtotal + $location->delivery_price;

        DB::transaction(function () use ($request, $user, $finalTotal, $location, $cartItems) {
            $order = Order::create([
                'user_id' => $user->id,
                'location_id' => $location->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'delivery_price' => $location->delivery_price,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'attributes' => $item->attributes,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', $user->id)->delete();
        });

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('shop.success');
    }
}
