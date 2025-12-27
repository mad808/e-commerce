<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\CartItem;
use Livewire\Attributes\On;

class ProductModal extends Component
{
    public $isOpen = false;
    public $product;
    public $productId;
    public $quantity = 1;
    public $selectedAttributes = [];

    #[On('openModal')]
    public function loadProduct($productId)
    {
        $this->productId = $productId;
        $this->product = Product::with(['attributes', 'category'])->find($productId);
        $this->quantity = 1;
        $this->selectedAttributes = [];

        if ($this->product) {
            foreach ($this->product->attributes as $attr) {
                $values = explode(',', $attr->pivot->value);
                $this->selectedAttributes[$attr->name] = trim($values[0]);
            }
            $this->isOpen = true;
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
        if (!auth()->check()) return redirect()->route('login');

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $this->productId)
            ->where('attributes', json_encode($this->selectedAttributes))
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $this->quantity);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $this->productId,
                'attributes' => $this->selectedAttributes,
                'quantity' => $this->quantity,
                'price' => $this->product->price,
            ]);
        }

        $this->isOpen = false;
        $this->dispatch('cartUpdated');
        session()->flash('success', 'Sebede goşuldy!');
    }

    public function close()
    {
        $this->isOpen = false;
    }
    public function render()
    {
        return view('livewire.product-modal');
    }
}
