<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_category_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(SiteCategory::class, 'site_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
