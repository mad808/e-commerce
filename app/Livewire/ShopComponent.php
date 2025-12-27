<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url; 
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopComponent extends Component
{
    use WithPagination;

    // --- 1. DEFINE VARIABLES ---
    // We use #[Url] so that when you change filters, the URL updates automatically.
    
    #[Url] 
    public $q = '';

    #[Url(as: 'category')] // This maps the URL param '?category=' to the variable '$category_id'
    public $category_id = ''; 

    #[Url] 
    public $min_price = '';

    #[Url] 
    public $max_price = '';

    #[Url] 
    public $sort = 'newest';

    // --- 2. MOUNT METHOD (THE FIX) ---
    // This forces Livewire to read the URL parameters immediately on load.
    public function mount(Request $request)
    {
        $this->q = $request->query('q', $this->q);
        $this->category_id = $request->query('category', $this->category_id);
        $this->min_price = $request->query('min_price', $this->min_price);
        $this->max_price = $request->query('max_price', $this->max_price);
        $this->sort = $request->query('sort', 'newest');
    }

    // Reset pagination when any filter changes
    public function updated($propertyName)
    {
        $this->resetPage();
    }

    // Clear filters
    public function clearFilters()
    {
        $this->reset(['q', 'category_id', 'min_price', 'max_price', 'sort']);
        $this->resetPage();
        return redirect()->route('shop');
    }

    public function render()
    {
        $query = Product::query();

        // Ensure we only show active products
        $query->where('is_active', true);

        // 1. Filter by Search (q)
        if (!empty($this->q)) {
            $query->where('name', 'like', '%' . $this->q . '%');
        }

        // 2. Filter by Category
        if (!empty($this->category_id)) {
            $query->where('category_id', $this->category_id);
        }

        // 3. Filter by Price
        if (!empty($this->min_price)) {
            $query->where('price', '>=', $this->min_price);
        }
        if (!empty($this->max_price)) {
            $query->where('price', '<=', $this->max_price);
        }

        // 4. Sorting
        switch ($this->sort) {
            case 'price_asc': 
                $query->orderBy('price', 'asc'); 
                break;
            case 'price_desc': 
                $query->orderBy('price', 'desc'); 
                break;
            default: 
                $query->latest(); 
                break;
        }

        $products = $query->paginate(12);

        // Sidebar Data
        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->get();

        // Trending Data
        $trendingProducts = Product::where('is_active', true)
                                   ->inRandomOrder()
                                   ->take(3)
                                   ->get();

        return view('livewire.shop-component', [
            'products' => $products,
            'categories' => $categories,
            'trendingProducts' => $trendingProducts
        ])->layout('layouts.app');
    }
}