<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftGiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle',
        'title',
        'step_1_image',
        'step_1_text',
        'step_2_image',
        'step_2_text',
        'step_3_image',
        'step_3_text',
    ];
}
