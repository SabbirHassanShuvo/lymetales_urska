<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationStep extends Model
{
    protected $fillable = ['product_id', 'name', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(ProductCustomizationOption::class, 'step_id')->orderBy('sort_order');
    }
}
