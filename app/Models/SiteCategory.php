<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_special',
        'status',
    ];

    protected $casts = [
        'is_special' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::created(function ($category) {
            $category->subcategories()->createMany([
                [
                    'name'        => 'NEWBORNS',
                    'description' => 'Books for newborns',
                    'status'      => true,
                ],
                [
                    'name'        => 'KIDS',
                    'description' => 'Books for kids',
                    'status'      => true,
                ],
                [
                    'name'        => 'ADULT',
                    'description' => 'Books for adults',
                    'status'      => true,
                ],
            ]);
        });
    }

    public function subcategories()
    {
        return $this->hasMany(SiteSubcategory::class, 'site_category_id')->orderBy('name');
    }
}
