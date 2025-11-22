<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'kursus_id',
        'modul_id',
        'judul',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'tipe',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relationships
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
