<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Basic Stats
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount') ?? 0,
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // Monthly Revenue Data for Chart
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // Best Selling Products
        $bestSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', 'products.sku', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats', 
            'monthlyRevenue', 
            'recentOrders', 
            'lowStockProducts',
            'bestSellingProducts'
        ));
    }
}