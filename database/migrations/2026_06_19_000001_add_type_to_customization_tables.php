<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Steps — add only if missing
        Schema::table('product_customization_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('product_customization_steps', 'type')) {
                $table->enum('type', ['dropdown', 'box', 'color'])->default('dropdown')->after('name');
            }
            if (!Schema::hasColumn('product_customization_steps', 'color_value')) {
                $table->string('color_value', 20)->nullable()->after('type');
            }
        });

        // Options
        Schema::table('product_customization_options', function (Blueprint $table) {
            if (!Schema::hasColumn('product_customization_options', 'type')) {
                $table->enum('type', ['dropdown', 'box', 'color'])->default('dropdown')->after('name');
            }
            if (!Schema::hasColumn('product_customization_options', 'color_value')) {
                $table->string('color_value', 20)->nullable()->after('type');
            }
        });

        // Sub-steps
        Schema::table('product_customization_substeps', function (Blueprint $table) {
            if (!Schema::hasColumn('product_customization_substeps', 'type')) {
                $table->enum('type', ['dropdown', 'box', 'color'])->default('dropdown')->after('name');
            }
            if (!Schema::hasColumn('product_customization_substeps', 'color_value')) {
                $table->string('color_value', 20)->nullable()->after('type');
            }
        });

        // Sub-options
        Schema::table('product_customization_suboptions', function (Blueprint $table) {
            if (!Schema::hasColumn('product_customization_suboptions', 'type')) {
                $table->enum('type', ['dropdown', 'box', 'color'])->default('dropdown')->after('name');
            }
            if (!Schema::hasColumn('product_customization_suboptions', 'color_value')) {
                $table->string('color_value', 20)->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_customization_steps', function (Blueprint $table) {
            $table->dropColumn(array_filter(['type', 'color_value'], fn ($c) => Schema::hasColumn('product_customization_steps', $c)));
        });

        Schema::table('product_customization_options', function (Blueprint $table) {
            $table->dropColumn(array_filter(['type', 'color_value'], fn ($c) => Schema::hasColumn('product_customization_options', $c)));
        });

        Schema::table('product_customization_substeps', function (Blueprint $table) {
            $table->dropColumn(array_filter(['type', 'color_value'], fn ($c) => Schema::hasColumn('product_customization_substeps', $c)));
        });

        Schema::table('product_customization_suboptions', function (Blueprint $table) {
            $table->dropColumn(array_filter(['type', 'color_value'], fn ($c) => Schema::hasColumn('product_customization_suboptions', $c)));
        });
    }
};
