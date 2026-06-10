<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('site_category_id')->nullable()->after('featured_image_id');
            $table->unsignedBigInteger('site_subcategory_id')->nullable()->after('site_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['site_category_id', 'site_subcategory_id']);
        });
    }
};
