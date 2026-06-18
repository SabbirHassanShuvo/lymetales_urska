<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $pendingUsers = User::where('role', 'user')->where('status', 'pending')->count();
        $approvedUsers = User::where('role', 'user')->where('status', 'approved')->count();
        
        $totalCategories = Category::count();
        $parentCategories = Category::count();
        $subCategories = Subcategory::count();
        $specialCategories = Category::special()->count();
        
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::all()->filter(fn($c) => $c->isValid())->count();

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', true)->count();
        
        // --- REVENUE DATA FOR CHARTS ---
        // Weekly (Last 7 Days)
        $weeklyRevenue = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $weeklyRevenue['labels'][] = $d->format('D');
            $weeklyRevenue['data'][] = \App\Models\Order::where('payment_status', 'paid')
                ->whereDate('created_at', $d->toDateString())
                ->sum('total');
        }

        // Monthly (Last 30 Days)
        $monthlyRevenue = ['labels' => [], 'data' => []];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $monthlyRevenue['labels'][] = $d->format('M d');
            $monthlyRevenue['data'][] = \App\Models\Order::where('payment_status', 'paid')
                ->whereDate('created_at', $d->toDateString())
                ->sum('total');
        }

        // Yearly (Last 12 Months)
        $yearlyRevenue = ['labels' => [], 'data' => []];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->startOfMonth()->subMonths($i);
            $yearlyRevenue['labels'][] = $d->format('M y');
            $yearlyRevenue['data'][] = \App\Models\Order::where('payment_status', 'paid')
                ->whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->sum('total');
        }

        $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total');

        return view('admin.dashboard', compact(
            'totalUsers', 'pendingUsers', 'approvedUsers',
            'totalCategories', 'parentCategories', 'subCategories', 'specialCategories',
            'totalCoupons', 'activeCoupons',
            'totalProducts', 'activeProducts',
            'weeklyRevenue', 'monthlyRevenue', 'yearlyRevenue', 'totalRevenue'
        ));
    }
}
