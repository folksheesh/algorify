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
        'slug',
        'deskripsi',
        'waktu_pengerjaan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'tipe',
        'minimum_score',
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

    protected static function booted()
    {
        static::saving(function (self $ujian) {
            if (! $ujian->slug || $ujian->isDirty('judul')) {
                $ujian->slug = self::generateUniqueSlug($ujian->judul, $ujian->id);
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'ujian';
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
