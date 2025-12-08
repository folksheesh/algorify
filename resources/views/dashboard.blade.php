@extends('layouts.template')

@section('title', 'Algorify - Dashboard Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .course-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .course-image {
            transition: transform 0.3s ease;
        }
        .course-card:hover .course-image {
            transform: scale(1.05);
        }
        .bookmark-button {
            transition: all 0.3s ease;
        }
        .bookmark-button:hover {
            background: #6366F1;
            color: white;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .course-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            background: #EEF2FF;
            color: #6366F1;
            border-radius: 1rem;
            font-weight: 600;
        }
        
        /* Responsive adjustments untuk inline styles */
        @media (max-width: 768px) {
            .course-card:hover {
                transform: translateY(-4px);
            }
            
            .course-description-text {
                font-size: 0.8rem !important;
                -webkit-line-clamp: 2;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .course-price-footer {
                flex-direction: column !important;
                gap: 8px !important;
                align-items: flex-start !important;
            }
            
            .course-price-footer span:last-child {
                align-self: flex-end;
            }
        }
        
        @media (max-width: 480px) {
            .course-card:hover {
                transform: translateY(-2px);
            }
            
            .author-avatar {
                width: 28px !important;
                height: 28px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <header class="main-header">
                <form action="{{ route('kursus.index') }}" method="GET" class="search-container">
                    <input type="search" name="search" class="search-input" placeholder="Apa yang ingin anda pelajari?" aria-label="Cari pelatihan" />
                    <button type="submit" class="search-button" aria-label="Cari">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </button>
                </form>
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
            
            <section class="courses-section" style="margin-bottom: 2rem;">
                <header class="section-header">
                    <h2 class="section-title">Lanjutkan Belajar</h2>
                    <a href="{{ route('user.pelatihan-saya.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                        Lihat Semua →
                    </a>
                </header>
                <div class="stats-grid" style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($enrollments as $enrollment)
                    <a href="{{ route('kursus.show', $enrollment->kursus_id) }}" class="stat-card" style="text-decoration: none; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; display: flex; align-items: center; padding: 1rem 1.5rem; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)';">
                        <div class="stat-icon" style="background: #EEF2FF; color: #6366F1; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                                <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                            </svg>
                        </div>
                        <div class="stat-content" style="flex: 1;">
                            <p class="stat-value" style="font-size: 0.75rem; color: #6366F1; margin: 0; font-weight: 600;">{{ $enrollment->progress ?? 0 }}% Selesai</p>
                            <h3 class="stat-label" style="font-size: 1rem; color: #1E293B; margin: 0.25rem 0 0 0; font-weight: 600;">{{ Str::limit($enrollment->kursus->judul ?? 'Kursus', 50) }}</h3>
                        </div>
                        <div class="stat-menu" style="color: #94A3B8;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </a>
                    @empty
                    <div style="background: #fff; border-radius: 12px; padding: 2rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                        <p style="font-size: 0.875rem; color: #64748B; margin: 0 0 0.5rem 0;">Belum ada pelatihan yang diikuti</p>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600;">
                            Jelajahi Pelatihan →
                        </a>
                    </div>
                    @endforelse
                </div>
            </section>

            <section class="courses-section">
                <header class="section-header">
                    <h2 class="section-title">Rekomendasi Pelatihan</h2>
                    <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                        Lihat Semua →
                    </a>
                </header>
                <div class="courses-grid">
                    @forelse($recommendedCourses as $kursus)
                    <article class="course-card">
                        <div class="course-thumbnail">
                            @if($kursus->gambar)
                                <img src="{{ asset('storage/' . $kursus->gambar) }}" alt="{{ $kursus->judul }}" class="course-image" />
                            @else
                                <img src="{{ asset('template/assets/compiled/jpg/' . (($loop->index % 3) + 2) . '.jpg') }}" alt="{{ $kursus->judul }}" class="course-image" />
                            @endif
                            <a href="{{ route('kursus.show', $kursus->id) }}" class="bookmark-button" aria-label="Lihat detail">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <div class="course-content">
                            <span class="course-badge">{{ strtoupper($kursus->kategori ?? 'PELATIHAN') }}</span>
                            <h3 class="course-title">{{ Str::limit($kursus->judul, 50) }}</h3>
                            <p class="course-description-text" style="font-size: 0.875rem; color: #64748B; margin: 0.5rem 0; line-height: 1.5;">
                                {{ Str::limit($kursus->deskripsi_singkat ?? $kursus->deskripsi, 80) }}
                            </p>
                            @if($kursus->pengajar)
                            <div class="course-author">
                                @if($kursus->pengajar->profile_photo)
                                    <img src="{{ asset('storage/' . $kursus->pengajar->profile_photo) }}" alt="{{ $kursus->pengajar->name }}" class="author-avatar" />
                                @else
                                    <div class="author-avatar" style="background: #6366F1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ substr($kursus->pengajar->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="author-info">
                                    <p class="author-name">{{ $kursus->pengajar->name }}</p>
                                    <p class="author-role">Pengajar</p>
                                </div>
                            </div>
                            @endif
                            <div class="course-price-footer" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.875rem; color: #64748B;">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    {{ $kursus->enrollments_count ?? 0 }} Peserta
                                </span>
                                <span style="font-size: 1rem; font-weight: 700; color: #6366F1;">
                                    Rp {{ number_format($kursus->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #64748B;">
                        <p style="font-size: 1.125rem; margin-bottom: 1rem;">Belum ada pelatihan tersedia</p>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600;">
                            Jelajahi Pelatihan →
                        </a>
                    </div>
                    @endforelse
                </div>
            </section>
        </main>
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
