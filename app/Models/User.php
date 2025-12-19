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
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'kode_unik',
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

    /**
     * Generate ID berdasarkan role
     * PST-XXXXXX untuk peserta
     * PJR-XXXXXX untuk pengajar
     * ADM-XXXXXX untuk admin/super admin
     */
    public static function generateId(string $role): string
    {
        $prefix = match(strtolower($role)) {
            'peserta' => 'PST',
            'pengajar' => 'PJR',
            'admin', 'super admin' => 'ADM',
            default => 'USR',
        };

        do {
            $randomNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $id = $prefix . '-' . $randomNumber;
        } while (self::where('id', $id)->exists());

        return $id;
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
