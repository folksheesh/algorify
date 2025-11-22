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
            $table->text('keahlian')->nullable()->after('pendidikan');
            $table->text('pengalaman')->nullable()->after('keahlian');
            $table->string('sertifikasi')->nullable()->after('pengalaman');
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
