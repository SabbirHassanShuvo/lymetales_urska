<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        // Override SMTP and Payment Gateway keys with actual .env values
        $envData = $this->parseEnv();
        $envOverrideKeys = [
            'stripe_key'                  => 'STRIPE_KEY',
            'stripe_secret'               => 'STRIPE_SECRET',
            'stripe_webhook_secret'       => 'STRIPE_WEBHOOK_SECRET',
            'mail_mailer'                 => 'MAIL_MAILER',
            'mail_host'                   => 'MAIL_HOST',
            'mail_port'                   => 'MAIL_PORT',
            'mail_username'               => 'MAIL_USERNAME',
            'mail_password'               => 'MAIL_PASSWORD',
            'mail_encryption'             => 'MAIL_ENCRYPTION',
            'mail_from_address'           => 'MAIL_FROM_ADDRESS',
            'mail_from_name'              => 'MAIL_FROM_NAME',
            'paypal_mode'                 => 'PAYPAL_MODE',
            'paypal_sandbox_client_id'    => 'PAYPAL_SANDBOX_CLIENT_ID',
            'paypal_sandbox_client_secret'=> 'PAYPAL_SANDBOX_CLIENT_SECRET',
            'paypal_live_client_id'       => 'PAYPAL_LIVE_CLIENT_ID',
            'paypal_live_client_secret'   => 'PAYPAL_LIVE_CLIENT_SECRET',
        ];
        foreach ($envOverrideKeys as $dbKey => $envKey) {
            if (isset($envData[$envKey])) {
                $settings[$dbKey] = $envData[$envKey];
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shipping_charge' => 'required|numeric|min:0',
            'fast_production_fee' => 'required|numeric|min:0',
            'global_discount_type' => 'required|in:fixed,percentage',
            'global_discount_value' => 'required|numeric|min:0',
            'whatsapp_url' => 'nullable|url|max:255',
            'admin_logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'admin_site_name' => 'nullable|string|max:100',
            'admin_tagline' => 'nullable|string|max:200',
            'admin_login_bg' => 'nullable|string|max:50',
            'admin_accent_color' => 'nullable|string|max:20',
            'stripe_key' => 'nullable|string|max:255',
            'stripe_secret' => 'nullable|string|max:255',
            'stripe_webhook_secret' => 'nullable|string|max:255',
            'mail_mailer' => 'nullable|string|in:smtp,log',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:0',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'paypal_mode' => 'nullable|string|in:sandbox,live',
            'paypal_sandbox_client_id' => 'nullable|string|max:255',
            'paypal_sandbox_client_secret' => 'nullable|string|max:255',
            'paypal_live_client_id' => 'nullable|string|max:255',
            'paypal_live_client_secret' => 'nullable|string|max:255',
        ]);

        // ── Handle Admin Logo Upload / Removal ──────────────────────────────
        if ($request->boolean('remove_admin_logo')) {
            $oldLogo = Setting::getVal('admin_logo_path', '');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }
            Setting::updateOrCreate(['key' => 'admin_logo_path'], ['value' => '']);
        } elseif ($request->hasFile('admin_logo_file')) {
            $oldLogo = Setting::getVal('admin_logo_path', '');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }
            $file = $request->file('admin_logo_file');
            if (!is_dir(public_path('uploads/settings'))) {
                mkdir(public_path('uploads/settings'), 0755, true);
            }
            $filename = 'admin_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::updateOrCreate(['key' => 'admin_logo_path'], ['value' => 'uploads/settings/' . $filename]);
        }

        // ── Handle accent color (use hex input text over color picker) ──────
        $accentColor = $request->input('admin_accent_color_text') ?: $request->input('admin_accent_color');
        if ($accentColor) {
            Setting::updateOrCreate(['key' => 'admin_accent_color'], ['value' => $accentColor]);
        }

        $keys = [
            'shipping_charge', 'fast_production_fee', 'global_discount_type', 'global_discount_value',
            'whatsapp_url',
            'admin_site_name', 'admin_tagline', 'admin_login_bg',
            'stripe_key', 'stripe_secret', 'stripe_webhook_secret',
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            'paypal_mode', 'paypal_sandbox_client_id', 'paypal_sandbox_client_secret', 'paypal_live_client_id', 'paypal_live_client_secret'
        ];

        // Map database setting keys to .env variable names
        $envMap = [
            'stripe_key'                   => 'STRIPE_KEY',
            'stripe_secret'                => 'STRIPE_SECRET',
            'stripe_webhook_secret'        => 'STRIPE_WEBHOOK_SECRET',
            'mail_mailer'                  => 'MAIL_MAILER',
            'mail_host'                    => 'MAIL_HOST',
            'mail_port'                    => 'MAIL_PORT',
            'mail_username'                => 'MAIL_USERNAME',
            'mail_password'                => 'MAIL_PASSWORD',
            'mail_encryption'              => 'MAIL_ENCRYPTION',
            'mail_from_address'            => 'MAIL_FROM_ADDRESS',
            'mail_from_name'               => 'MAIL_FROM_NAME',
            'paypal_mode'                  => 'PAYPAL_MODE',
            'paypal_sandbox_client_id'     => 'PAYPAL_SANDBOX_CLIENT_ID',
            'paypal_sandbox_client_secret' => 'PAYPAL_SANDBOX_CLIENT_SECRET',
            'paypal_live_client_id'        => 'PAYPAL_LIVE_CLIENT_ID',
            'paypal_live_client_secret'    => 'PAYPAL_LIVE_CLIENT_SECRET',
        ];

        foreach ($keys as $key) {
            $value = $request->input($key);
            
            // Save to database
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            // Sync to .env if mapped
            if (array_key_exists($key, $envMap)) {
                $envKey = $envMap[$key];
                $this->updateEnv($envKey, $value);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Parse the .env file and return key-value pairs (strips quotes).
     */
    private function parseEnv(): array
    {
        $path = base_path('.env');
        if (!file_exists($path)) return [];

        $result = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (!str_contains($line, '=')) continue;
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Strip surrounding quotes
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Update or append environment variable in .env file.
     */
    private function updateEnv(string $key, ?string $value): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);
        $value = $value ?? '';

        // If the value has spaces or comment character, wrap it in double quotes
        if (preg_match('/\s/', $value) || strpos($value, '#') !== false) {
            $value = '"' . $value . '"';
        }

        $escapedKey = preg_quote($key, '/');

        // Match exact key line or a commented out key line
        if (preg_match("/^{$escapedKey}=.*/m", $content)) {
            // Replace existing active key
            $content = preg_replace("/^{$escapedKey}=.*/m", "{$key}={$value}", $content);
        } elseif (preg_match("/^#\s*{$escapedKey}=.*/m", $content)) {
            // Replace commented key with active one
            $content = preg_replace("/^#\s*{$escapedKey}=.*/m", "{$key}={$value}", $content);
        } else {
            // Key doesn't exist, append it at the end
            $content = rtrim($content) . "\n{$key}={$value}\n";
        }

        file_put_contents($path, $content);
    }
}
