@extends('layouts.template')

@section('title', 'Algorify - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .detail-container {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .detail-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #5D3FFF;
        }

        .course-detail-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .course-header {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .course-category {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .course-title-detail {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .course-meta-detail {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .course-body {
            padding: 2rem;
        }

        .course-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #5D3FFF;
        }

        .course-description-full {
            color: #374151;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .course-price-detail {
            font-size: 2rem;
            font-weight: 700;
            color: #5D3FFF;
            margin-bottom: 1rem;
        }

        .enroll-button {
            background: #5D3FFF;
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            max-width: 400px;
        }

        .enroll-button:hover {
            background: #4c32cc;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(93, 63, 255, 0.3);
        }

        .instructor-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 10px;
        }

        .instructor-avatar-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #6b7280;
        }

        .instructor-info {
            flex: 1;
        }

        .instructor-name-detail {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .instructor-role {
            font-size: 0.875rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .detail-content {
                padding: 1rem;
            }
            
            .course-title-detail {
                font-size: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="detail-container">
        <div class="dashboard-container">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="detail-content">
                    <a href="{{ route('kursus.index') }}" class="back-button">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Kembali ke Daftar Pelatihan
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
                                        {{ strtoupper(substr($kursus->pengajar->name, 0, 1)) }}
                                    </div>
                                    <div class="instructor-info">
                                        <div class="instructor-name-detail">{{ $kursus->pengajar->name }}</div>
                                        <div class="instructor-role">{{ $kursus->pengajar->profesi ?? 'Instruktur' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="course-section">
                                <h2 class="section-title">Investasi</h2>
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
@endsection

@push('scripts')
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
