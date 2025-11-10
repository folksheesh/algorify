@extends('layouts.template')

@section('title', 'Algorify - Dashboard Pelatihan')

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
                    <input type="search" class="search-input" placeholder="Apa yang ingin anda pelajari?" aria-label="Cari pelatihan" />
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
                    <h1 class="hero-title">Tingkatkan Skill-mu<br />Bareng Pelatihan Profesional</h1>
                    <p class="hero-description">Berlangganan pelatihan lainnya untuk pengetahuan yang<br />lebih luas.</p>
                </div>
                <div class="hero-illustration">
                    <img src="{{ asset('template/img/hero-illustration.png') }}" alt="Hero Illustration" class="illustration-graphic">
                </div>
            </section>
            <section class="stats-section">
                <article class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                            <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-value">2/8 Ditonton</p>
                        <h3 class="stat-label">Software Dev</h3>
                    </div>
                    <div class="stat-menu">
                        <button type="button" class="menu-button" aria-label="Menu opsi">
                            <svg width="4" height="16" viewBox="0 0 4 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="2" cy="2" r="2" fill="currentColor" />
                                <circle cx="2" cy="8" r="2" fill="currentColor" />
                                <circle cx="2" cy="14" r="2" fill="currentColor" />
                            </svg>
                        </button>
                    </div>
                </article>
                <article class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                            <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-value">2/8 Ditonton</p>
                        <h3 class="stat-label">FrontEnd</h3>
                    </div>
                    <div class="stat-menu">
                        <button type="button" class="menu-button" aria-label="Menu opsi">
                            <svg width="4" height="16" viewBox="0 0 4 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="2" cy="2" r="2" fill="currentColor" />
                                <circle cx="2" cy="8" r="2" fill="currentColor" />
                                <circle cx="2" cy="14" r="2" fill="currentColor" />
                            </svg>
                        </button>
                    </div>
                </article>
                <article class="stat-card">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                            <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-value">2/8 Ditonton</p>
                        <h3 class="stat-label">BackEnd</h3>
                    </div>
                    <div class="stat-menu">
                        <button type="button" class="menu-button" aria-label="Menu opsi">
                            <svg width="4" height="16" viewBox="0 0 4 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="2" cy="2" r="2" fill="currentColor" />
                                <circle cx="2" cy="8" r="2" fill="currentColor" />
                                <circle cx="2" cy="14" r="2" fill="currentColor" />
                            </svg>
                        </button>
                    </div>
                </article>
            </section>
            <section class="courses-section">
                <header class="section-header">
                    <h2 class="section-title">Lanjutkan Menonton</h2>
                    <div class="section-controls">
                        <button type="button" class="control-button" aria-label="Sebelumnya">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button type="button" class="control-button" aria-label="Selanjutnya">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </header>
                <div class="courses-grid">
                    @foreach (range(1,3) as $i)
                    <article class="course-card">
                        <div class="course-thumbnail">
                            <img src="{{ asset('template/assets/compiled/jpg/' . ($i+1) . '.jpg') }}" alt="Peran & Tugas Frontend Developer" class="course-image" />
                            <button type="button" class="bookmark-button" aria-label="Simpan ke bookmark">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 3C5 2.44772 5.44772 2 6 2H14C14.5523 2 15 2.44772 15 3V18L10 15L5 18V3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                                </svg>
                            </button>
                        </div>
                        <div class="course-content">
                            <span class="course-badge">FRONTEND</span>
                            <h3 class="course-title">Peran & Tugas Frontend Developer</h3>
                            <div class="course-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ 30 + $i * 10 }}%"></div>
                                </div>
                            </div>
                            <div class="course-author">
                                <img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}" alt="Prashant Kumar Singh" class="author-avatar" />
                                <div class="author-info">
                                    <p class="author-name">Prashant Kumar Singh</p>
                                    <p class="author-role">Software Developer</p>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
