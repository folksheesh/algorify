<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

if (!function_exists('display_user_name')) {
    /**
     * Get a safe display name for the currently authenticated user.
     */
    function display_user_name(): string
    {
        $user = Auth::user();

        if (!$user) {
            return 'Guest';
        }

        $name = trim((string) ($user->name ?? ''));
        if ($name !== '') {
            return $name;
        }

        $email = trim((string) ($user->email ?? ''));
        if ($email !== '') {
            $localPart = Str::before($email, '@');
            return $localPart !== '' ? $localPart : $email;
        }

        $id = trim((string) ($user->id ?? ''));
        return $id !== '' ? $id : 'User';
    }
}