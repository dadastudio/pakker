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
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('permalink')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('catalog_visibility')->nullable();
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('regular_price', 15, 4)->nullable();
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->dateTimeTz('date_on_sale_from')->nullable();
            $table->dateTimeTz('date_on_sale_from_gmt')->nullable();
            $table->dateTimeTz('date_on_sale_to')->nullable();
            $table->dateTimeTz('date_on_sale_to_gmt')->nullable();
            $table->boolean('on_sale')->default(false);
            $table->unsignedInteger('total_sales')->default(0);
            $table->boolean('virtual')->default(false);
            $table->boolean('downloadable')->default(false);
            $table->integer('download_limit')->nullable();
            $table->integer('download_expiry')->nullable();
            $table->text('external_url')->nullable();
            $table->string('button_text')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->boolean('manage_stock')->default(false);
            $table->decimal('stock_quantity', 15, 4)->nullable();
            $table->string('stock_status')->nullable();
            $table->string('backorders')->nullable();
            $table->boolean('backorders_allowed')->default(false);
            $table->boolean('backordered')->default(false);
            $table->decimal('low_stock_amount', 15, 4)->nullable();
            $table->boolean('sold_individually')->default(false);
            $table->decimal('weight', 15, 4)->nullable();
            $table->json('dimensions')->nullable();
            $table->boolean('shipping_required')->default(true);
            $table->boolean('shipping_taxable')->default(true);
            $table->string('shipping_class')->nullable();
            $table->unsignedBigInteger('shipping_class_id')->nullable();
            $table->boolean('reviews_allowed')->default(true);
            $table->decimal('average_rating', 8, 4)->nullable();
            $table->unsignedInteger('rating_count')->default(0);
            $table->json('related_ids')->nullable();
            $table->json('upsell_ids')->nullable();
            $table->json('cross_sell_ids')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('purchase_note')->nullable();
            $table->json('variations')->nullable();
            $table->json('grouped_products')->nullable();
            $table->integer('menu_order')->default(0);
            $table->text('price_html')->nullable();
            $table->boolean('has_options')->default(false);
            $table->json('default_attributes_snapshot')->nullable();
            $table->json('meta_data_snapshot')->nullable();
            $table->json('links')->nullable();
            $table->dateTimeTz('date_created')->nullable();
            $table->dateTimeTz('date_created_gmt')->nullable();
            $table->dateTimeTz('date_modified')->nullable();
            $table->dateTimeTz('date_modified_gmt')->nullable();
            $table->timestamps();
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
