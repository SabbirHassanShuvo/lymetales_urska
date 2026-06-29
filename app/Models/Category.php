<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_special',
        'status',
        'language_type',
    ];

    protected $casts = [
        'is_special' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /** Level-1 subcategories (parent_id IS NULL) */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id')->whereNull('parent_id')->orderBy('name');
    }

    /** All subcategories flat (level-1 and level-2) */
    public function allSubcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id')->orderBy('name');
    }

    /**
     * Scope a query to only include special categories.
     */
    public function scopeSpecial($query)
    {
        return $query->where('is_special', true);
    }
}
