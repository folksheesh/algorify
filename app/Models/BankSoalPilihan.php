<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoalPilihan extends Model
{
    protected $table = 'bank_soal_pilihan';
    
    protected $fillable = [
        'bank_soal_id',
        'pilihan',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class, 'bank_soal_id');
    }
}
