<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'order_status',
        'payment_status',
        'email',
        'full_name',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'items',
        'subtotal',
        'shipping_fee',
        'fast_production_fee',
        'discount',
        'coupon_code',
        'total',
        'payment_method',
        'stripe_payment_intent_id',
        'paypal_order_id',
        'source',
        'tracking_number',
        'tracking_link',
    ];

    protected $casts = [
        'items'               => 'array',
        'subtotal'            => 'decimal:2',
        'shipping_fee'        => 'decimal:2',
        'fast_production_fee' => 'decimal:2',
        'discount'            => 'decimal:2',
        'total'               => 'decimal:2',
    ];

    /**
     * Get the coupon used for this order.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }
}

