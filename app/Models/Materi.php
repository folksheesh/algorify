<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'modul_id',
        'judul',
        'slug',
        'deskripsi',
        'konten',
        'featured_image',
        'file_path',
        'urutan',
    ];

    // Relationships
    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    protected static function booted()
    {
        static::saving(function (self $materi) {
            if (! $materi->slug || $materi->isDirty('judul')) {
                $materi->slug = self::generateUniqueSlug($materi->judul, $materi->id);
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'materi';
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
