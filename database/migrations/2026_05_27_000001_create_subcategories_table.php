<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates a dedicated `subcategories` table and migrates existing subcategory data.
     */
    public function up(): void
    {
        // 1. Create the subcategories table
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });

        // 2. Migrate existing subcategories from categories table (where parent_id is NOT NULL)
        $existing = DB::table('categories')->whereNotNull('parent_id')->get();
        foreach ($existing as $sub) {
            $newId = DB::table('subcategories')->insertGetId([
                'category_id' => $sub->parent_id,
                'name'        => $sub->name,
                'slug'        => $sub->slug,
                'description' => $sub->description,
                'status'      => $sub->status,
                'created_at'  => $sub->created_at,
                'updated_at'  => $sub->updated_at,
            ]);

            // Update products that pointed to the old category_id (sub) → now use subcategory_id
            DB::table('products')
                ->where('category_id', $sub->id)
                ->update([
                    'category_id'    => $sub->parent_id, // The parent category
                    'subcategory_id' => $newId,
                ]);
        }

        // 3. Remove the migrated subcategory rows from categories table
        DB::table('categories')->whereNotNull('parent_id')->delete();

        // 4. Drop the parent_id column from categories (no longer needed)
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        // 5. Update foreign key of subcategory_id in products table to point to subcategories table instead of categories
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add parent_id to categories
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->index()->after('id');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Restore subcategories back into categories table
        $subs = DB::table('subcategories')->get();
        foreach ($subs as $sub) {
            $oldId = DB::table('categories')->insertGetId([
                'parent_id'   => $sub->category_id,
                'name'        => $sub->name,
                'slug'        => $sub->slug . '_restored',
                'description' => $sub->description,
                'status'      => $sub->status,
                'created_at'  => $sub->created_at,
                'updated_at'  => $sub->updated_at,
            ]);

            DB::table('products')
                ->where('subcategory_id', $sub->id)
                ->update(['category_id' => $oldId]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('set null');
        });

        Schema::dropIfExists('subcategories');
    }
};
