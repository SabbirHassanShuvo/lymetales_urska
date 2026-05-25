<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `order_status` (fulfillment lifecycle) and `payment_status` (payment lifecycle)
     * columns to the orders table. The legacy `status` column is made nullable (deprecated,
     * not removed) so existing rows are not broken.
     *
     * order_status allowed values : pending | processing | shipped | delivered | cancelled
     * payment_status allowed values: pending | paid | failed
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Fulfillment lifecycle column — admin-managed
            $table->string('order_status', 20)->default('pending')->after('status');

            // Payment lifecycle column — webhook-managed (Stripe) or admin-managed (COD)
            $table->string('payment_status', 20)->default('pending')->after('order_status');

            // Deprecate the legacy status column; keep it nullable so existing rows survive
            $table->string('status', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the two new columns and restores `status` to non-nullable.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_status', 'payment_status']);

            // Restore status to non-nullable with its original default
            $table->string('status', 20)->default('pending')->nullable(false)->change();
        });
    }
};
