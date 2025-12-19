<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `kursus` MODIFY `kategori` VARCHAR(255) NOT NULL DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `kursus` MODIFY `kategori` ENUM('programming', 'design', 'business', 'marketing', 'data_science', 'other') NOT NULL DEFAULT 'other'");
    }
};
