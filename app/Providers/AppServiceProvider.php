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
        //
    }
}
