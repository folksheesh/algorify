<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Page/Template CSS -->
    @stack('styles')
    </head>
    <body>
        <!-- Header -->
        <header>
            @yield('header')
        </header>

        <!-- Sidebar / Nav (optional) -->
        @hasSection('sidebar')
            <aside>
                @yield('sidebar')
            </aside>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer>
            @yield('footer')
        </footer>

    <!-- Page/Template JS -->
    @stack('scripts')
    </body>
    </html>
