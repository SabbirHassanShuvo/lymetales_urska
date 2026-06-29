<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderConfirmedMail;
use App\Mail\OrderShippedMail;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // For Cash on Delivery, order is confirmed immediately
        if (strtolower($order->payment_method) === 'cod') {
            try {
                Mail::to($order->email)->send(new OrderConfirmedMail($order));
            } catch (\Exception $e) {
                Log::error("Failed to send order confirmed email for COD order {$order->order_number}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Order Confirmed (Paid via Stripe or PayPal)
        if ($order->wasChanged('payment_status') && $order->payment_status === 'paid' && strtolower($order->payment_method) !== 'cod') {
            try {
                Mail::to($order->email)->send(new OrderConfirmedMail($order));
            } catch (\Exception $e) {
                Log::error("Failed to send order confirmed email for paid order {$order->order_number}: " . $e->getMessage());
            }
        }

        // Order status changes
        if ($order->wasChanged('order_status')) {
            $status = strtolower($order->order_status);
            
            if ($status === 'shipped') {
                try {
                    Mail::to($order->email)->send(new OrderShippedMail($order));
                } catch (\Exception $e) {
                    Log::error("Failed to send order shipped email for order {$order->order_number}: " . $e->getMessage());
                }
            } elseif (in_array($status, ['processing', 'delivered', 'cancelled'])) {
                try {
                    Mail::to($order->email)->send(new OrderStatusChangedMail($order, $status));
                } catch (\Exception $e) {
                    Log::error("Failed to send order status changed email ({$status}) for order {$order->order_number}: " . $e->getMessage());
                }
            }
        }
    }
}

