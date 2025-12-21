<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Boot the model - auto-generate slug from judul
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kursus) {
            if (empty($kursus->slug)) {
                $kursus->slug = static::generateUniqueSlug($kursus->judul);
            }
        });

        static::updating(function ($kursus) {
            // Update slug if judul changed and slug not manually set
            if ($kursus->isDirty('judul') && !$kursus->isDirty('slug')) {
                $kursus->slug = static::generateUniqueSlug($kursus->judul, $kursus->id);
            }
        });
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug(string $judul, $excludeId = null): string
    {
        $baseSlug = Str::slug($judul);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Get the route key for the model (use slug instead of id)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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

