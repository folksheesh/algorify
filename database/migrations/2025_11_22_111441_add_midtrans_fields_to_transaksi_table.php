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
        Schema::table('transaksi', function (Blueprint $table) {
            // Add kursus_id column
            $table->foreignId('kursus_id')->nullable()->after('user_id')->constrained('kursus')->onDelete('cascade');
            
            // Add kode_transaksi for Midtrans order_id
            $table->string('kode_transaksi')->unique()->after('id');
            
            // Add jumlah (amount) column
            $table->decimal('jumlah', 10, 2)->default(0)->after('kursus_id');
            
            // Make enrollment_id nullable since we might not have enrollment yet
            $table->foreignId('enrollment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['kursus_id']);
            $table->dropColumn(['kursus_id', 'kode_transaksi', 'jumlah']);
        });
    }
};
