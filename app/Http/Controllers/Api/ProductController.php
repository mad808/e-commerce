<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Search and Filter
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->where('stock', '>', 0);

        // Filter by Category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search text
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Sort
        if ($request->sort == 'price_asc') $query->orderBy('price', 'asc');
        if ($request->sort == 'price_desc') $query->orderBy('price', 'desc');
        if ($request->sort == 'newest') $query->latest();

        $products = $query->paginate(10);
        
        // Transform images in pagination
        $products->getCollection()->transform(function ($p) {
            $p->image = asset('storage/' . $p->image);
            return $p;
        });

        return response()->json($products);
    }

    public function categories()
    {
        $categories = Category::all()->map(function($c) {
            $c->image = $c->image ? asset('storage/'.$c->image) : null;
            return $c;
        });
        return response()->json(['status' => true, 'data' => $categories]);
    }

    // Single Product Detail
    public function show($id)
    {
        $product = Product::with(['category', 'images'])->where('is_active', true)->find($id);

        if(!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }

        // Fix Image URLs
        $product->image = asset('storage/' . $product->image);
        $product->images->transform(function($img) {
            $img->image = asset('storage/' . $img->image);
            return $img;
        });

        return response()->json(['status' => true, 'data' => $product]);
    }
}