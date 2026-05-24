<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\ConfirmationController;
use App\Http\Controllers\API\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Shop API Routes
|--------------------------------------------------------------------------
| Base URL: /api/shop/...
|
| POST /api/shop/checkout with payment_method=cod    → COD order
| POST /api/shop/checkout with payment_method=stripe → Stripe checkout link
*/

Route::prefix('shop')->group(function () {

    // Cart
    Route::get('/cart',          [CartController::class, 'index']);
    Route::post('/cart/add',     [CartController::class, 'add']);
    Route::post('/cart/update',  [CartController::class, 'update']);
    Route::post('/cart/remove',  [CartController::class, 'remove']);

    // Checkout (COD + Stripe — single endpoint)
    Route::get('/checkout',  [CheckoutController::class, 'index']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // Coupon
    Route::post('/coupon/apply',  [CheckoutController::class, 'applyCoupon']);
    Route::post('/coupon/remove', [CheckoutController::class, 'removeCoupon']);

    // Order Confirmation
    Route::get('/confirmation/{orderNumber}', [ConfirmationController::class, 'show']);
});

// Stripe Webhook (no CSRF, no auth)
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);
