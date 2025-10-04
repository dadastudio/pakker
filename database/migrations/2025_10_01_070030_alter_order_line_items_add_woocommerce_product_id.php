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
        Schema::table('order_line_items', function (Blueprint $table) {
            // Rename existing columns to preserve WooCommerce IDs
            $table->renameColumn('product_id', 'woocommerce_product_id');
            $table->renameColumn('variation_id', 'woocommerce_variation_id');
        });

        Schema::table('order_line_items', function (Blueprint $table) {
            // Add new columns for internal product references (after rename)
            $table->foreignId('product_id')->nullable()->after('name')->constrained('products')->nullOnDelete();
            $table->foreignId('variation_id')->nullable()->after('product_id')->constrained('product_variations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_line_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['variation_id']);
            $table->dropColumn(['product_id', 'variation_id']);
        });

        Schema::table('order_line_items', function (Blueprint $table) {
            $table->renameColumn('woocommerce_product_id', 'product_id');
            $table->renameColumn('woocommerce_variation_id', 'variation_id');
        });
    }
};
