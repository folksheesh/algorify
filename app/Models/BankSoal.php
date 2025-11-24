<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table = 'bank_soal';
    
    protected $fillable = [
        'kategori_id',
        'pertanyaan',
        'tingkat_kesulitan',
        'kunci_jawaban',
        'created_by'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSoal::class, 'kategori_id');
    }

    public function pilihan()
    {
        return $this->hasMany(BankSoalPilihan::class, 'bank_soal_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
