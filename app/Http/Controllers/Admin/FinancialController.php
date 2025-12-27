<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FinancialController extends Controller
{
    public function index()
    {
        try {
            // 1. Calculate Summary (Stock Analysis)
            // Total cost of all items currently in the warehouse
            $totalInvestment = Product::sum(DB::raw('cost_price * stock'));

            // Potential revenue if every single item in stock is sold
            $totalExpectedRevenue = Product::sum(DB::raw('price * stock'));

            // Potential profit from current stock
            $totalExpectedProfit = $totalExpectedRevenue - $totalInvestment;

            // 2. Pagination for the table
            $products = Product::orderBy('stock', 'desc')->paginate(15);

            return view('admin.financial.index', compact(
                'products',
                'totalInvestment',
                'totalExpectedRevenue',
                'totalExpectedProfit'
            ));
        } catch (\Exception $e) {
            Log::error('Financial Index Error: ' . $e->getMessage());
            return back()->with('error', 'Hasabat ýüklenmedi.');
        }
    }

    public function getChartData(Request $request)
    {
        try {
            $filter = $request->input('filter', 'day');

            // Start query for DELIVERED orders only (Real money)
            $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.status', 'delivered');

            // PostgreSQL Compatible Grouping
            switch ($filter) {
                case 'day':
                    $query->selectRaw("TO_CHAR(orders.created_at, 'YYYY-MM-DD') as label")
                        ->where('orders.created_at', '>=', Carbon::now()->subDays(30));
                    break;
                case 'week':
                    $query->selectRaw("TO_CHAR(orders.created_at, 'IYYY-IW') as label")
                        ->where('orders.created_at', '>=', Carbon::now()->subWeeks(12));
                    break;
                case 'month':
                    $query->selectRaw("TO_CHAR(orders.created_at, 'YYYY-MM') as label")
                        ->where('orders.created_at', '>=', Carbon::now()->subMonths(12));
                    break;
                case 'year':
                    $query->selectRaw("TO_CHAR(orders.created_at, 'YYYY') as label");
                    break;
            }

            $data = $query->selectRaw('
                    SUM(order_items.price * order_items.quantity) as revenue,
                    SUM(products.cost_price * order_items.quantity) as cost
                ')
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->get();

            $labels = $data->pluck('label');
            $revenues = $data->pluck('revenue')->map(fn($v) => round($v, 2));
            $profits = $data->map(fn($item) => round($item->revenue - $item->cost, 2));

            return response()->json([
                'labels' => $labels,
                'revenues' => $revenues,
                'profits' => $profits
            ]);
        } catch (\Exception $e) {
            Log::error('Chart Error: ' . $e->getMessage());
            return response()->json(['error' => 'Data error'], 500);
        }
    }
}
