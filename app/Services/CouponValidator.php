<?php

namespace App\Services;

use App\Exceptions\CouponException;
use App\Models\Coupon;

class CouponValidator
{
    /**
     * Validate a coupon code against the given subtotal.
     *
     * Ordered checks:
     *   1. Case-insensitive lookup → not found: CouponException("Coupon code not found.")
     *   2. status === true         → inactive:  CouponException("This coupon is inactive.")
     *   3. isExpired() === false   → expired:   CouponException("This coupon has expired.")
     *   4. used_count < usage_limit (or null) → CouponException("This coupon has reached its usage limit.")
     *   5. Calculate discount and return CouponResult.
     *
     * @throws CouponException
     */
    public function validate(string $code, float $subtotal): CouponResult
    {
        // 1. Case-insensitive lookup
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();

        if ($coupon === null) {
            throw new CouponException('Coupon code not found.');
        }

        // 2. Status check
        if (! $coupon->status) {
            throw new CouponException('This coupon is inactive.');
        }

        // 3. Expiry check
        if ($coupon->isExpired()) {
            throw new CouponException('This coupon has expired.');
        }

        // 4. Usage limit check
        if (! is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
            throw new CouponException('This coupon has reached its usage limit.');
        }

        // 5. Calculate discount
        $discount    = 0.0;
        $freeShipping = false;

        switch ($coupon->type) {
            case 'percent':
                $discount = round(((float) $coupon->value / 100) * $subtotal, 2);
                break;

            case 'fixed':
                $discount = min(round((float) $coupon->value, 2), $subtotal);
                break;

            case 'free_shipping':
                $discount    = 0.0;
                $freeShipping = true;
                break;
        }

        return new CouponResult(
            code: $coupon->code,
            type: $coupon->type,
            discount: $discount,
            freeShipping: $freeShipping,
        );
    }
}
