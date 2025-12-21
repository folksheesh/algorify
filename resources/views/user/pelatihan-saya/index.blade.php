@extends('layouts.template')

@section('title', 'Pelatihan Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peserta/pelatihan-index.css') }}">
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <!-- Page Header with Hero Banner -->
                <div class="page-header-hero">
                    <div class="hero-content-left">
                        <h1>Pelatihan Saya</h1>
                        <p>Lihat dan kelola pelatihan yang sedang Anda ikuti. Lanjutkan perjalanan Anda menuju target Anda</p>
                    </div>
                    <div class="hero-icon-right">
                        <img src="{{ asset('template/img/icon-hero-banner-pelatihan.png') }}" alt="Pelatihan Icon" class="hero-icon-img">
                    </div>
                </div>

                <!-- Search Bar di bawah hero -->
                <div class="search-filter-bar-below">
                    <div class="search-box">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari pelatihan saya...">
                    </div>
                </div>

                @if($enrollments->count() > 0)
                    <!-- Courses Grid -->
                    <div class="courses-grid" id="coursesGrid">
                        @foreach($enrollments as $enrollment)
                        <div class="course-card" data-course-name="{{ strtolower($enrollment->kursus->judul) }}" onclick="window.location='{{ route('admin.pelatihan.show', $enrollment->kursus->slug) }}'">
                            <div class="course-thumbnail-wrapper">
                                @php
                                    $enrollmentThumbnail = resolve_thumbnail_url(
                                        $enrollment->kursus->thumbnail,
                                        asset('template/assets/static/images/samples/origami.jpg')
                                    );
                                @endphp
                                <img src="{{ $enrollmentThumbnail }}" 
                                     alt="{{ $enrollment->kursus->judul }}" 
                                     class="course-thumbnail"
                                     onerror="this.src='{{ asset('template/assets/static/images/samples/origami.jpg') }}'">
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $enrollment->kursus->judul }}</h3>
                                <p class="course-description">{{ $enrollment->kursus->deskripsi_singkat }}</p>
                                
                                <div class="course-meta">
                                    <div class="course-meta-item">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                        <span>{{ $enrollment->kursus->modul->count() }} Modul</span>
                                    </div>
                                    <span class="status-badge {{ $enrollment->status }}">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            @if($enrollment->status === 'completed')
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                        {{ $enrollment->status === 'completed' ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="progress-header">
                                        <span class="progress-label">Progress</span>
                                        <span class="progress-value">{{ $enrollment->progress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="course-footer" onclick="event.stopPropagation()">
                                    @if(($enrollment->progress ?? 0) >= 100 || $enrollment->status === 'completed')
                                        <a href="{{ route('user.sertifikat.index') }}" class="btn-certificate">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                            Cek Sertifikat
                                        </a>
                                    @else
                                        <a href="{{ route('admin.pelatihan.show', $enrollment->kursus->slug) }}" class="btn-continue">
                                            Lanjutkan Belajar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        </div>
                        <h3>Belum Ada Pelatihan</h3>
                        <p>Anda belum mendaftar pelatihan apapun. Mulai perjalanan belajar Anda sekarang!</p>
                        <a href="{{ route('kursus.index') }}" class="btn-browse">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            Jelajahi Pelatihan
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const courseCards = document.querySelectorAll('.course-card');
                
                courseCards.forEach(card => {
                    const courseName = card.getAttribute('data-course-name');
                    if (courseName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    </script>
@endpush
