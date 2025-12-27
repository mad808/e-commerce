<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use App\Models\News;
use Illuminate\Support\Facades\Log;

class FrontController extends Controller
{
    // 1. HOME PAGE
    public function index()
    {
        $sliders = Slider::where('type', 'home_main')->where('is_active', true)->orderBy('sort_order')->get();
        $banners = Slider::where('type', 'product_detail_banner')->where('is_active', true)->take(2)->get();
        $popupAd = Slider::where('type', 'popup_ad')->where('is_active', true)->first();

        $newArrivals = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $discountedProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where('discount_percent', '>=', 5)
            ->inRandomOrder()
            ->take(8)
            ->get();

        $categories = Category::whereHas('products', function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        })->take(16)->get();

        $latestNews = News::latestNews(6)->get();

        return view('home', compact('sliders', 'banners', 'newArrivals', 'discountedProducts', 'categories', 'popupAd', 'latestNews'));
    }

    // 2. PRODUCT DETAIL PAGE
    public function product($slug)
    {
        // Eager load 'attributes' to display on frontend
        $product = Product::with(['category', 'attributes', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // View Increment Logic
        $sessionKey = 'viewed_product_' . $product->id;
        if (!session()->has($sessionKey)) {
            $product->increment('views');
            session()->put($sessionKey, true);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)->get();

        $banners = Slider::where('type', 'product_detail_banner')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(2)->get();

        return view('shop.product', compact('product', 'relatedProducts', 'banners'));
    }

    // 3. SHOP / CATALOG PAGE
    public function shop(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $category = Category::find($request->category);

            if ($category) {
                $allCategoryIds = $category->getAllChildrenIds();
                $query->whereIn('category_id', $allCategoryIds);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        $trendingProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.index', compact('products', 'categories', 'trendingProducts'));
    }

    // AJAX LIVE SEARCH
    public function searchAjax(Request $request)
    {
        try {
            $query = $request->get('q');

            if (!$query || strlen($query) < 2) {
                return response()->json([]);
            }

            $products = Product::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%");
            })
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->with('category:id,name')
                ->take(5)
                ->get(['id', 'name', 'slug', 'image', 'price', 'discount_percent', 'category_id', 'barcode']);

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
