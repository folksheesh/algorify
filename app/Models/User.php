<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profesi',
        'pendidikan',
        'address',
        'jenis_kelamin',
        'foto_profil',
        'status',
        'tanggal_lahir',
        'tanggal_daftar',
        'tanggal_login_terakhir',
        'keahlian',
        'pengalaman',
        'sertifikasi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
            'tanggal_daftar' => 'datetime',
            'tanggal_login_terakhir' => 'datetime',
        ];
    }
    
    // Relationships
    public function kursus()
    {
        return $this->hasMany(Kursus::class);
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
    
    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class);
    }
    
    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }
    
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
