<?php

namespace App\Providers;

use App\Services\CartManager;
use App\Services\CouponValidator;
use App\Services\OrderGenerator;
use App\Services\StripeGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // CartManager as singleton so the same instance is shared per request
        $this->app->singleton(CartManager::class, function ($app) {
            return new CartManager();
        });

        $this->app->bind(CouponValidator::class, function ($app) {
            return new CouponValidator();
        });

        $this->app->bind(OrderGenerator::class, function ($app) {
            return new OrderGenerator();
        });

        $this->app->bind(StripeGateway::class, function ($app) {
            return new StripeGateway();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (class_exists(\App\Models\Setting::class) && \Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::pluck('value', 'key')->all();

                // Overwrite Stripe config
                if (isset($settings['stripe_key'])) config(['services.stripe.key' => $settings['stripe_key']]);
                if (isset($settings['stripe_secret'])) config(['services.stripe.secret' => $settings['stripe_secret']]);
                if (isset($settings['stripe_webhook_secret'])) config(['services.stripe.webhook_secret' => $settings['stripe_webhook_secret']]);

                // Overwrite PayPal config
                if (isset($settings['paypal_mode'])) config(['services.paypal.mode' => $settings['paypal_mode']]);
                if (isset($settings['paypal_sandbox_client_id'])) config(['services.paypal.sandbox_client_id' => $settings['paypal_sandbox_client_id']]);
                if (isset($settings['paypal_sandbox_client_secret'])) config(['services.paypal.sandbox_client_secret' => $settings['paypal_sandbox_client_secret']]);
                if (isset($settings['paypal_live_client_id'])) config(['services.paypal.live_client_id' => $settings['paypal_live_client_id']]);
                if (isset($settings['paypal_live_client_secret'])) config(['services.paypal.live_client_secret' => $settings['paypal_live_client_secret']]);

                // Overwrite Mail config
                if (isset($settings['mail_mailer'])) config(['mail.default' => $settings['mail_mailer']]);
                if (isset($settings['mail_host'])) config(['mail.mailers.smtp.host' => $settings['mail_host']]);
                if (isset($settings['mail_port'])) config(['mail.mailers.smtp.port' => $settings['mail_port']]);
                if (isset($settings['mail_username'])) config(['mail.mailers.smtp.username' => $settings['mail_username']]);
                if (isset($settings['mail_password'])) config(['mail.mailers.smtp.password' => $settings['mail_password']]);
                if (isset($settings['mail_encryption']) && $settings['mail_encryption']) {
                    $enc = strtolower($settings['mail_encryption']);
                    $scheme = $enc === 'ssl' ? 'smtps' : ($enc === 'tls' ? 'smtp' : null);
                    
                    config([
                        'mail.mailers.smtp.encryption' => $enc,
                        'mail.mailers.smtp.scheme' => $scheme,
                    ]);
                } else {
                    config([
                        'mail.mailers.smtp.encryption' => null,
                        'mail.mailers.smtp.scheme' => null,
                    ]);
                }
                if (isset($settings['mail_from_address'])) config(['mail.from.address' => $settings['mail_from_address']]);
                if (isset($settings['mail_from_name'])) config(['mail.from.name' => $settings['mail_from_name']]);
            }
        } catch (\Throwable $e) {}
    }
}
