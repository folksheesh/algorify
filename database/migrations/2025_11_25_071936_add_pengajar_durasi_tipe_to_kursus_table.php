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
        Schema::table('kursus', function (Blueprint $table) {
            $table->string('pengajar')->nullable()->after('user_id');
            $table->string('durasi', 100)->nullable()->after('pengajar');
            $table->enum('tipe_kursus', ['online', 'hybrid', 'offline'])->default('online')->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursus', function (Blueprint $table) {
            $table->dropColumn(['pengajar', 'durasi', 'tipe_kursus']);
        });
    }
};
