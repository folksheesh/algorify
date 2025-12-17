<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change enum to string first to allow any category slug
        Schema::table('kursus', function (Blueprint $table) {
            $table->string('kategori', 50)->default('other')->change();
        });
        
        // Then convert existing data_science values to data-science (with dash)
        DB::table('kursus')->where('kategori', 'data_science')->update(['kategori' => 'data-science']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back data-science to data_science
        DB::table('kursus')->where('kategori', 'data-science')->update(['kategori' => 'data_science']);
        
        // Note: Converting back to enum might fail if there are values not in the enum list
        // This is a simplified rollback
        Schema::table('kursus', function (Blueprint $table) {
            $table->enum('kategori', ['programming', 'design', 'business', 'marketing', 'data_science', 'other'])->default('other')->change();
        });
    }
};
