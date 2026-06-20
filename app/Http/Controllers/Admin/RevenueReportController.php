<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        // Calculate top level metrics for realized revenue
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        
        $orderCount = Order::where('payment_status', 'paid')->count();
        $averageOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;
        
        // Breakdown by payment method
        $stripeRevenue = Order::where('payment_status', 'paid')
            ->where('payment_method', 'stripe')
            ->sum('total');
            
        $codRevenue = Order::where('payment_status', 'paid')
            ->where('payment_method', 'cod')
            ->sum('total');

        // Detailed order list (revenue generating only)
        // With basic pagination
        $orders = Order::where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.revenue', compact(
            'totalRevenue',
            'orderCount',
            'averageOrderValue',
            'stripeRevenue',
            'codRevenue',
            'orders'
        ));
    }
}
