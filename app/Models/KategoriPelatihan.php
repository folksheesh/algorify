<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelatihan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelatihan';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi'
    ];

    /**
     * Get the bank soal for the kategori
     */
    public function bankSoal()
    {
        return $this->hasMany(BankSoal::class, 'kategori_id');
    }

    /**
     * Get the kursus for the kategori
     */
    public function kursus()
    {
        return $this->hasMany(Kursus::class, 'kategori_id');
    }
}
