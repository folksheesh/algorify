<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'ujian_id',
        'pertanyaan',
        'kunci_jawaban',
        'kursus_id',
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class);
    }
}
