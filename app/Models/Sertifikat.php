<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'user_id',
        'kursus_id',
        'nomor_sertifikat',
        'judul',
        'deskripsi',
        'tanggal_terbit',
        'status_sertifikat',
        'file_path',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    // Auto generate nomor sertifikat saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sertifikat) {
            if (empty($sertifikat->nomor_sertifikat)) {
                $sertifikat->nomor_sertifikat = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(10));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }
}
