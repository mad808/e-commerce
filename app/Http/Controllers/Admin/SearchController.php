<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class SearchController extends Controller
{
    // 1. GLOBAL SEARCH PAGE (Big Search)
    public function index(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return redirect()->back();
        }

        // Search Products (Name or Barcode)
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->latest()->take(20)->get();

        // Search Orders (ID or Phone)
        $orders = Order::where('id', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('full_name', 'like', "%{$query}%")
            ->latest()->take(20)->get();

        // Search Clients (Name, Email, or Phone - Critical for catching liars)
        $users = User::where('role', 'client')
            ->where(function ($q) use ($query) {
                $q->where('phone', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest()->take(20)->get();

        // Search Categories
        $categories = Category::where('name', 'like', "%{$query}%")
            ->latest()->take(20)->get();

        return view('admin.search.index', compact('products', 'orders', 'users', 'categories', 'query'));
    }

    // 2. AJAX LIVE SEARCH (Small dropdown search)
    public function searchAjax(Request $request)
    {
        $query = $request->get('q');

        // Only search if user types 2+ characters
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // --- 1. SEARCH CLIENTS (By Phone or Name) ---
        $users = User::where('role', 'client')
            ->where(function ($q) use ($query) {
                $q->where('phone', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%");
            })->take(3)->get();

        foreach ($users as $u) {
            $results[] = [
                'title' => $u->name . ($u->status == 'blocked' ? ' (BLOCKED)' : ''),
                'subtitle' => 'Client: ' . ($u->phone ?? $u->email),
                'url' => route('admin.users.edit', $u->id),
                'icon' => $u->status == 'blocked' ? 'bi-person-x text-danger' : 'bi-person text-info'
            ];
        }

        // --- 2. SEARCH PRODUCTS ---
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->take(3)->get();

        foreach ($products as $p) {
            $results[] = [
                'title' => $p->name,
                'subtitle' => 'Product' . ($p->barcode ? ': ' . $p->barcode : ''),
                'url' => route('admin.products.edit', $p->id),
                'icon' => 'bi-box-seam text-primary'
            ];
        }

        // --- 3. SEARCH ORDERS (By ID or Phone) ---
        $orders = Order::where('id', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->take(3)->get();

        foreach ($orders as $o) {
            $results[] = [
                'title' => 'Order #' . $o->id,
                'subtitle' => $o->full_name . ' (' . $o->phone . ')',
                'url' => route('admin.orders.show', $o->id),
                'icon' => 'bi-cart-check text-warning'
            ];
        }

        // --- 4. SEARCH CATEGORIES ---
        $categories = Category::where('name', 'like', "%{$query}%")->take(3)->get();
        foreach ($categories as $c) {
            $results[] = [
                'title' => $c->name,
                'subtitle' => 'Category',
                'url' => route('admin.categories.edit', $c->id),
                'icon' => 'bi-diagram-3 text-success'
            ];
        }

        return response()->json($results);
    }
}
