<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollment';

    protected $fillable = [
        'user_id',
        'kursus_id',
        'kode',
        'tanggal_daftar',
        'status',
        'progress',
        'nilai_akhir',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'nilai_akhir' => 'decimal:2',
    ];

    // Auto generate kode saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($enrollment) {
            if (empty($enrollment->kode)) {
                $enrollment->kode = 'ENR-' . strtoupper(Str::random(8));
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

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
