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
        Schema::create('order_shipping_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->string('method_title')->nullable();
            $table->string('method_id')->nullable();
            $table->unsignedBigInteger('instance_id')->nullable();
            $table->decimal('total', 15, 4)->default(0);
            $table->decimal('total_tax', 15, 4)->default(0);
            $table->json('taxes')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipping_lines');
    }
};
