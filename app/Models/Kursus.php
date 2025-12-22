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
        'slug',
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
        'kapasitas',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'harga' => 'decimal:2',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Alias untuk instructor (backwards compatibility)
    public function pengajarRelation()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias untuk pengajar (untuk konsistensi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Accessor untuk nama pengajar
    public function getPengajarNameAttribute()
    {
        // Try to get from user relation first
        if ($this->user_id && $this->instructor) {
            return $this->instructor->name;
        }
        // Fallback to pengajar string field
        return $this->pengajar ?: '-';
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
    
    // Accessor untuk nama (alias dari judul)
    public function getNamaAttribute()
    {
        return $this->judul;
    }

    protected static function booted()
    {
        static::saving(function (self $kursus) {
            if (! $kursus->slug || $kursus->isDirty('judul')) {
                $kursus->slug = self::generateUniqueSlug($kursus->judul, $kursus->id);
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'kursus';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            self::where('slug', $slug)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
