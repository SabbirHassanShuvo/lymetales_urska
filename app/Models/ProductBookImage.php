<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBookImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Include the 'url' accessor in JSON/array output
    protected $appends = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Full URL — works for both local paths and external URLs.
     */
    public function getUrlAttribute(): string
    {
        $path = $this->image_path;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Normalise: ensure exactly one leading slash
        $normalised = '/' . ltrim($path, '/');

        // Use request host when available (browser requests), fall back to APP_URL
        if (app()->runningInConsole() || !request()->getHost()) {
            return rtrim(config('app.url'), '/') . $normalised;
        }

        return request()->getSchemeAndHttpHost() . $normalised;
    }
}
