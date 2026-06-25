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
        Schema::create('product_upsells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('upsell_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Unique combination to prevent duplicate recommendations
            $table->unique(['product_id', 'upsell_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_upsells');
    }
};
