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
        Schema::create('bank_soal_pilihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->onDelete('cascade');
            $table->text('pilihan');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal_pilihan');
    }
};
