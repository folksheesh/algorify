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
            $table->integer('kapasitas')->nullable()->after('harga')->comment('Jumlah maksimal peserta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursus', function (Blueprint $table) {
            $table->dropColumn('kapasitas');
        });
    }
};
