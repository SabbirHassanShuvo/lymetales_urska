<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'featured_image_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('featured_image_id')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'featured_image_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('featured_image_id');
            });
        }
    }
};
