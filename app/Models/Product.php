<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'pages',
        'age_range',
        'size',
        'characters',
        'cover_type',
        'print_type',
        'paper_type',
        'rating',
        'reviews_count',
        'image',
        'gallery',
        'is_bestseller',
        'is_recommended',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'reviews_count' => 'integer',
        'pages' => 'integer',
        'is_bestseller' => 'boolean',
        'is_recommended' => 'boolean',
        'status' => 'boolean',
        'gallery' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
