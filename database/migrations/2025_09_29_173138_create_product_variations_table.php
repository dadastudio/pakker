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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedBigInteger('product_woocommerce_id');
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->text('permalink')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->nullable();
            $table->integer('menu_order')->default(0);
            $table->string('sku')->nullable();
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('regular_price', 15, 4)->nullable();
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->dateTimeTz('date_on_sale_from')->nullable();
            $table->dateTimeTz('date_on_sale_from_gmt')->nullable();
            $table->dateTimeTz('date_on_sale_to')->nullable();
            $table->dateTimeTz('date_on_sale_to_gmt')->nullable();
            $table->boolean('on_sale')->default(false);
            $table->boolean('purchasable')->default(false);
            $table->boolean('virtual')->default(false);
            $table->boolean('downloadable')->default(false);
            $table->integer('download_limit')->nullable();
            $table->integer('download_expiry')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->boolean('manage_stock')->default(false);
            $table->decimal('stock_quantity', 15, 4)->nullable();
            $table->string('stock_status')->nullable();
            $table->string('backorders')->nullable();
            $table->boolean('backordered')->default(false);
            $table->boolean('visible')->default(true);
            $table->boolean('shipping_required')->default(true);
            $table->boolean('shipping_taxable')->default(true);
            $table->decimal('weight', 15, 4)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('shipping_class')->nullable();
            $table->unsignedBigInteger('shipping_class_id')->nullable();
            $table->text('price_html')->nullable();
            $table->json('image')->nullable();
            $table->json('links')->nullable();
            $table->json('meta_data_snapshot')->nullable();
            $table->dateTimeTz('date_created')->nullable();
            $table->dateTimeTz('date_created_gmt')->nullable();
            $table->dateTimeTz('date_modified')->nullable();
            $table->dateTimeTz('date_modified_gmt')->nullable();
            $table->timestamps();

            $table->index('product_woocommerce_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
