<?php

use Illuminate\Support\Str;

if (!function_exists('display_user_name')) {
    /**
     * Normalize and return the currently authenticated user's name.
     */
    function display_user_name(?\Illuminate\Contracts\Auth\Authenticatable $user = null): string
    {
        $raw = trim(($user?->name) ?? (auth()->user()->name ?? ''));

        if ($raw === '') {
            return 'Pengguna';
        }

        $normalized = preg_replace('/\s+/', ' ', $raw);

        return Str::title($normalized);
    }
}

if (!function_exists('resolve_thumbnail_url')) {
    /**
     * Determine the correct thumbnail URL, allowing external links or storage paths.
     */
    function resolve_thumbnail_url(?string $path, ?string $fallback = null): string
    {
        if (!empty($path) && Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (!empty($path)) {
            return asset('storage/' . ltrim($path, '/'));
        }

        if (!empty($fallback)) {
            return $fallback;
        }

        return asset('template/assets/static/images/samples/origami.jpg');
    }
}
