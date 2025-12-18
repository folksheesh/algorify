<?php

use Illuminate\Support\Str;

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