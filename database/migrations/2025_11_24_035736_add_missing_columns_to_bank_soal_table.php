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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('bank_soal', 'tipe_soal')) {
                $table->enum('tipe_soal', ['pilihan_ganda', 'multi_jawaban', 'essay'])->after('pertanyaan');
            }
            if (!Schema::hasColumn('bank_soal', 'opsi_jawaban')) {
                $table->json('opsi_jawaban')->nullable()->after('tipe_soal');
            }
            if (!Schema::hasColumn('bank_soal', 'jawaban_benar')) {
                $table->json('jawaban_benar')->nullable()->after('opsi_jawaban');
            }
            if (!Schema::hasColumn('bank_soal', 'kategori_id')) {
                $table->foreignId('kategori_id')->nullable()->after('jawaban_benar')->constrained('kategori_pelatihan')->onDelete('set null');
            }
            if (!Schema::hasColumn('bank_soal', 'kursus_id')) {
                $table->foreignId('kursus_id')->nullable()->after('kategori_id')->constrained('kursus')->onDelete('set null');
            }
            if (!Schema::hasColumn('bank_soal', 'lampiran')) {
                $table->string('lampiran')->nullable()->after('kursus_id');
            }
            if (!Schema::hasColumn('bank_soal', 'created_by')) {
                $table->foreignId('created_by')->after('lampiran')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            if (Schema::hasColumn('bank_soal', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('bank_soal', 'lampiran')) {
                $table->dropColumn('lampiran');
            }
            if (Schema::hasColumn('bank_soal', 'kursus_id')) {
                $table->dropForeign(['kursus_id']);
                $table->dropColumn('kursus_id');
            }
            if (Schema::hasColumn('bank_soal', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
            if (Schema::hasColumn('bank_soal', 'jawaban_benar')) {
                $table->dropColumn('jawaban_benar');
            }
            if (Schema::hasColumn('bank_soal', 'opsi_jawaban')) {
                $table->dropColumn('opsi_jawaban');
            }
            if (Schema::hasColumn('bank_soal', 'tipe_soal')) {
                $table->dropColumn('tipe_soal');
            }
        });
    }
};
