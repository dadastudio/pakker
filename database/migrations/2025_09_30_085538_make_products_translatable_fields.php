<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add temporary columns
        Schema::table('products', function (Blueprint $table) {
            $table->json('name_new')->nullable();
            $table->json('description_new')->nullable();
            $table->json('short_description_new')->nullable();
        });

        // Migrate existing data to JSON format (assuming English as default)
        DB::table('products')->get()->each(function ($product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'name_new' => $product->name ? json_encode(['en' => $product->name]) : null,
                    'description_new' => $product->description ? json_encode(['en' => $product->description]) : null,
                    'short_description_new' => $product->short_description ? json_encode(['en' => $product->short_description]) : null,
                ]);
        });

        // Drop old columns and rename new ones
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'short_description']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_new', 'name');
            $table->renameColumn('description_new', 'description');
            $table->renameColumn('short_description_new', 'short_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add temporary text columns
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_old')->nullable();
            $table->text('description_old')->nullable();
            $table->text('short_description_old')->nullable();
        });

        // Extract English text from JSON
        DB::table('products')->get()->each(function ($product) {
            $name = json_decode($product->name, true);
            $description = json_decode($product->description, true);
            $shortDescription = json_decode($product->short_description, true);

            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'name_old' => $name['en'] ?? '',
                    'description_old' => $description['en'] ?? null,
                    'short_description_old' => $shortDescription['en'] ?? null,
                ]);
        });

        // Drop JSON columns and rename text columns
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'short_description']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_old', 'name');
            $table->renameColumn('description_old', 'description');
            $table->renameColumn('short_description_old', 'short_description');
        });
    }
};
