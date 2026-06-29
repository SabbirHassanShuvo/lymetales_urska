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
        $tables = [
            'products',
            'categories',
            'site_categories',
            'coupons',
            'offers',
            'pages',
            'hero_sections',
            'home_features',
            'home_promos',
            'gift_givers',
            'faqs',
            'footer_sections',
            'footer_items',
            'gift_cards',
            'gifts'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->string('language_type')->default('SL');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'categories',
            'site_categories',
            'coupons',
            'offers',
            'pages',
            'hero_sections',
            'home_features',
            'home_promos',
            'gift_givers',
            'faqs',
            'footer_sections',
            'footer_items',
            'gift_cards',
            'gifts'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    if (Schema::hasColumn($table, 'language_type')) {
                        $tableBlueprint->dropColumn('language_type');
                    }
                });
            }
        }
    }
};
