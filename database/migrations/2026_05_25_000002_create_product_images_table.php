<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // product_images table may already exist — only create if missing
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->index();
                $table->string('path');
                $table->boolean('is_primary')->default(false);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            });
        }

        // Drop old image/gallery columns from products if they still exist
        Schema::table('products', function (Blueprint $table) {
            $cols = Schema::getColumnListing('products');
            $toDrop = array_intersect(['image', 'gallery'], $cols);
            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('reviews_count');
            }
            if (!Schema::hasColumn('products', 'gallery')) {
                $table->text('gallery')->nullable()->after('image');
            }
        });
    }
};
