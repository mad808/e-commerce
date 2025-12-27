<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductDetailAddCart extends Component
{
    public $productId;
    public $stock;
    public $quantity = 1;

    public function mount($productId, $stock)
    {
        $this->productId = $productId;
        $this->stock = $stock;
    }

    public function increment()
    {
        if ($this->quantity < $this->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $this->productId)
                            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $this->quantity);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
                'quantity' => $this->quantity
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Added to cart!');
    }

    public function render()
    {
        return view('livewire.product-detail-add-cart');
    }
}