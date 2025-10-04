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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('number')->nullable();
            $table->string('order_key')->nullable();
            $table->string('created_via')->nullable();
            $table->string('version')->nullable();
            $table->string('status')->nullable();
            $table->string('currency', 10)->nullable();
            $table->dateTimeTz('date_created')->nullable();
            $table->dateTimeTz('date_created_gmt')->nullable();
            $table->dateTimeTz('date_modified')->nullable();
            $table->dateTimeTz('date_modified_gmt')->nullable();
            $table->decimal('discount_total', 15, 4)->default(0);
            $table->decimal('discount_tax', 15, 4)->default(0);
            $table->decimal('shipping_total', 15, 4)->default(0);
            $table->decimal('shipping_tax', 15, 4)->default(0);
            $table->decimal('cart_tax', 15, 4)->default(0);
            $table->decimal('total', 15, 4)->default(0);
            $table->decimal('total_tax', 15, 4)->default(0);
            $table->boolean('prices_include_tax')->default(false);
            $table->unsignedBigInteger('customer_woocommerce_id')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_ip_address')->nullable();
            $table->string('customer_user_agent')->nullable();
            $table->text('customer_note')->nullable();
            $table->json('billing')->nullable();
            $table->json('shipping')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_title')->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTimeTz('date_paid')->nullable();
            $table->dateTimeTz('date_paid_gmt')->nullable();
            $table->dateTimeTz('date_completed')->nullable();
            $table->dateTimeTz('date_completed_gmt')->nullable();
            $table->string('cart_hash')->nullable();
            $table->boolean('set_paid')->default(false);
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
