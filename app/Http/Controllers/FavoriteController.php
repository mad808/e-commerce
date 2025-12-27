<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Get current favorites
        $favorites = $user->favorites()->with('category')->paginate(12);

        // 2. Get Related Products
        $relatedProducts = collect();

        if ($favorites->count() > 0) {
            // Get all category IDs from the wishlist
            $categoryIds = $favorites->pluck('category_id')->unique()->filter();

            // Get IDs of products already in favorites to exclude them
            $favoriteIds = $favorites->pluck('id');

            // Fetch products from the same categories, excluding existing favorites
            $relatedProducts = Product::whereIn('category_id', $categoryIds)
                ->whereNotIn('id', $favoriteIds) // Don't show what's already in wishlist
                ->with('category')
                ->inRandomOrder() // Mix them up
                ->take(8) // Limit to 8 items
                ->get();
        }

        return view('client.favorites', compact('favorites', 'relatedProducts'));
    }

    public function toggle($id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        // Toggle logic
        if ($user->favorites()->where('product_id', $id)->exists()) {
            $user->favorites()->detach($id);
            $message = 'Removed from favorites.';
        } else {
            $user->favorites()->attach($id);
            $message = 'Added to favorites!';
        }

        return back()->with('success', $message);
    }
}
