<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soal';

    protected $fillable = [
        'pertanyaan',
        'tipe_soal',
        'opsi_jawaban',
        'jawaban_benar',
        'kategori_id',
        'kursus_id',
        'poin',
        'lampiran',
        'created_by'
    ];

    protected $casts = [
        'opsi_jawaban' => 'array',
        'jawaban_benar' => 'array'
    ];

    /**
     * Get the kategori pelatihan that owns the soal
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriPelatihan::class, 'kategori_id');
    }

    /**
     * Get the kursus that owns the soal
     */
    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'kursus_id');
    }

    /**
     * Get the user who created the soal
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the pilihan for the bank soal
     */
    public function pilihan()
    {
        return $this->hasMany(BankSoalPilihan::class, 'bank_soal_id');
    }

    /**
     * Get the kategori pelatihan that owns the soal
     */
    public function kategoriPelatihan()
    {
        return $this->belongsTo(KategoriPelatihan::class, 'kategori_id');
    }
}
