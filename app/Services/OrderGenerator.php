<?php

namespace App\Services;

use App\Exceptions\OrderException;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderGenerator
{
    /**
     * Generate a unique LYM-XXXXXXXX order number.
     *
     * @throws OrderException if all retry attempts collide.
     */
    private function generateOrderNumber(): string
    {
        $prefix  = config('shop.order_number_prefix', 'LYM');
        $length  = config('shop.order_number_length', 8);
        $retries = config('shop.order_number_retries', 10);
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        for ($attempt = 1; $attempt <= $retries; $attempt++) {
            $suffix = '';
            for ($i = 0; $i < $length; $i++) {
                $suffix .= $charset[random_int(0, strlen($charset) - 1)];
            }
            $number = $prefix . '-' . $suffix;

            if (! Order::where('order_number', $number)->exists()) {
                return $number;
            }
        }

        Log::error("Failed to generate unique order number after {$retries} attempts.");
        throw new OrderException('Order could not be placed. Please try again.');
    }

    /**
     * Create and persist an Order from validated checkout data.
     *
     * @param  array       $checkoutData  Validated fields from CheckoutRequest
     * @param  array       $cartItems     Items from CartManager::items()
     * @param  array|null  $coupon        Applied coupon session data (or null)
     * @return Order
     *
     * @throws OrderException
     */
    public function create(array $checkoutData, array $cartItems, ?array $coupon): Order
    {
        $orderNumber = $this->generateOrderNumber();

        // Calculate financials
        $subtotal          = round(array_sum(array_column($cartItems, 'line_total')), 2);

        // Calculate subtotal of products (excluding gifts) for offer discount calculation
        $productsSubtotal = 0.0;
        $totalProductQuantity = 0;
        foreach ($cartItems as $item) {
            if (($item['type'] ?? 'product') === 'product') {
                $productsSubtotal += $item['line_total'];
                $totalProductQuantity += $item['quantity'];
            }
        }

        // Offer discount
        $offerDiscount = 0.0;
        $activeOffer = \App\Models\Offer::where('is_active', true)
            ->where('min_quantity', '<', $totalProductQuantity)
            ->first();
        if ($activeOffer) {
            $offerDiscount = round($productsSubtotal * ($activeOffer->discount_percentage / 100), 2);
        }

        $shippingFee       = ($coupon && ($coupon['free_shipping'] ?? false))
                                ? 0.00
                                : round((float) config('shop.shipping_fee', 5.95), 2);
        $fastProductionFee = ! empty($checkoutData['fast_production'])
                                ? round((float) config('shop.fast_production_fee', 9.95), 2)
                                : 0.00;
        $couponDiscount    = $coupon ? round((float) ($coupon['discount'] ?? 0), 2) : 0.00;
        $discount          = round($couponDiscount + $offerDiscount, 2);
        $couponCode        = $coupon ? ($coupon['code'] ?? null) : null;
        $total             = round($subtotal - $discount + $shippingFee + $fastProductionFee, 2);
        if ($total < 0) {
            $total = 0.00;
        }

        try {
            $order = DB::transaction(function () use (
                $orderNumber, $checkoutData, $cartItems,
                $subtotal, $shippingFee, $fastProductionFee,
                $discount, $couponCode, $total
            ) {
                $order = Order::create([
                    'order_number'             => $orderNumber,
                    'order_status'             => 'pending',
                    'payment_status'           => 'pending',
                    'email'                    => $checkoutData['email'],
                    'full_name'                => $checkoutData['full_name'],
                    'address'                  => $checkoutData['address'],
                    'city'                     => $checkoutData['city'],
                    'postal_code'              => $checkoutData['postal_code'],
                    'country'                  => $checkoutData['country'],
                    'phone'                    => $checkoutData['phone'],
                    'items'                    => $cartItems,
                    'subtotal'                 => $subtotal,
                    'shipping_fee'             => $shippingFee,
                    'fast_production_fee'      => $fastProductionFee,
                    'discount'                 => $discount,
                    'coupon_code'              => $couponCode,
                    'total'                    => $total,
                    'payment_method'           => $checkoutData['payment_method'],
                    'stripe_payment_intent_id' => $checkoutData['stripe_payment_intent_id'] ?? null,
                ]);

                if ($couponCode) {
                    Coupon::where('code', $couponCode)->increment('used_count');
                }

                return $order;
            });
        } catch (\Throwable $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            throw new OrderException('Order could not be placed. Please try again.');
        }

        return $order;
    }
}
