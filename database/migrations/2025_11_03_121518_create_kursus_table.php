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
        Schema::create('kursus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->enum('kategori', ['programming', 'design', 'business', 'marketing', 'data_science', 'other'])->default('other');
            $table->string('user_id', 10)->nullable(); // Pengajar/Instructor
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->decimal('harga', 10, 2)->default(0);
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            // Foreign key constraint for user_id (string) to users.id (string)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursus');
    }
};
