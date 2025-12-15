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
        Schema::create('enrollment', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 10)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('kursus_id')->constrained('kursus')->onDelete('cascade');
            $table->string('kode')->unique(); // Kode unik enrollment
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->enum('status', ['active', 'completed', 'dropped', 'expired'])->default('active');
            $table->integer('progress')->default(0); // Progress dalam persen (0-100)
            $table->decimal('nilai_akhir', 5, 2)->nullable(); // Nilai akhir kursus
            $table->timestamps();
            
            // Unique constraint: satu user hanya bisa enroll satu kali per kursus
            $table->unique(['user_id', 'kursus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment');
    }
};
