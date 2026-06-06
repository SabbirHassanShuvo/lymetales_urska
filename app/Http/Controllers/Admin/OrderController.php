<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders for the admin panel.
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * DELETE /admin/orders/{order}
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Updates order_status for any order (COD or Stripe).
     * Allowed values: pending, processing, shipped, delivered, cancelled
     */
    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'string', Rule::in([
                'pending', 'processing', 'shipped', 'delivered', 'cancelled',
            ])],
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        return response()->json([
            'success'      => true,
            'order_status' => $order->order_status,
        ]);
    }

    /**
     * PATCH /admin/orders/{order}/payment-status
     * Updates payment_status for COD orders only.
     * Stripe order payment status is controlled exclusively by webhooks.
     */
    public function updatePaymentStatus(Request $request, Order $order): JsonResponse
    {
        if ($order->payment_method === 'stripe') {
            return response()->json([
                'success' => false,
                'message' => 'Payment status for Stripe orders is managed by webhooks.',
            ], 422);
        }

        $validated = $request->validate([
            'payment_status' => ['required', 'string', Rule::in(['pending', 'paid', 'failed'])],
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return response()->json([
            'success'        => true,
            'payment_status' => $order->payment_status,
        ]);
    }
}
