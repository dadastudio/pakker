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
        Schema::create('order_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('tax_class')->nullable();
            $table->decimal('subtotal', 15, 4)->default(0);
            $table->decimal('subtotal_tax', 15, 4)->default(0);
            $table->decimal('total', 15, 4)->default(0);
            $table->decimal('total_tax', 15, 4)->default(0);
            $table->json('taxes')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 15, 4)->default(0);
            $table->string('parent_name')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_line_items');
    }
};
