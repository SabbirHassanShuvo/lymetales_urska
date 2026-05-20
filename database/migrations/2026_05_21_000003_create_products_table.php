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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('pages')->nullable();
            $table->string('age_range')->nullable();
            $table->string('size')->nullable();
            $table->string('characters')->nullable();
            $table->string('cover_type')->nullable();
            $table->string('print_type')->nullable();
            $table->string('paper_type')->nullable();
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('reviews_count')->default(0);
            $table->string('image')->nullable(); // Main image
            $table->text('gallery')->nullable(); // JSON array of thumbnails
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
