<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ToggleFavorite extends Component
{
    public $productId;
    public $isFavorited = false;

    public function mount($productId)
    {
        $this->productId = $productId;

        if (Auth::check()) {
            $this->isFavorited = Auth::user()->favorites->contains($productId);
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Auth::user()->favorites()->toggle($this->productId);

        $this->isFavorited = !$this->isFavorited;
    }

    public function render()
    {
        return view('livewire.toggle-favorite');
    }
}
