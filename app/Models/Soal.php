<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'kuis_id',
        'pertanyaan',
        'kunci_jawaban',
        'kursus_id',
    ];

    // Relationships
    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }
}
