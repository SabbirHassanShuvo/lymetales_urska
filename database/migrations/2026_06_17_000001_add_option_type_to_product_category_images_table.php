<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_category_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_category_images', 'option_type')) {
                $table->string('option_type')->default('box')->after('sort_order');
            }
            if (!Schema::hasColumn('product_category_images', 'option_value')) {
                $table->string('option_value')->nullable()->after('option_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_category_images', function (Blueprint $table) {
            $table->dropColumn(['option_type', 'option_value']);
        });
    }
};
