<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'min_quantity',
        'discount_percentage',
        'is_active',
        'language_type',
    ];

    protected $casts = [
        'min_quantity'        => 'integer',
        'discount_percentage' => 'decimal:2',
        'is_active'          => 'boolean',
    ];
}
