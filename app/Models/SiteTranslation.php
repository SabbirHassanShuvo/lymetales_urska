<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTranslation extends Model
{
    protected $fillable = [
        'key',
        'group',
        'display_name',
        'input_type',
        'value',
        'language_type',
    ];

    /**
     * Get a translation value by key and language.
     * Returns decoded JSON or raw string.
     */
    public static function getTranslation(string $key, string $lang = 'SL'): mixed
    {
        $record = self::where('key', $key)->where('language_type', $lang)->first();
        if (!$record) return null;

        // Try to decode as JSON; fallback to raw string
        $decoded = json_decode($record->value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $record->value;
    }

    /**
     * Get all translations for a language as a nested array (grouped by group).
     */
    public static function getAllForLanguage(string $lang = 'SL'): array
    {
        $all = self::where('language_type', $lang)->get();
        $result = [];
        foreach ($all as $t) {
            $decoded = json_decode($t->value, true);
            $value = json_last_error() === JSON_ERROR_NONE ? $decoded : $t->value;

            // Build nested array from dot-notation key
            $keys = explode('.', $t->key);
            $ref = &$result;
            foreach ($keys as $segment) {
                if (!isset($ref[$segment])) {
                    $ref[$segment] = [];
                }
                $ref = &$ref[$segment];
            }
            $ref = $value;
            unset($ref);
        }
        return $result;
    }
}
