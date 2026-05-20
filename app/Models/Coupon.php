<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'description',
        'expiry_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'status' => 'boolean',
        'value' => 'decimal:2',
    ];

    /**
     * Check if the coupon is expired.
     */
    public function isExpired(): bool
    {
        if (is_null($this->expiry_date)) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid(): bool
    {
        if (!$this->status) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
