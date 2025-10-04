<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE product_variations MODIFY price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE product_variations MODIFY regular_price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE product_variations MODIFY sale_price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE product_variations MODIFY stock_quantity DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE product_variations MODIFY weight DECIMAL(15,4) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE product_variations MODIFY price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE product_variations MODIFY regular_price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE product_variations MODIFY sale_price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE product_variations MODIFY stock_quantity DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE product_variations MODIFY weight DECIMAL(15,4) NULL');
    }
};
