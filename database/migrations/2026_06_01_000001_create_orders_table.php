<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 12)->unique()->index(); // e.g. LYM-MOBXGZBF
            $table->string('status', 20)->default('pending');     // pending | paid | failed

            // Contact & Shipping
            $table->string('email');
            $table->string('full_name', 100);
            $table->string('address', 255);
            $table->string('city', 100);
            $table->string('postal_code', 20);
            $table->string('country', 100);
            $table->string('phone', 20);

            // Cart snapshot (JSON)
            $table->json('items');
            // Each item: { product_id, title, image, unit_price, quantity, line_total }

            // Financials
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('fast_production_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 10, 2);

            // Payment
            $table->string('payment_method', 20);                 // cod | stripe
            $table->string('stripe_payment_intent_id')->nullable();

            $table->timestamps();

            // Index for fast webhook lookups
            $table->index('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
