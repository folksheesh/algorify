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
        // Tambah kolom slug
        Schema::table('kursus', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('judul');
        });

        // Generate slug untuk kursus yang sudah ada
        $kursusList = DB::table('kursus')->get();
        foreach ($kursusList as $kursus) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursus', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
