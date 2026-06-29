<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterItem extends Model
{
    use HasFactory;

    protected $fillable = ['footer_section_id', 'label', 'url', 'sort_order', 'language_type'];

    public function section()
    {
        return $this->belongsTo(FooterSection::class, 'footer_section_id');
    }
}
