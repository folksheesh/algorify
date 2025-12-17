<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk tracking progress user pada setiap item pembelajaran
     * (video, materi bacaan, quiz, ujian)
     */
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 10)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('kursus_id')->constrained('kursus')->onDelete('cascade');
            
            // Polymorphic relationship untuk berbagai tipe item
            $table->string('item_type'); // 'video', 'materi', 'quiz', 'ujian'
            $table->unsignedBigInteger('item_id');
            
            // Status tracking
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            
            // Untuk video: tracking waktu nonton
            $table->integer('watch_time')->nullable(); // dalam detik
            $table->integer('total_duration')->nullable(); // durasi total video dalam detik
            
            // Untuk quiz/ujian: menyimpan skor
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            
            // Waktu completion
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Unique constraint: satu user hanya bisa punya satu progress per item
            $table->unique(['user_id', 'item_type', 'item_id'], 'user_item_progress_unique');
            
            // Index untuk query cepat
            $table->index(['user_id', 'kursus_id']);
            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
