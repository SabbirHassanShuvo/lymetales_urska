<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationSuboption extends Model
{
    protected $fillable = ['substep_id', 'name', 'type', 'color_value', 'image_path', 'is_default', 'sort_order'];

    protected $casts = ['is_default' => 'boolean'];

    public function subStep()
    {
        return $this->belongsTo(ProductCustomizationSubstep::class, 'substep_id');
    }
}
