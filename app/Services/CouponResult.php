<?php

namespace App\Services;

class CouponResult
{
    public readonly string $code;
    public readonly string $type;       // percent | fixed | free_shipping
    public readonly float  $discount;   // monetary amount to deduct
    public readonly bool   $freeShipping;

    public function __construct(string $code, string $type, float $discount, bool $freeShipping)
    {
        $this->code = $code;
        $this->type = $type;
        $this->discount = $discount;
        $this->freeShipping = $freeShipping;
    }
}
