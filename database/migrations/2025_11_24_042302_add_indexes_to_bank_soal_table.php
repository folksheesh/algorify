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
            // Single column indexes untuk filter yang sering digunakan
            $table->index('kategori_id', 'idx_bank_soal_kategori');
            $table->index('kursus_id', 'idx_bank_soal_kursus');
            $table->index('created_by', 'idx_bank_soal_creator');
            $table->index('tipe_soal', 'idx_bank_soal_tipe');
            
            // Composite index untuk filter gabungan yang umum
            $table->index(['kategori_id', 'tipe_soal'], 'idx_bank_soal_kategori_tipe');
            $table->index(['created_at'], 'idx_bank_soal_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropIndex('idx_bank_soal_kategori');
            $table->dropIndex('idx_bank_soal_kursus');
            $table->dropIndex('idx_bank_soal_creator');
            $table->dropIndex('idx_bank_soal_tipe');
            $table->dropIndex('idx_bank_soal_kategori_tipe');
            $table->dropIndex('idx_bank_soal_created_at');
        });
    }
};
