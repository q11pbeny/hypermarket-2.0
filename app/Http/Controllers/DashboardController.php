<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_customers' => Customer::count(),
            'total_suppliers' => Supplier::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('stock_quantity', '<', 10)->count(),
        ];

        // داده‌های نمودار فروش ماهانه
        $monthly_sales = Order::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                $monthNames = [
                    1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
                    4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
                    7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
                    10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
                ];
                return [$monthNames[$item->month] => $item->total];
            });

        // محصولات پرفروش
        $top_products = Product::withCount(['orderItems as total_sales' => function($query) {
            $query->select(\DB::raw('SUM(quantity)'));
        }])
        ->orderBy('total_sales', 'desc')
        ->take(5)
        ->get();

        // مشتریان برتر
        $top_customers = Customer::orderBy('total_purchases', 'desc')
            ->take(5)
            ->get();

        // آمار دسته‌بندی‌ها
        $category_stats = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        $recent_orders = Order::with('customer')->latest()->take(5)->get();
        $low_stock_products = Product::with('category')->where('stock_quantity', '<', 10)->get();

        return view('dashboard', compact(
            'stats', 
            'recent_orders', 
            'low_stock_products',
            'monthly_sales',
            'top_products',
            'top_customers',
            'category_stats'
        ));
    }
}
