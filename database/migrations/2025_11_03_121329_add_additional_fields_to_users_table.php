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
            // Note: role sudah dihandle oleh Spatie Permission, jadi tidak perlu kolom role lagi
            $table->string('profesi')->nullable()->after('email');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('profesi');
            $table->string('foto_profil')->nullable()->after('jenis_kelamin');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('foto_profil');
            $table->date('tanggal_lahir')->nullable()->after('status');
            $table->timestamp('tanggal_daftar')->useCurrent()->after('tanggal_lahir');
            $table->timestamp('tanggal_login_terakhir')->nullable()->after('tanggal_daftar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profesi',
                'jenis_kelamin',
                'foto_profil',
                'status',
                'tanggal_lahir',
                'tanggal_daftar',
                'tanggal_login_terakhir'
            ]);
        });
    }
};
