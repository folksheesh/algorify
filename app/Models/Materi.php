<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'kursus_id',
        'tipe',
        'judul',
        'file_path',
    ];

    // Relationships
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }
}
