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
        Schema::table('product_upsells', function (Blueprint $table) {
            // Drop old foreign key constraint referencing 'products'
            $table->dropForeign(['upsell_product_id']);

            // Re-add foreign key constraint pointing to 'gifts'
            $table->foreign('upsell_product_id')
                  ->references('id')
                  ->on('gifts')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_upsells', function (Blueprint $table) {
            // Drop foreign key referencing 'gifts'
            $table->dropForeign(['upsell_product_id']);

            // Re-add foreign key referencing 'products'
            $table->foreign('upsell_product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
};
