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
        Schema::table('soal', function (Blueprint $table) {
            $table->enum('tipe_soal', ['single', 'multiple'])->default('single')->after('pertanyaan')->comment('single: pilih satu jawaban, multiple: pilih banyak jawaban');
            $table->string('lampiran_foto')->nullable()->after('tipe_soal')->comment('Path ke foto lampiran soal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn(['tipe_soal', 'lampiran_foto']);
        });
    }
};
