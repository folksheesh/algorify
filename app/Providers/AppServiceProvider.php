<?php // File untuk mendaftarkan layanan aplikasi

namespace App\Providers; // Namespace untuk service provider

use Illuminate\Support\ServiceProvider; // Import kelas dasar ServiceProvider

// Kelas ini menangani pendaftaran dan inisialisasi layanan-layanan
// yang dibutuhkan aplikasi saat pertama kali dijalankan
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan layanan-layanan aplikasi.
     * Dijalankan saat aplikasi pertama kali dimuat.
     * Tempat mendaftarkan binding ke container, singleton, dll.
     */
    public function register(): void
    {
        //
    }

    /**
     * Menginisialisasi layanan-layanan aplikasi.
     * Dijalankan setelah semua layanan terdaftar.
     * Tempat untuk konfigurasi view, route, event listener, dll.
     */
    public function boot(): void
    {
        //
    }
}
