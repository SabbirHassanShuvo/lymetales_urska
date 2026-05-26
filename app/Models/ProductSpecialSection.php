<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecialSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'subtitle',
        'title',
        'description',
        'image',
        'sort_order',
    ];

    /**
     * Get the product that owns the special section.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
