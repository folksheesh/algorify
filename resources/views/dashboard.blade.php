@extends('layouts.template')

@section('title', 'Algorify - Dashboard Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peserta/dashboard.css') }}">
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <section class="hero-banner" style="background-image: url('{{ asset('template/img/hero-banner-bg.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="hero-content">
                    <h1 class="hero-title">Tingkatkan Skill-mu<br />Bersama Pelatihan Profesional</h1>
                    <p class="hero-description">Jelajahi pelatihan terbaik untuk mengembangkan kemampuanmu.</p>
                    <a href="{{ route('kursus.index') }}" class="hero-cta-btn">Mulai Belajar Sekarang</a>
                </div>
            </section>
            
            <section class="courses-section" style="margin-bottom: 2rem;">
                <header class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h2 class="section-title" style="margin: 0;">Lanjutkan Belajar</h2>
                    <a href="{{ route('user.pelatihan-saya.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                        Lihat Semua 
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </header>
                <div id="continueCarousel" class="continue-carousel-wrapper">
                    @forelse($enrollments as $enrollment)
                        @if($enrollment->kursus)
                        <a href="{{ route('kursus.show', $enrollment->kursus_id) }}" class="continue-card">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                                <div style="background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%); color: #fff; width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h3 style="font-size: 1rem; color: #1E293B; margin: 0; font-weight: 600; line-height: 1.4;">{{ Str::limit($enrollment->kursus->judul, 40) }}</h3>
                                    <p style="font-size: 0.8125rem; color: #64748B; margin: 0.25rem 0 0 0;">{{ $enrollment->kursus->kategori ?? 'Pelatihan' }}</p>
                                </div>
                            </div>
                            <div style="margin-top: auto;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.8125rem; color: #64748B;">Progress</span>
                                    <span style="font-size: 0.875rem; color: #6366F1; font-weight: 600;">{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                                <div style="width: 100%; height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $enrollment->progress ?? 0 }}%; height: 100%; background: linear-gradient(90deg, #6366F1 0%, #8B5CF6 100%); border-radius: 4px; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        </a>
                        @endif
                    @empty
                    <div style="grid-column: 1 / -1; background: #fff; border-radius: 16px; padding: 3rem; text-align: center; border: 1px solid #E2E8F0;">
                        <div style="width: 64px; height: 64px; background: #EEF2FF; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <p style="font-size: 0.9375rem; color: #64748B; margin: 0 0 0.75rem 0;">Belum ada pelatihan yang diikuti</p>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                            Jelajahi Pelatihan →
                        </a>
                    </div>
                    @endforelse
                    {{-- Spacer untuk card terakhir tidak terpotong --}}
                    <div style="flex-shrink: 0; width: 1px; height: 1px;"></div>
                </div>
            </section>
            
            <style>
                .continue-carousel-wrapper {
                    display: flex;
                    gap: 1.5rem;
                    overflow-x: auto;
                    scroll-behavior: smooth;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    padding: 0.5rem 0;
                    margin-right: -2rem;
                    padding-right: 2rem;
                }
                .continue-carousel-wrapper::-webkit-scrollbar { display: none; }
                .continue-carousel-wrapper .continue-card {
                    flex: 0 0 calc((100% - 3rem) / 3);
                    min-width: 280px;
                    max-width: 380px;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    display: flex;
                    flex-direction: column;
                    padding: 1.5rem;
                    background: #fff;
                    border-radius: 16px;
                    border: 1px solid #E2E8F0;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                }
                .continue-card:hover { 
                    transform: translateY(-4px); 
                    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.15); 
                    border-color: #6366F1;
                }
                .carousel-nav button:hover { border-color: #6366F1; background: #EEF2FF; }
                .carousel-nav button:hover svg path { stroke: #6366F1; }
                @media (max-width: 1024px) {
                    .continue-carousel-wrapper .continue-card { flex: 0 0 calc((100% - 1.5rem) / 2); min-width: 260px; }
                }
                @media (max-width: 640px) {
                    .continue-carousel-wrapper .continue-card { flex: 0 0 85%; min-width: unset; max-width: unset; }
                }
                
                /* Fix hero banner overflow */
                .hero-banner {
                    overflow: visible !important;
                }
                .main-content {
                    overflow-x: hidden;
                }
            </style>
            
            <script>
                function scrollCarousel(direction) {
                    const carousel = document.getElementById('continueCarousel');
                    const cardWidth = carousel.querySelector('.continue-card')?.offsetWidth || 300;
                    const gap = 24;
                    carousel.scrollBy({ left: direction * (cardWidth + gap), behavior: 'smooth' });
                }
            </script>

            <script>
                // Make recommendation cards navigate to the course detail page on click/keyboard
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.course-card[data-course-url]').forEach(function (card) {
                        const navigateToCourse = function () {
                            const targetUrl = card.dataset.courseUrl;
                            if (targetUrl) {
                                window.location.href = targetUrl;
                            }
                        };

                        card.addEventListener('click', function (event) {
                            if (event.target.closest('.bookmark-button')) {
                                return;
                            }
                            navigateToCourse();
                        });

                        card.addEventListener('keydown', function (event) {
                            if (event.key === 'Enter' || event.key === ' ') {
                                event.preventDefault();
                                navigateToCourse();
                            }
                        });
                    });
                });
            </script>

            <section class="courses-section">
                <header class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h2 class="section-title" style="margin: 0;">Rekomendasi Pelatihan</h2>
                    <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                        Lihat Semua 
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </header>
                <div class="courses-grid">
                    @forelse($recommendedCourses as $kursus)
                    <article
                        class="course-card"
                        data-course-url="{{ route('kursus.show', $kursus->id) }}"
                        role="button"
                        tabindex="0"
                    >
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
                                @if($kursus->pengajar && is_object($kursus->pengajar) && $kursus->pengajar->profile_photo)
                                    <img src="{{ asset('storage/' . $kursus->pengajar->profile_photo) }}" alt="{{ $kursus->pengajar->name }}" class="author-avatar" />
                                @else
                                    <div class="author-avatar" style="background: #6366F1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ $kursus->pengajar && is_object($kursus->pengajar) ? substr($kursus->pengajar->name, 0, 1) : '?' }}
                                    </div>
                                @endif
                                <div class="author-info">
                                    <p class="author-name">{{ $kursus->pengajar && is_object($kursus->pengajar) ? $kursus->pengajar->name : 'Pengajar' }}</p>
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
