<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE product_images MODIFY position INT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE product_images MODIFY position INT UNSIGNED NOT NULL DEFAULT 0');
    }
};
