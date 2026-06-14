<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sort_order'];

    public function items()
    {
        return $this->hasMany(FooterItem::class)->orderBy('sort_order');
    }
}
