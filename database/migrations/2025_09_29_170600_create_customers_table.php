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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('woocommerce_id')->unique();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('role')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->json('billing')->nullable();
            $table->json('shipping')->nullable();
            $table->boolean('is_paying_customer')->default(false);
            $table->string('avatar_url')->nullable();
            $table->unsignedBigInteger('last_order_id')->nullable();
            $table->string('last_order_number')->nullable();
            $table->dateTimeTz('last_order_date')->nullable();
            $table->dateTimeTz('last_order_date_gmt')->nullable();
            $table->unsignedInteger('orders_count')->default(0);
            $table->decimal('total_spent', 15, 4)->default(0);
            $table->dateTimeTz('date_created')->nullable();
            $table->dateTimeTz('date_created_gmt')->nullable();
            $table->dateTimeTz('date_modified')->nullable();
            $table->dateTimeTz('date_modified_gmt')->nullable();
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
