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
        // 1. Home Highlight Features (Image 2)
        Schema::create('home_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        // 2. Home Promo / Middle Section (Image 3)
        Schema::create('home_promos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('button_text');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // 3. Legendary Gift Giver Section (Image 4)
        Schema::create('gift_givers', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle')->default('BECOME A');
            $table->string('title')->default('Legendary gift-giver');
            $table->string('step_1_image')->nullable();
            $table->string('step_1_text')->default('Fill in a few bits of info');
            $table->string('step_2_image')->nullable();
            $table->string('step_2_text')->default('Preview personalisation in real time');
            $table->string('step_3_image')->nullable();
            $table->string('step_3_text')->default('Deliver smiles of joy to your favourite child');
            $table->timestamps();
        });

        // 4. Newsletter Subscribers (Requirement 7)
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });

        // 5. Footer Sections (Requirement 8)
        Schema::create('footer_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. Footer Items / Links (Requirement 8)
        Schema::create('footer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_section_id')->constrained('footer_sections')->onDelete('cascade');
            $table->string('label');
            $table->string('url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_items');
        Schema::dropIfExists('footer_sections');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('gift_givers');
        Schema::dropIfExists('home_promos');
        Schema::dropIfExists('home_features');
    }
};
