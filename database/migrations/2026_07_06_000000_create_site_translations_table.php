<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_translations', function (Blueprint $table) {
            $table->id();
            $table->string('key');                          // dot-notation key e.g. global.error_loading
            $table->string('group')->default('general');   // e.g. global, home, books, contact
            $table->string('display_name')->nullable();    // Human-readable label for the admin panel
            $table->string('input_type')->default('text'); // text | textarea | json
            $table->longText('value')->nullable();          // string or JSON string
            $table->string('language_type')->default('SL'); // SL | EN | NL
            $table->timestamps();

            $table->unique(['key', 'language_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_translations');
    }
};
