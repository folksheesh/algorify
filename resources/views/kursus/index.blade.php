@extends('layouts.template')

@section('title', 'Algorify - Jelajahi Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kursus/index.css') }}">

@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="pelatihan-container">
        <div class="dashboard-container with-topbar">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="pelatihan-content">
                    <div class="pelatihan-header">
                        <h1>Jelajahi Pelatihan</h1>
                    </div>

                    <div class="search-filter-section">
                        <form method="GET" action="{{ route('kursus.index') }}">
                            <div class="search-box">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                <input type="search" name="search" placeholder="Cari pelatihan..." value="{{ request('search') }}" />
                            </div>

                            <div class="filter-section">
                                <div class="filter-label">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 4h14M5 8h10M7 12h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    Filter Pelatihan
                                </div>
                                <label class="kategori-label">Kategori</label>
                                <div class="filter-badges">
                                    <a href="{{ route('kursus.index') }}" class="filter-badge {{ !request('kategori') ? 'active' : '' }}">
                                        Semua
                                    </a>
                                    @foreach($categories as $category)
                                    <a href="{{ route('kursus.index', ['kategori' => $category->id]) }}" class="filter-badge {{ request('kategori') == $category->id ? 'active' : '' }}">
                                        {{ $category->nama_kategori }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($kursus->count() > 0)
                        <div class="courses-grid">
                            @foreach($kursus as $course)
                            <div class="course-card">
                                <div class="course-thumbnail">
                                    @php
                                        $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                    @endphp
                                    @if($courseThumbnailUrl)
                                        <img src="{{ $courseThumbnailUrl }}" alt="{{ $course->judul }}" />
                                    @endif
                                    <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori)) }}</span>
                                    <button type="button" class="bookmark-btn" onclick="event.stopPropagation();">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 3C5 2.44772 5.44772 2 6 2H14C14.5523 2 15 2.44772 15 3V18L10 15L5 18V3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="course-content">
                                    <h3 class="course-title">{{ $course->judul }}</h3>
                                    <p class="course-description">{{ $course->deskripsi_singkat }}</p>
                                    <a href="{{ route('kursus.show', $course->id) }}" class="view-detail-btn">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($kursus->hasPages())
                            <div class="pagination-wrapper">
                                {{ $kursus->links() }}
                            </div>
                            <div class="pagination-info">
                                Menampilkan {{ $kursus->firstItem() }} - {{ $kursus->lastItem() }} dari {{ $kursus->total() }} pelatihan
                            </div>
                        @endif
                    @else
                        <div class="no-courses">
                            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="60" cy="60" r="50" stroke="currentColor" stroke-width="2"/>
                                <path d="M40 55h40M40 65h40M40 75h25" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <h3 style="margin-bottom: 0.5rem; color: #374151;">Tidak ada pelatihan ditemukan</h3>
                            <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                        </div>
                    @endif
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
