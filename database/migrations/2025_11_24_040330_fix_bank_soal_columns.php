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
            // Make kunci_jawaban nullable since we use jawaban_benar instead
            if (Schema::hasColumn('bank_soal', 'kunci_jawaban')) {
                $table->string('kunci_jawaban')->nullable()->change();
            }
            
            // Drop tingkat_kesulitan if exists (not used)
            if (Schema::hasColumn('bank_soal', 'tingkat_kesulitan')) {
                $table->dropColumn('tingkat_kesulitan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {
            // Restore kunci_jawaban to not null
            if (Schema::hasColumn('bank_soal', 'kunci_jawaban')) {
                $table->string('kunci_jawaban')->nullable(false)->change();
            }
            
            // Add back tingkat_kesulitan
            if (!Schema::hasColumn('bank_soal', 'tingkat_kesulitan')) {
                $table->string('tingkat_kesulitan')->default('sedang')->after('pertanyaan');
            }
        });
    }
};
