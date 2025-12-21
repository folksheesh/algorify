<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $table = 'modul';

    protected $fillable = [
        'kursus_id',
        'judul',
        'slug',
        'deskripsi',
        'urutan',
    ];

    // Relationships
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class)->orderBy('urutan');
    }

    public function video()
    {
        return $this->hasMany(Video::class)->orderBy('urutan');
    }

    protected static function booted()
    {
        static::saving(function (self $modul) {
            if (! $modul->slug || $modul->isDirty('judul')) {
                $modul->slug = self::generateUniqueSlug($modul->judul, $modul->id);
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'modul';
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
