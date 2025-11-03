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
        'deskripsi',
        'urutan',
    ];

    // Relationships
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }
}
