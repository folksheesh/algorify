<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Generate a new OTP for email
     */
    public static function generateFor(string $email): string
    {
        // Delete existing OTPs for this email
        self::where('email', $email)->delete();
        
        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create new OTP record (expires in 15 minutes)
        self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(15),
            'created_at' => now(),
        ]);
        
        return $otp;
    }

    /**
     * Verify OTP for email
     */
    public static function verify(string $email, string $otp): bool
    {
        $record = self::where('email', $email)
            ->where('otp', $otp)
            ->first();
        
        if (!$record) {
            return false;
        }
        
        if ($record->isExpired()) {
            $record->delete();
            return false;
        }
        
        return true;
    }

    /**
     * Clear OTP after successful verification
     */
    public static function clearFor(string $email): void
    {
        self::where('email', $email)->delete();
    }
}
