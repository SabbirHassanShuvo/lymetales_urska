<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_text')->nullable()->after('description');
            $table->string('name_font_family')->nullable()->default('PetitCochon')->after('name_text');
            $table->string('name_top')->nullable()->default('2%')->after('name_font_family');
            $table->string('name_color')->nullable()->default('#e591ae')->after('name_top');
            $table->string('name_font_size')->nullable()->default('88px')->after('name_color');
            $table->string('name_right')->nullable()->default('50%')->after('name_font_size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_text',
                'name_font_family',
                'name_top',
                'name_color',
                'name_font_size',
                'name_right',
            ]);
        });
    }
};
