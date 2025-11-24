<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSoal extends Model
{
    protected $table = 'kategori_soal';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'warna'
    ];

    public function bankSoal()
    {
        return $this->hasMany(BankSoal::class, 'kategori_id');
    }
}
