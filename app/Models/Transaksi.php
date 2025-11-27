<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'enrollment_id',
        'user_id',
        'kursus_id',
        'tanggal_transaksi',
        'nominal_pembayaran',
        'jumlah',
        'status',
        'bukti_pembayaran',
        'metode_pembayaran',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'nominal_pembayaran' => 'decimal:2',
        'jumlah' => 'decimal:2',
    ];

    // Relationships
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }
}