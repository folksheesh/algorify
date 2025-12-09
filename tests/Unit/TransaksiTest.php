<?php

namespace Tests\Unit;

use App\Models\Transaksi;
use Carbon\Carbon;
use Tests\TestCase;

class TransaksiTest extends TestCase
{
    public function test_casts_dates_and_decimals(): void
    {
        $trx = new Transaksi([
            'tanggal_transaksi' => '2025-12-08 10:00:00',
            'tanggal_verifikasi' => '2025-12-09 11:00:00',
            'nominal_pembayaran' => '1234.567',
            'jumlah' => '2.5',
        ]);

        $this->assertInstanceOf(Carbon::class, $trx->tanggal_transaksi);
        $this->assertInstanceOf(Carbon::class, $trx->tanggal_verifikasi);

        $this->assertSame('1234.57', $trx->nominal_pembayaran);
        $this->assertSame('2.50', $trx->jumlah);
    }
}
