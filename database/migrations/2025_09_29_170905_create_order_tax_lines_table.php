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
        Schema::create('order_tax_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->string('rate_code')->nullable();
            $table->unsignedBigInteger('rate_id')->nullable();
            $table->string('label')->nullable();
            $table->boolean('compound')->default(false);
            $table->decimal('tax_total', 15, 4)->default(0);
            $table->decimal('shipping_tax_total', 15, 4)->default(0);
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tax_lines');
    }
};
