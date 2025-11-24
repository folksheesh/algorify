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
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->enum('tipe_soal', ['pilihan_ganda', 'multi_jawaban', 'essay']);
            $table->json('opsi_jawaban')->nullable();
            $table->json('jawaban_benar')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_pelatihan')->onDelete('set null');
            $table->foreignId('kursus_id')->nullable()->constrained('kursus')->onDelete('set null');
            $table->string('lampiran')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};
