<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. General Counts & Revenue
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalClients = User::where('role', 'client')->count();

        // 2. Order Status Breakdown (Optimized to 1 query)
        $orderStatsRaw = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $orderStats = [
            'pending'    => $orderStatsRaw['pending'] ?? 0,
            'processing' => $orderStatsRaw['processing'] ?? 0,
            'shipped'    => $orderStatsRaw['shipped'] ?? 0,
            'delivered'  => $orderStatsRaw['delivered'] ?? 0,
            'cancelled'  => $orderStatsRaw['cancelled'] ?? 0,
        ];

        // 3. SALES CHART (PostgreSQL Compatible)
        $salesData = Order::select(
            DB::raw("CAST(created_at AS DATE) as date_label"),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', '!=', 'cancelled')
            ->groupBy(DB::raw("CAST(created_at AS DATE)"))
            ->orderBy(DB::raw("CAST(created_at AS DATE)"), 'desc')
            ->take(7)
            ->get()
            ->sortBy('date_label');

        $chartDates = $salesData->pluck('date_label')->map(fn($date) => Carbon::parse($date)->format('d M'))->values()->toArray();
        $chartTotals = $salesData->pluck('total')->values()->toArray();

        // 4. CATEGORY CHART
        $topCategories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $catNames = $topCategories->pluck('name')->toArray();
        $catCounts = $topCategories->pluck('total_sold')->toArray();

        // 5. Top 10 Best Users
        $topUsers = Order::select('user_id', DB::raw('SUM(total_price) as total_spent'), DB::raw('COUNT(id) as order_count'))
            ->where('status', 'delivered') // Only count real money spent
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->take(10)
            ->get();

        // 6. Top Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product.category') // Eager load category to prevent N+1
            ->take(20)
            ->get();

        // 7. Top Favorites
        $topFavorites = DB::table('favorites')
            ->join('products', 'favorites.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'products.image', 'products.price', 'categories.name as category_name', DB::raw('COUNT(favorites.id) as likes_count'))
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'categories.name')
            ->orderByDesc('likes_count')
            ->take(20)
            ->get();

        // 8. Monthly Target
        $monthlyGoal = 50000;
        $currentMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $targetPercentage = ($monthlyGoal > 0) ? ($currentMonthRevenue / $monthlyGoal) * 100 : 0;

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalClients',
            'orderStats',
            'chartDates',
            'chartTotals',
            'catNames',
            'catCounts',
            'topUsers',
            'topProducts',
            'topFavorites',
            'currentMonthRevenue',
            'targetPercentage'
        ));
    }
}
