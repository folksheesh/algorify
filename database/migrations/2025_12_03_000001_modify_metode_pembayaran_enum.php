<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ubah enum metode_pembayaran:
     * - Hapus: virtual_account
     * - Tambah: mini_market, kartu_debit
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        // MySQL requires ALTER TABLE to change enum values
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN metode_pembayaran ENUM('bank_transfer', 'e_wallet', 'credit_card', 'qris', 'mini_market', 'kartu_debit') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN metode_pembayaran ENUM('bank_transfer', 'e_wallet', 'credit_card', 'qris', 'virtual_account') NULL");
    }
};
