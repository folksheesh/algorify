@extends('layouts.template')

@section('title', 'Algorify - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kursus/show.css') }}">
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="detail-container">
        <div class="dashboard-container with-topbar">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="detail-content">
                    <a href="{{ route('kursus.index') }}" class="back-button">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="hide-mobile">Kembali ke Daftar Pelatihan</span>
                        <span class="hide-desktop">Kembali</span>
                    </a>

                    <div class="course-detail-card">
                        <div class="course-header">
                            <span class="course-category">{{ strtoupper(str_replace('_', ' ', $kursus->kategori)) }}</span>
                            <h1 class="course-title-detail">{{ $kursus->judul }}</h1>
                            <div class="course-meta-detail">
                                <div class="meta-item">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="white" stroke-width="1.5"/>
                                        <path d="M10 6V10L13 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    {{ $kursus->tanggal_mulai ? $kursus->tanggal_mulai->diffForHumans() : 'Segera' }}
                                </div>
                                <div class="meta-item">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 18C17 16.3431 14.7614 15 12 15H8C5.23858 15 3 16.3431 3 18M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    {{ $kursus->enrollments->count() }} Peserta
                                </div>
                            </div>
                        </div>

                        <div class="course-body">
                            <div class="course-section">
                                <h2 class="section-title">Deskripsi Pelatihan</h2>
                                <p class="course-description-full">{{ $kursus->deskripsi }}</p>
                            </div>

                            <div class="course-section">
                                <h2 class="section-title">Instruktur</h2>
                                <div class="instructor-card">
                                    <div class="instructor-avatar-large">
                                        {{ $kursus->pengajar ? strtoupper(substr($kursus->pengajar->name ?? 'N', 0, 1)) : 'N' }}
                                    </div>
                                    <div class="instructor-info">
                                        <div class="instructor-name-detail">{{ $kursus->pengajar->name ?? 'N/A' }}</div>
                                        <div class="instructor-role">{{ $kursus->pengajar->profesi ?? 'Instruktur' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="course-section">
                                <h2 class="section-title">Harga</h2>
                                <div class="course-price-detail">
                                    @if($kursus->harga > 0)
                                        Rp {{ number_format($kursus->harga, 0, ',', '.') }}
                                    @else
                                        Gratis
                                    @endif
                                </div>
                                <a href="{{ route('user.kursus.pembayaran', $kursus->id) }}" class="enroll-button" style="display: inline-block; text-align: center; text-decoration: none; padding: 12px 24px;">
                                    Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

@push('scripts')
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
