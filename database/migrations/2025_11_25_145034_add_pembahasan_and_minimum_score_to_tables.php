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
        // Add pembahasan column to soal table
        Schema::table('soal', function (Blueprint $table) {
            $table->text('pembahasan')->nullable()->after('kunci_jawaban');
        });

        // Add minimum_score column to ujian table
        Schema::table('ujian', function (Blueprint $table) {
            $table->integer('minimum_score')->default(70)->after('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('pembahasan');
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('minimum_score');
        });
    }
};
