<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';

    protected $fillable = [
        'soal_id',
        'user_id',
        'jawaban',
        'status',
    ];

    // Relationships
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
