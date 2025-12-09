@extends('layouts.template')

@section('title', 'Pelatihan Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.6;
        }
        .search-filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.875rem;
            background: white;
        }
        .search-box svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
        }
        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }
        .course-thumbnail-wrapper {
            width: 100%;
            height: 180px;
            overflow: hidden;
            flex-shrink: 0;
            background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);
        }
        .course-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .course-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .course-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .course-description {
            font-size: 0.875rem;
            color: #64748B;
            margin-bottom: 1rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .course-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.8125rem;
            color: #64748B;
        }
        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        .course-meta-item svg {
            width: 16px;
            height: 16px;
        }
        .progress-container {
            margin-bottom: 0.75rem;
        }
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .progress-label {
            font-size: 0.8125rem;
            color: #475569;
            font-weight: 500;
        }
        .progress-value {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #3A6DFF;
        }
        .progress-bar-container {
            height: 8px;
            background: #F1F5F9;
            border-radius: 999px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #3A6DFF 0%, #3A6DFF 100%);
            border-radius: 999px;
            transition: width 0.3s ease;
        }
        .course-footer {
            display: flex;
            align-items: center;
            margin-top: auto;
        }
        .btn-continue {
            flex: 1;
            padding: 0.75rem 1.5rem;
            background: #3A6DFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-continue:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-certificate {
            flex: 1;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-certificate:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .btn-certificate svg {
            width: 18px;
            height: 18px;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-state-icon svg {
            width: 60px;
            height: 60px;
            color: #94A3B8;
        }
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            font-size: 0.9375rem;
            color: #64748B;
            margin-bottom: 1.5rem;
        }
        .btn-browse {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: #3A6DFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-browse:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.active {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge.completed {
            background: #DBEAFE;
            color: #0284C7;
        }
        .status-badge svg {
            width: 14px;
            height: 14px;
        }
        
        /* Responsive Styles */
        /* Topbar Layout Adjustment */
        .dashboard-container.with-topbar {
            padding-top: 72px;
        }
        
        .dashboard-container.with-topbar .main-content {
            padding-top: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }
            
            .dashboard-container.with-topbar .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .main-content > div {
                padding: 0 1rem 1.5rem !important;
            }
            .page-header {
                padding: 1.5rem;
                border-radius: 16px;
                margin-bottom: 1.5rem;
            }
            .page-header h1 {
                font-size: 1.5rem;
            }
            .page-header p {
                font-size: 0.875rem;
            }
            .search-filter-bar {
                margin-bottom: 1.5rem;
            }
            .search-box {
                min-width: 100%;
            }
            .courses-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .course-thumbnail-wrapper {
                height: 160px;
            }
            .course-content {
                padding: 1.25rem;
            }
            .course-title {
                font-size: 1rem;
            }
            .empty-state {
                padding: 2.5rem 1.5rem;
            }
            .empty-state-icon {
                width: 100px;
                height: 100px;
            }
            .empty-state-icon svg {
                width: 50px;
                height: 50px;
            }
            .empty-state h3 {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .main-content > div {
                padding: 0 0.75rem 1rem !important;
            }
            .page-header {
                padding: 1.25rem;
                border-radius: 12px;
            }
            .page-header h1 {
                font-size: 1.25rem;
            }
            .course-thumbnail-wrapper {
                height: 140px;
            }
            .course-content {
                padding: 1rem;
            }
            .course-title {
                font-size: 0.9375rem;
            }
            .course-description {
                font-size: 0.8125rem;
            }
            .course-meta {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .btn-continue, .btn-certificate {
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Pelatihan Saya</h1>
                    <p>Lihat dan kelola pelatihan yang sedang Anda ikuti. Lanjutkan perjalanan Anda menuju target Anda</p>
                </div>

                <!-- Search Bar -->
                <div class="search-filter-bar">
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
                        <div class="course-card" data-course-name="{{ strtolower($enrollment->kursus->judul) }}" onclick="window.location='{{ route('admin.pelatihan.show', $enrollment->kursus->id) }}'">
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
                                        <a href="{{ route('admin.pelatihan.show', $enrollment->kursus->id) }}" class="btn-continue">
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
