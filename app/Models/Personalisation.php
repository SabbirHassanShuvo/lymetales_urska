<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personalisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'child_name',
        'dedication',
        'preview_image',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
