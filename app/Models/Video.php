<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';

    protected $fillable = [
        'modul_id',
        'judul',
        'deskripsi',
        'file_video',
        'urutan',
    ];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }
}
