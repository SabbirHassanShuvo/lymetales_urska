<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class ConfirmationController extends Controller
{
    /**
     * GET /api/shop/confirmation/{orderNumber}
     */
    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
            'status'       => $order->status,
            'email'        => $order->email,
            'full_name'    => $order->full_name,
            'total'        => config('shop.currency_symbol', '€') . number_format($order->total, 2),
            'items'        => $order->items,
            'created_at'   => $order->created_at->toDateTimeString(),
        ]);
    }
}
