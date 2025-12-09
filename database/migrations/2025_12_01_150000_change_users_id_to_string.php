<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah users.id dari bigint auto-increment menjadi string dengan format role-based
     */
    public function up(): void
    {
        // Skip this migration on SQLite (testing) because ALTER/MODIFY not supported
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        // Step 1: Simpan mapping id lama ke kode_unik baru
        $users = DB::table('users')->get();
        $idMapping = [];
        
        foreach ($users as $user) {
            $idMapping[$user->id] = $user->kode_unik;
        }
        
        // Step 2: Drop semua foreign keys yang reference ke users.id
        // Berdasarkan hasil query FK yang ada di database:
        // - bank_soal_created_by_foreign
        // - enrollment_user_id_foreign
        // - jawaban_user_id_foreign
        // - kursus_user_id_foreign
        // - nilai_user_id_foreign
        // - sertifikat_user_id_foreign
        // - transaksi_user_id_foreign
        // - user_progress_user_id_foreign
        
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        
        Schema::table('kursus', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('jawaban', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('enrollment', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        // Step 3: Ubah kolom user_id/created_by di semua tabel menjadi string
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->string('created_by', 10)->nullable()->change();
        });
        
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('user_id', 10)->nullable()->change();
        });
        
        Schema::table('kursus', function (Blueprint $table) {
            $table->string('user_id', 10)->nullable()->change();
        });
        
        Schema::table('nilai', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        Schema::table('jawaban', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        Schema::table('enrollment', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        Schema::table('user_progress', function (Blueprint $table) {
            $table->string('user_id', 10)->change();
        });
        
        // Step 4: Update data user_id/created_by dengan kode_unik
        foreach ($idMapping as $oldId => $newId) {
            DB::table('bank_soal')->where('created_by', $oldId)->update(['created_by' => $newId]);
            DB::table('sessions')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('kursus')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('nilai')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('sertifikat')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('transaksi')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('jawaban')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('enrollment')->where('user_id', $oldId)->update(['user_id' => $newId]);
            DB::table('user_progress')->where('user_id', $oldId)->update(['user_id' => $newId]);
        }
        
        // Step 5: Ubah users.id menjadi string
        // MySQL: Buat kolom baru untuk id string
        Schema::table('users', function (Blueprint $table) {
            $table->string('new_id', 10)->after('id')->nullable();
        });
        
        // Copy kode_unik ke new_id
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['new_id' => $user->kode_unik]);
        });
        
        // MySQL: Hilangkan AUTO_INCREMENT dulu dengan mengubah kolom menjadi BIGINT biasa
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL');
        // Baru drop PRIMARY KEY
        DB::statement('ALTER TABLE users DROP PRIMARY KEY');
        // Drop kolom id dan kode_unik
        DB::statement('ALTER TABLE users DROP COLUMN id');
        DB::statement('ALTER TABLE users DROP COLUMN kode_unik');
        
        // Rename new_id ke id dan jadikan primary key
        DB::statement('ALTER TABLE users CHANGE new_id id VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE users ADD PRIMARY KEY (id)');
        
        // Step 6: Tambahkan kembali foreign keys
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::table('kursus', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('jawaban', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('enrollment', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::table('user_progress', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tidak didukung karena kompleksitas perubahan
        throw new \Exception('This migration cannot be rolled back. Please restore from backup.');
    }
};
