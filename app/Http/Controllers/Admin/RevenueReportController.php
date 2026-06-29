<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $preset   = $request->input('date_preset', 'this_month');
        $dateFrom = null;
        $dateTo   = null;

        if ($preset === 'today') {
            $dateFrom = Carbon::today()->toDateString();
            $dateTo   = Carbon::today()->toDateString();
        } elseif ($preset === 'this_week') {
            $dateFrom = Carbon::now()->startOfWeek()->toDateString();
            $dateTo   = Carbon::now()->endOfWeek()->toDateString();
        } elseif ($preset === 'this_month') {
            $dateFrom = Carbon::now()->startOfMonth()->toDateString();
            $dateTo   = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($preset === 'this_year') {
            $dateFrom = Carbon::now()->startOfYear()->toDateString();
            $dateTo   = Carbon::now()->endOfYear()->toDateString();
        } elseif ($preset === 'all') {
            $dateFrom = null;
            $dateTo   = null;
        } elseif ($preset === 'custom') {
            $dateFrom = $request->input('date_from');
            $dateTo   = $request->input('date_to');
        } else {
            $preset   = 'this_month';
            $dateFrom = Carbon::now()->startOfMonth()->toDateString();
            $dateTo   = Carbon::now()->endOfMonth()->toDateString();
        }

        // ── Revenue Comparison (for the animated bar section) ──────────────
        $revenueByPeriod = [
            'today'      => Order::where('payment_status', 'paid')->whereDate('created_at', Carbon::today())->sum('total'),
            'this_week'  => Order::where('payment_status', 'paid')->whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->sum('total'),
            'this_month' => Order::where('payment_status', 'paid')->whereDate('created_at', '>=', Carbon::now()->startOfMonth())->whereDate('created_at', '<=', Carbon::now()->endOfMonth())->sum('total'),
            'this_year'  => Order::where('payment_status', 'paid')->whereDate('created_at', '>=', Carbon::now()->startOfYear())->whereDate('created_at', '<=', Carbon::now()->endOfYear())->sum('total'),
            'all'        => Order::where('payment_status', 'paid')->sum('total'),
        ];

        // ── Main query (filtered) ──────────────────────────────────────────
        $baseQuery = Order::where('payment_status', 'paid');
        if ($dateFrom) $baseQuery->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $baseQuery->whereDate('created_at', '<=', $dateTo);

        $allOrders = (clone $baseQuery)->orderBy('created_at', 'desc')->get();
        $orders    = (clone $baseQuery)->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $vatRate        = (float) env('SHOP_VAT_RATE', 22.00);
        $orderCount     = $allOrders->count();
        $grossRevenue   = $allOrders->sum('total');
        $totalShipping  = $allOrders->sum('shipping_fee') + $allOrders->sum('fast_production_fee');
        $totalDiscounts = $allOrders->sum('discount');

        $taxableAmount = $allOrders->sum('subtotal') - $totalDiscounts;
        if ($taxableAmount < 0) $taxableAmount = 0.0;

        $totalVat          = $taxableAmount * ($vatRate / (100 + $vatRate));
        $netRevenue        = $taxableAmount / (1 + ($vatRate / 100));
        $averageOrderValue = $orderCount > 0 ? $netRevenue / $orderCount : 0;

        $stripeRevenue = $allOrders->where('payment_method', 'stripe')->sum('total');
        $codRevenue    = $allOrders->where('payment_method', 'cod')->sum('total');
        $paypalRevenue = $allOrders->where('payment_method', 'paypal')->sum('total');

        // Product Performance
        $items = collect();
        foreach ($allOrders as $order) {
            $orderItems = is_array($order->items) ? $order->items : json_decode($order->items, true);
            if (is_array($orderItems)) {
                foreach ($orderItems as $item) {
                    $items->push([
                        'product_id' => $item['id'] ?? ($item['product_id'] ?? 'unknown'),
                        'title'      => $item['title'] ?? 'Unknown Product',
                        'quantity'   => (int)($item['quantity'] ?? 1),
                        'price'      => (float)($item['price'] ?? 0),
                        'line_total' => (float)($item['line_total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1))),
                    ]);
                }
            }
        }

        $productStats = $items->groupBy('product_id')->map(function ($group) {
            return [
                'title'    => $group->first()['title'],
                'quantity' => $group->sum('quantity'),
                'revenue'  => $group->sum('line_total'),
            ];
        })->sortByDesc('revenue')->values()->all();

        // Coupon Stats
        $couponStats = \App\Models\Coupon::leftJoin('orders', function ($join) use ($dateFrom, $dateTo) {
            $join->on('coupons.code', '=', 'orders.coupon_code')
                 ->where('orders.payment_status', '=', 'paid');
            if ($dateFrom) $join->whereDate('orders.created_at', '>=', $dateFrom);
            if ($dateTo)   $join->whereDate('orders.created_at', '<=', $dateTo);
        })
        ->select(
            'coupons.id', 'coupons.code', 'coupons.type', 'coupons.value',
            \DB::raw('COUNT(orders.id) as times_used'),
            \DB::raw('SUM(orders.discount) as total_discount_given')
        )
        ->groupBy('coupons.id', 'coupons.code', 'coupons.type', 'coupons.value')
        ->orderByDesc('times_used')
        ->get();

        return view('admin.reports.revenue', compact(
            'grossRevenue', 'netRevenue', 'totalShipping', 'totalDiscounts',
            'totalVat', 'orderCount', 'averageOrderValue',
            'stripeRevenue', 'codRevenue', 'paypalRevenue',
            'orders', 'productStats', 'couponStats',
            'dateFrom', 'dateTo', 'preset', 'vatRate',
            'revenueByPeriod'
        ));
    }
}
