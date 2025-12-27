<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use Livewire\Attributes\On;

class NavbarCart extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        if (Auth::check()) {
            $this->count = CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            $this->count = 0;
        }
    }

    public function render()
    {
        return view('livewire.navbar-cart');
    }
}