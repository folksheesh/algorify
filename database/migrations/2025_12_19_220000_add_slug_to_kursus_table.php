<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom slug ke tabel kursus untuk URL yang lebih bersih
     */
    public function up(): void
    {
        // Tambah kolom slug jika belum ada
        if (! Schema::hasColumn('kursus', 'slug')) {
            Schema::table('kursus', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('judul');
            });
        }

        // Generate slug untuk kursus yang sudah ada
        $kursusList = DB::table('kursus')->get();
        foreach ($kursusList as $kursus) {
            // Lewati jika slug sudah terisi
            if (! empty($kursus->slug)) {
                continue;
            }

            $baseSlug = Str::slug($kursus->judul);
            $slug = $baseSlug;
            $counter = 1;
            
            // Pastikan slug unik
            while (DB::table('kursus')->where('slug', $slug)->where('id', '!=', $kursus->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            DB::table('kursus')->where('id', $kursus->id)->update(['slug' => $slug]);
        }

        // Tambah index unik jika belum ada
        $indexExists = DB::select("SHOW INDEX FROM `kursus` WHERE Key_name = ?", ['kursus_slug_unique']);
        if (empty($indexExists)) {
            Schema::table('kursus', function (Blueprint $table) {
                $table->unique('slug', 'kursus_slug_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus index unik jika ada
        $indexExists = DB::select("SHOW INDEX FROM `kursus` WHERE Key_name = ?", ['kursus_slug_unique']);
        if (! empty($indexExists)) {
            Schema::table('kursus', function (Blueprint $table) {
                $table->dropUnique('kursus_slug_unique');
            });
        }

        // Hapus kolom jika ada
        if (Schema::hasColumn('kursus', 'slug')) {
            Schema::table('kursus', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
