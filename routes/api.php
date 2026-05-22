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
| All routes are stateful (session-based) so the cart persists across
| requests. No authentication required.
|
| Base URL: /api/shop/...
*/

Route::prefix('shop')->group(function () {

    // Cart
    Route::get('/cart',           [CartController::class, 'index']);
    Route::post('/cart/add',      [CartController::class, 'add']);
    Route::patch('/cart/update',  [CartController::class, 'update']);
    Route::delete('/cart/remove', [CartController::class, 'remove']);

    // Checkout
    Route::get('/checkout',  [CheckoutController::class, 'index']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // Coupon
    Route::post('/coupon/apply',    [CheckoutController::class, 'applyCoupon']);
    Route::delete('/coupon/remove', [CheckoutController::class, 'removeCoupon']);

    // Stripe PaymentIntent
    Route::post('/payment/intent', [CheckoutController::class, 'createPaymentIntent']);

    // Order Confirmation
    Route::get('/confirmation/{orderNumber}', [ConfirmationController::class, 'show']);
});

// Stripe Webhook (no CSRF, no auth)
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);
