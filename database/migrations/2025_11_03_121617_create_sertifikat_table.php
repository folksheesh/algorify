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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kursus_id')->constrained('kursus')->onDelete('cascade');
            $table->string('nomor_sertifikat')->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->timestamp('tanggal_terbit')->useCurrent();
            $table->enum('status_sertifikat', ['active', 'revoked', 'expired'])->default('active');
            $table->string('file_path')->nullable(); // Path untuk file PDF sertifikat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
