<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'parent_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /** Parent category */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /** Parent subcategory (level-1 → null, level-2 → level-1 id) */
    public function parent()
    {
        return $this->belongsTo(Subcategory::class, 'parent_id');
    }

    /** Child subcategories (level-2 items under this level-1) */
    public function children()
    {
        return $this->hasMany(Subcategory::class, 'parent_id')->orderBy('name');
    }

    /** Products under this subcategory */
    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /** Only level-1 subcategories (no parent) */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
