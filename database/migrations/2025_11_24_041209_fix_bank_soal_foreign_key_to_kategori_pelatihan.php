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
        Schema::table('bank_soal', function (Blueprint $table) {
            // Drop old foreign key that references kategori_soal
            $table->dropForeign(['kategori_id']);
            
            // Add new foreign key that references kategori_pelatihan
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategori_pelatihan')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            // Drop the correct foreign key
            $table->dropForeign(['kategori_id']);
            
            // Restore old foreign key to kategori_soal
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategori_soal')
                  ->onDelete('cascade');
        });
    }
};
