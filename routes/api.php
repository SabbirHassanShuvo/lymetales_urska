<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\ConfirmationController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\WebhookController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\HomeContentControllerNew;
use App\Http\Controllers\API\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Shop API Routes
|--------------------------------------------------------------------------
| Base URL: /api/shop/...
|
| POST /api/shop/checkout with payment_method=cod    → COD order
| POST /api/shop/checkout with payment_method=stripe → Stripe checkout link
|
*/

Route::prefix('shop')->group(function () {

    // ── Products ───────────────────────────────────────────────────────────
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // ── Reviews ────────────────────────────────────────────────────────────
    Route::get('/reviews', [ReviewController::class, 'allReviews']);
    Route::get('/products/{id}/reviews', [ReviewController::class, 'index']);
    Route::post('/products/{id}/reviews', [ReviewController::class, 'store']);

    // ── Categories ─────────────────────────────────────────────────────────
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'show']);

    // ── Site Categories ────────────────────────────────────────────────────
    Route::get('/site-categories', [\App\Http\Controllers\API\SiteCategoryController::class, 'index']);
    Route::get('/site-categories/{id}', [\App\Http\Controllers\API\SiteCategoryController::class, 'show']);

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

    // Cart fast production toggle
    Route::post('/cart/fast-production', [CartController::class, 'toggleFastProduction']);

    // Personalisation — validate & attach to cart item
    Route::post('/personalisation', [\App\Http\Controllers\API\PersonalisationController::class, 'store']);
    Route::get('/confirmation/{orderNumber}', [ConfirmationController::class, 'show']);

    // Dynamic Pages
    Route::get('/pages/{slug}', [PageController::class, 'show']);

    // Contact Form
    Route::post('/contact', [ContactController::class, 'store']);

    // Newsletter Subscribe
    Route::post('/subscribe', [\App\Http\Controllers\API\SubscriberController::class, 'store']);

    // Home Content (Hero, Features, Gifts, FAQs)
    Route::get('/home-content', [HomeContentControllerNew::class, 'index']);

    // Gifts
    Route::get('/gifts', [\App\Http\Controllers\API\GiftController::class, 'index']);
    Route::get('/offers', [\App\Http\Controllers\API\OfferController::class, 'index']);

    // Global Search 
    Route::get('/search', [SearchController::class, 'search']);
});

// Stripe Webhook (no CSRF, no auth)
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);