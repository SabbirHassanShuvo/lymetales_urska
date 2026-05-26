<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    // যে fields গুলো mass assignment করা যাবে
    protected $fillable = [
        'product_id',
        'reviewer_name',
        'title',
        'reviewer_email',
        'reviewer_location',
        'rating',
        'comment',
        'is_approved',
    ];

    // Data type conversion
    protected $casts = [
        'rating' => 'decimal:1',
        'is_approved' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    /**
     * এই review কোন product এর
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}