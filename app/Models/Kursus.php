<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;

    protected $table = 'kursus';

    protected $fillable = [
        'judul',
        'deskripsi',
        'deskripsi_singkat',
        'kategori',
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'harga',
        'thumbnail',
        'pengajar',
        'durasi',
        'tipe_kursus',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'harga' => 'decimal:2',
    ];

    // Relationships
    public function pengajar()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias untuk pengajar (untuk konsistensi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function modul()
    {
        return $this->hasMany(Modul::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class);
    }

    // Helper method untuk mendapatkan peserta
    public function peserta()
    {
        return $this->belongsToMany(User::class, 'enrollment')
            ->withPivot('kode', 'tanggal_daftar', 'status', 'progress', 'nilai_akhir')
            ->withTimestamps();
    }
}
