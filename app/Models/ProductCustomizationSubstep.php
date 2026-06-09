<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationSubstep extends Model
{
    protected $fillable = ['option_id', 'name', 'type', 'color_value', 'sort_order'];

    public function option()
    {
        return $this->belongsTo(ProductCustomizationOption::class, 'option_id');
    }

    public function subOptions()
    {
        return $this->hasMany(ProductCustomizationSuboption::class, 'substep_id')->orderBy('sort_order');
    }
}
