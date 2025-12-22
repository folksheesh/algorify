<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * Adds security headers to all responses for OWASP compliance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking - page cannot be embedded in iframe
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy - control how much referrer info is sent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - disable unnecessary browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS - Force HTTPS (only in production)
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy - control resource loading
        // Allow inline scripts/styles for Laravel applications, YouTube embeds, jQuery, Select2, Google OAuth
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://www.youtube.com https://s.ytimg.com https://accounts.google.com https://apis.google.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://accounts.google.com https://fonts.bunny.net; " .
               "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:; " .
               "img-src 'self' data: blob: https: http:; " .
               "frame-src 'self' https://www.youtube.com https://youtube.com https://accounts.google.com https://staging.doku.com; " .
               "connect-src 'self' https://accounts.google.com https://oauth2.googleapis.com; " .
               "media-src 'self' blob:; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self' https://accounts.google.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        // Prevent caching of sensitive pages
        if ($request->is('login', 'register', 'password/*', 'admin/*', 'dashboard/*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
