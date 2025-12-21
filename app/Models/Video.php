<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';

    protected $fillable = [
        'modul_id',
        'judul',
        'slug',
        'deskripsi',
        'file_video',
        'urutan',
    ];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    protected static function booted()
    {
        static::saving(function (self $video) {
            if (! $video->slug || $video->isDirty('judul')) {
                $video->slug = self::generateUniqueSlug($video->judul, $video->id);
            }
        });
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'video';
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
