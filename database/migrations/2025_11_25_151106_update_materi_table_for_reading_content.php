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
        Schema::table('materi', function (Blueprint $table) {
            // Rename file_pdf to file_path (akan digunakan untuk gambar dalam konten)
            $table->renameColumn('file_pdf', 'file_path');
            
            // Ubah file_path menjadi nullable karena sekarang optional
            $table->string('file_path')->nullable()->change();
            
            // Tambah kolom untuk konten HTML
            $table->longText('konten')->nullable()->after('deskripsi');
            
            // Tambah kolom untuk featured image (opsional)
            $table->string('featured_image')->nullable()->after('konten');
            
            // Ubah deskripsi menjadi excerpt/ringkasan
            $table->text('deskripsi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->renameColumn('file_path', 'file_pdf');
            $table->string('file_pdf')->nullable(false)->change();
            $table->dropColumn(['konten', 'featured_image']);
        });
    }
};
