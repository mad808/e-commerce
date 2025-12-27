<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Sliders with Full Image URLs
        $sliders = Slider::where('type', 'home_main')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function($slider) {
                $slider->image = asset('storage/' . $slider->image);
                return $slider;
            });

        // 2. Categories
        $categories = Category::withCount('products')
            ->take(8)
            ->get()
            ->map(function($cat) {
                $cat->image = $cat->image ? asset('storage/' . $cat->image) : null;
                return $cat;
            });

        // 3. New Arrivals
        $newArrivals = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get()
            ->map(function($p) {
                $p->image = asset('storage/' . $p->image);
                return $p;
            });

        return response()->json([
            'status' => true,
            'data' => [
                'sliders' => $sliders,
                'categories' => $categories,
                'new_arrivals' => $newArrivals,
            ]
        ]);
    }
    
    public function settings()
    {
        $settings = Setting::pluck('value', 'key');
        return response()->json(['status' => true, 'data' => $settings]);
    }
}