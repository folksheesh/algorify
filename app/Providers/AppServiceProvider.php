<?php // File untuk mendaftarkan layanan aplikasi

namespace App\Providers; // Namespace untuk service provider

use Illuminate\Support\ServiceProvider; // Import kelas dasar ServiceProvider
use App\Repositories\ProgressRepository;

use Illuminate\Support\Facades\URL;

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
        // Ensure global helper functions are loaded even if Composer autoload files are stale.
        $helpersPath = app_path('helpers.php');
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        // Register ProgressRepository as singleton
        $this->app->singleton(ProgressRepository::class, function ($app) {
            return new ProgressRepository();
        });
    }

    /**
     * Menginisialisasi layanan-layanan aplikasi.
     * Dijalankan setelah semua layanan terdaftar.
     * Tempat untuk konfigurasi view, route, event listener, dll.
     */
    public function boot(): void
    {
        // Force HTTPS specifically for ngrok or production
        if($this->app->environment('production') || str_contains(request()->url(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
    }
}
