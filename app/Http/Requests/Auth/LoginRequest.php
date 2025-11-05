<?php // Request khusus untuk proses login user

namespace App\Http\Requests\Auth; // Namespace request auth

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// Kelas ini menangani validasi dan proses autentikasi login user
class LoginRequest extends FormRequest
{
    /**
     * Mengecek apakah user diizinkan melakukan request ini.
     * Biasanya selalu true untuk form login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk form login.
     * Email dan password wajib diisi.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Melakukan proses autentikasi user berdasarkan email dan password.
     * Jika gagal, akan menambah hit rate limiter dan melempar error validasi.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan permintaan login tidak melebihi batas percobaan (rate limit).
     * Jika terlalu banyak percobaan gagal, user akan di-lock sementara.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Mengambil key unik untuk rate limiter berdasarkan email dan IP user.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
