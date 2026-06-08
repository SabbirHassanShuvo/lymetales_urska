<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationOption extends Model
{
    protected $fillable = ['step_id', 'name', 'image_path', 'is_default', 'sort_order'];

    protected $casts = ['is_default' => 'boolean'];

    public function step()
    {
        return $this->belongsTo(ProductCustomizationStep::class, 'step_id');
    }

    public function subSteps()
    {
        return $this->hasMany(ProductCustomizationSubstep::class, 'option_id')->orderBy('sort_order');
    }
}
