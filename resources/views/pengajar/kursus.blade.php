@extends('layouts.template')

@section('title', 'Data Kursus Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-index.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Data Kursus</h1>
                    
                    <!-- Search Bar -->
                    <div class="search-wrapper">
                        <div class="search-box">
                            <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                            <input 
                                type="text" 
                                class="search-input" 
                                id="searchKursus"
                                placeholder="Cari kursus berdasarkan judul..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>

                @if($kursus->count() > 0)
                    <!-- Courses Grid -->
                    <div class="courses-grid">
                        @foreach($kursus as $course)
                        <div class="course-card" onclick="window.location='{{ route('admin.pelatihan.show', $course->id) }}'" style="cursor: pointer;">
                            <div class="course-thumbnail-container">
                                @php
                                    $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                @endphp
                                @if($courseThumbnailUrl)
                                    <img src="{{ $courseThumbnailUrl }}" 
                                         alt="{{ $course->judul }}" 
                                         class="course-thumbnail"
                                         onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';">
                                @else
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori ?? 'OTHER')) }}</span>
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $course->judul }}</h3>
                                <p class="course-type">{{ ucfirst($course->status ?? 'Published') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $kursus->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z"/>
                            </svg>
                        </div>
                        <h3>Belum Ada Kursus</h3>
                        <p>Belum ada kursus yang di-assign ke Anda</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKursus');
            const courseCards = document.querySelectorAll('.course-card');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    courseCards.forEach(card => {
                        const title = card.querySelector('.course-title');
                        if (title) {
                            const titleText = title.textContent.toLowerCase();
                            
                            if (titleText.includes(searchTerm)) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
@endpush
