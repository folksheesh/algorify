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
        Schema::table('users', function (Blueprint $table) {
            $table->text('keahlian')->nullable()->after('pendidikan'); // Keahlian/Skills
            $table->text('pengalaman')->nullable()->after('keahlian'); // Pengalaman mengajar
            $table->string('sertifikasi')->nullable()->after('pengalaman'); // File path sertifikasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['keahlian', 'pengalaman', 'sertifikasi']);
        });
    }
};
