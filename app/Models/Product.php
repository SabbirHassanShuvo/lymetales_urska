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
        'subcategory_id',
        'title',
        'slug',
        'description',
        'name_text',
        'name_font_family',
        'name_top',
        'name_color',
        'name_font_size',
        'name_right',
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
        'is_bestseller',
        'is_recommended',
        'status',
        'domain',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'rating'         => 'decimal:1',
        'reviews_count'  => 'integer',
        'pages'          => 'integer',
        'is_bestseller'  => 'boolean',
        'is_recommended' => 'boolean',
        'status'         => 'boolean',
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

    // ── Relationships ──────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /** Special sections */
    public function specialSections()
    {
        return $this->hasMany(ProductSpecialSection::class)->orderBy('sort_order');
    }

    /** All images ordered by sort_order */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /** Primary (cover) image */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /** Gallery images (non-primary) */
    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)
            ->where('is_main', false)
            ->orderBy('sort_order');
    }

    /** Category images */
    public function categoryImages()
    {
        return $this->hasMany(ProductCategoryImage::class)->orderBy('sort_order');
    }

    /** Customization steps (Gender → Boy/Girl → Hair Color → Red/Black) */
    public function customizationSteps()
    {
        return $this->hasMany(\App\Models\ProductCustomizationStep::class)->orderBy('sort_order');
    }

    /** All reviews */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    /** Only approved reviews */
    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)
            ->where('is_approved', true)
            ->latest();
    }

    // ── Convenience accessors ──────────────────────────────────────────────

    /** Returns the URL of the primary image, or null */
    public function getImageUrlAttribute(): ?string
    {
        $img = $this->relationLoaded('primaryImage')
            ? $this->primaryImage
            : $this->primaryImage()->first();

        return $img?->url;
    }
}
