<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class SiteTranslationController extends Controller
{
    private array $groups = [
        'global'       => 'Global',
        'home'         => 'Home Page',
        'books'        => 'Books Page',
        'book_details' => 'Book Details Page',
        'search'       => 'Search',
        'contact'      => 'Contact Page',
        'coupon'       => 'Coupon Dialog',
        'cookie'       => 'Cookie Modal',
        'orders'       => 'Orders (Confirmed & Failed)',
    ];

    private array $langs = ['EN', 'HR', 'SL'];

    public function index(Request $request)
    {
        // Auto-run migration and seeder if table does not exist
        if (!Schema::hasTable('site_translations')) {
            Artisan::call('migrate', ['--force' => true]);
            if (file_exists(database_path('seeders/SiteTranslationSeeder.php'))) {
                require_once database_path('seeders/SiteTranslationSeeder.php');
            }
            Artisan::call('db:seed', ['--class' => 'SiteTranslationSeeder', '--force' => true]);
        }

        $lang  = strtoupper($request->input('lang', 'SL'));
        $group = $request->input('group', 'global');

        // Fetch master list of keys from the default language 'SL'
        $defaultTranslations = SiteTranslation::where('language_type', 'SL')
            ->where('group', $group)
            ->orderBy('key')
            ->get();

        // Fetch the translations for the currently selected language
        $currentTranslations = SiteTranslation::where('language_type', $lang)
            ->where('group', $group)
            ->get()
            ->keyBy('key');

        // Map over the master list and inject the translated values (if they exist)
        $translations = $defaultTranslations->map(function ($item) use ($currentTranslations) {
            $mapped = clone $item;
            if ($currentTranslations->has($item->key)) {
                $mapped->value = $currentTranslations->get($item->key)->value;
            } else {
                // If the value hasn't been translated yet, leave it empty
                $mapped->value = '';
            }
            return $mapped;
        });

        return view('admin.translations.index', [
            'translations' => $translations,
            'groups'       => $this->groups,
            'langs'        => $this->langs,
            'currentLang'  => $lang,
            'currentGroup' => $group,
        ]);
    }

    public function update(Request $request)
    {
        $lang  = strtoupper($request->input('lang', 'SL'));
        $group = $request->input('group', 'global');

        $updates = $request->input('translations', []);

        foreach ($updates as $key => $value) {
            // Sanitize the key (dot notation only allowed)
            $key = preg_replace('/[^a-z0-9._]/', '', strtolower($key));
            if (empty($key)) continue;

            // If it's a JSON field, validate JSON
            $record = SiteTranslation::where('key', $key)
                ->where('language_type', $lang)
                ->first();

            if ($record && $record->input_type === 'json') {
                // Try to parse — if it fails, skip to avoid corrupting data
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }
                // Re-encode to keep consistent formatting
                $value = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            SiteTranslation::updateOrCreate(
                ['key' => $key, 'language_type' => $lang],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Translations updated successfully.');
    }
}
