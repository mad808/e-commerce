<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AddToCartButton extends Component
{
    public $productId;
    public $customClass = '';
    public $btnColor = 'success';
    public $withQuantity = false;
    public $quantity = 1;
    public $selectedAttributes = [];

    public function mount($productId, $customClass = '', $btnColor = 'success', $withQuantity = false)
    {
        $this->productId = $productId;
        $this->customClass = $customClass;
        $this->btnColor = $btnColor;
        $this->withQuantity = $withQuantity;

        if ($this->withQuantity) {
            $product = Product::with('attributes')->find($productId);
            if ($product) {
                foreach ($product->attributes as $attr) {
                    $values = explode(',', $attr->pivot->value);
                    $this->selectedAttributes[$attr->name] = trim($values[0]);
                }
            }
        }
    }

    public function increment()
    {
        $this->quantity++;
    }
    public function decrement()
    {
        if ($this->quantity > 1) $this->quantity--;
    }

    public function addToCart()
    {
        if (!Auth::check()) return redirect()->route('login');

        if ($this->withQuantity) {
            $this->directAddToCart();
        } else {
            $this->dispatch('openModal', productId: $this->productId);
        }
    }

    public function directAddToCart()
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $this->productId)
            ->where('attributes', json_encode($this->selectedAttributes))
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $this->quantity);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'attributes' => $this->selectedAttributes,
                'price' => Product::find($this->productId)->price ?? 0
            ]);
        }

        $this->quantity = 1;
        session()->flash('success', 'Sebede goşuldy!');
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
