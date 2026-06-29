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
        'language_type',
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
                    'name'        => 'NOVOROJENČKI',
                    'description' => 'Knjige za novorojenčke',
                    'status'      => true,
                ],
                [
                    'name'        => 'OTROCI',
                    'description' => 'Knjige za otroke',
                    'status'      => true,
                ],
                [
                    'name'        => 'ODRASLI',
                    'description' => 'Knjige za odrasle',
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
