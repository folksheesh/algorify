@extends('layouts.template')

@section('title', 'Pelatihan Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <header class="main-header">
                <div class="search-container">
                    <input type="search" class="search-input" placeholder="Cari pelatihan saya..." aria-label="Cari pelatihan" />
                    <button type="submit" class="search-button" aria-label="Cari">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </button>
                </div>
            </header>
            <section class="hero-banner">
                <div class="hero-content">
                    <h1 class="hero-title">Pelatihan Saya</h1>
                    <p class="hero-description">Lihat dan kelola pelatihan yang sedang Anda ikuti</p>
                </div>
            </section>
            <section class="stats-section">
                <div style="padding: 2rem; background: white; border-radius: 12px; margin-top: 2rem;">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1E293B;">Halaman dalam pengembangan</h2>
                    <p style="color: #64748B;">Konten untuk halaman Pelatihan Saya akan segera ditambahkan.</p>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
