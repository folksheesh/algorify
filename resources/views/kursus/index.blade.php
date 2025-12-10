@extends('layouts.template')

@section('title', 'Algorify - Jelajahi Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
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
                        <form method="GET" action="{{ route('kursus.index') }}" id="filterForm">
                            <div class="search-filter-wrapper">
                                <div class="search-box">
                                    <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                    <input 
                                        type="text" 
                                        class="search-input" 
                                        name="search"
                                        placeholder="Cari pelatihan berdasarkan judul..."
                                        value="{{ request('search') }}"
                                        autocomplete="off"
                                    >
                                </div>
                                
                                <div class="filters-container">
                                    <select name="kategori" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Kategori</option>
                                        <option value="programming" {{ request('kategori') == 'programming' ? 'selected' : '' }}>Programming</option>
                                        <option value="design" {{ request('kategori') == 'design' ? 'selected' : '' }}>Design</option>
                                        <option value="business" {{ request('kategori') == 'business' ? 'selected' : '' }}>Business</option>
                                        <option value="marketing" {{ request('kategori') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="data_science" {{ request('kategori') == 'data_science' ? 'selected' : '' }}>Data Science</option>
                                        <option value="other" {{ request('kategori') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>

                                    <select name="tipe_kursus" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Tipe Kursus</option>
                                        <option value="online" {{ request('tipe_kursus') == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="offline" {{ request('tipe_kursus') == 'offline' ? 'selected' : '' }}>Offline</option>
                                        <option value="hybrid" {{ request('tipe_kursus') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
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
