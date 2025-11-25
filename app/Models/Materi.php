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
}
