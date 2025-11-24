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
            $table->foreignId('kategori_id')->constrained('kategori_soal')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('tingkat_kesulitan')->default('sedang'); // mudah, sedang, sulit
            $table->string('kunci_jawaban');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
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
