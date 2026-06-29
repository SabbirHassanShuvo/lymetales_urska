<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'button_one_text',
        'button_one_link',
        'button_two_text',
        'button_two_link',
        'image_path',
        'language_type',
    ];
}
