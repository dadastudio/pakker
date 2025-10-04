<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products MODIFY price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY regular_price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY sale_price DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY low_stock_amount DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY weight DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY average_rating DECIMAL(8,4) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY regular_price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY sale_price DECIMAL(15,4) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY low_stock_amount DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY weight DECIMAL(15,4) NULL');
        DB::statement('ALTER TABLE products MODIFY average_rating DECIMAL(8,4) NULL');
    }
};
