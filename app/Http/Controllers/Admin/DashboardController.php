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
        
        return view('admin.dashboard', compact(
            'totalUsers', 'pendingUsers', 'approvedUsers',
            'totalCategories', 'parentCategories', 'subCategories', 'specialCategories',
            'totalCoupons', 'activeCoupons',
            'totalProducts', 'activeProducts'
        ));
    }
}
