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
        // Add subcategory_id and remove old single special section columns if they exist
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'special_section_title')) {
                $table->dropColumn([
                    'special_section_title',
                    'special_section_subtitle',
                    'special_section_desc',
                    'special_section_image'
                ]);
            }
            $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id')->index();
            $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('set null');
        });

        // Create new table for multiple special sections
        Schema::create('product_special_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('subtitle')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_special_sections');

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_id');
            
            $table->string('special_section_title')->nullable()->after('status');
            $table->string('special_section_subtitle')->nullable()->after('special_section_title');
            $table->text('special_section_desc')->nullable()->after('special_section_subtitle');
            $table->string('special_section_image')->nullable()->after('special_section_desc');
        });
    }
};
