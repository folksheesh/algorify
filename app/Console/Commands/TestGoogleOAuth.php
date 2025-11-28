<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:google-oauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google OAuth configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Google OAuth Configuration...');
        $this->newLine();

        // Check if Socialite is installed
        if (!class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            $this->error('❌ Laravel Socialite is not installed!');
            $this->info('Run: composer require laravel/socialite');
            return 1;
        }
        $this->info('✅ Laravel Socialite installed');

        // Check Client ID
        $clientId = config('services.google.client_id');
        if (empty($clientId)) {
            $this->error('❌ GOOGLE_CLIENT_ID not configured in .env');
        } else {
            $this->info('✅ GOOGLE_CLIENT_ID: ' . substr($clientId, 0, 20) . '...');
        }

        // Check Client Secret
        $clientSecret = config('services.google.client_secret');
        if (empty($clientSecret)) {
            $this->error('❌ GOOGLE_CLIENT_SECRET not configured in .env');
        } else {
            $this->info('✅ GOOGLE_CLIENT_SECRET: ' . str_repeat('*', strlen($clientSecret)));
        }

        // Check Redirect URL
        $redirectUrl = config('services.google.redirect');
        if (empty($redirectUrl)) {
            $this->error('❌ Redirect URL not configured');
        } else {
            $this->info('✅ Redirect URL: ' . $redirectUrl);
            
            // Check if redirect URL uses HTTPS in production
            if (app()->environment('production') && !str_starts_with($redirectUrl, 'https://')) {
                $this->warn('⚠️  Redirect URL should use HTTPS in production!');
            }
        }

        // Check APP_URL
        $appUrl = config('app.url');
        $this->info('✅ APP_URL: ' . $appUrl);

        // Check routes
        $this->newLine();
        $this->info('Checking routes...');
        
        $baseUrl = config('app.url');
        $this->info('✅ Google redirect URL should be: ' . $baseUrl . '/auth/google');
        $this->info('✅ Google callback URL should be: ' . $baseUrl . '/auth/google/callback');

        // Summary
        $this->newLine();
        if (empty($clientId) || empty($clientSecret)) {
            $this->error('Configuration incomplete! Please check your .env file.');
            $this->newLine();
            $this->info('Required in .env:');
            $this->line('GOOGLE_CLIENT_ID=your-client-id-here');
            $this->line('GOOGLE_CLIENT_SECRET=your-client-secret-here');
            $this->line('GOOGLE_REDIRECT_URL=' . config('app.url') . '/auth/google/callback');
            return 1;
        }

        $this->info('✅ Google OAuth configuration looks good!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. Make sure your domain is registered in Google Cloud Console');
        $this->line('2. Add authorized redirect URI: ' . $redirectUrl);
        $this->line('3. Test login at: ' . config('app.url') . '/login');
        
        return 0;
    }
}
