@extends('layouts.template')

@section('title', 'Algorify - Jelajahi Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .pelatihan-container {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .pelatihan-content {
            padding: 1rem 2rem 2rem 2rem;
        }

        .pelatihan-header {
            margin-bottom: 1.25rem;
        }

        .pelatihan-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .search-filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #5D3FFF;
            box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
        }

        .search-box svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .filter-section {
            margin-bottom: 1rem;
        }

        .filter-label {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .filter-label svg {
            margin-right: 0.5rem;
        }

        .kategori-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.75rem;
            display: block;
        }

        .filter-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .filter-badge {
            padding: 0.5rem 1.25rem;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            font-size: 0.875rem;
            color: #374151;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .filter-badge:hover {
            border-color: #5D3FFF;
            color: #5D3FFF;
            background: #f5f3ff;
        }

        .filter-badge.active {
            background: #5D3FFF;
            color: white;
            border-color: #5D3FFF;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 1200px) {
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .course-thumbnail {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: #f3f4f6;
            flex-shrink: 0;
        }

        .course-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .course-badge {
            position: absolute;
            top: 0.625rem;
            left: 0.625rem;
            background: white;
            color: #5D3FFF;
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .bookmark-btn {
            position: absolute;
            top: 0.625rem;
            right: 0.625rem;
            background: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .bookmark-btn svg {
            width: 16px;
            height: 16px;
        }

        .bookmark-btn:hover {
            background: #5D3FFF;
            color: white;
            transform: scale(1.05);
        }

        .course-content {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .course-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.55em;
        }

        .course-description {
            font-size: 0.8125rem;
            color: #64748b;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.25em;
            margin-bottom: 0.875rem;
        }

        .view-detail-btn {
            width: 100%;
            padding: 0.5rem;
            background: white;
            border: 1.5px solid #5D3FFF;
            color: #5D3FFF;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            display: block;
            box-sizing: border-box;
            margin-top: auto;
        }

        .view-detail-btn:hover {
            background: #5D3FFF;
            color: white;
            transform: translateY(-1px);
        }

        .no-courses {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .no-courses svg {
            margin: 0 auto 1rem;
            opacity: 0.3;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2.5rem;
            padding: 1.5rem 0;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-item .page-link {
            padding: 0.5rem 0.875rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            font-size: 0.875rem;
            background: white;
        }

        .pagination .page-item .page-link:hover:not(.disabled) {
            border-color: #5D3FFF;
            color: #5D3FFF;
            background: #f5f3ff;
        }

        .pagination .page-item.active .page-link {
            background: #5D3FFF;
            color: white;
            border-color: #5D3FFF;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f9fafb;
        }

        /* Hide default Laravel pagination text */
        .pagination-wrapper nav > div:first-child,
        .pagination-wrapper nav > div:last-child {
            display: none;
        }

        /* Pagination info text */
        .pagination-info {
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .pelatihan-content {
                padding: 1rem;
            }

            .filter-badges {
                gap: 0.5rem;
            }

            .filter-badge {
                padding: 0.4rem 1rem;
                font-size: 0.8125rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="pelatihan-container">
        <div class="dashboard-container">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="pelatihan-content">
                    <div class="pelatihan-header">
                        <h1>Pelatihan Saya</h1>
                    </div>

                    <div style="max-width: 360px;">
                        @if(isset($enrollments) && $enrollments->count() > 0)
                            @foreach($enrollments as $enrollment)
                                @php
                                    $kursus = $enrollment->kursus;
                                    $progress = is_numeric($enrollment->progress) ? (int) $enrollment->progress : 0;
                                @endphp

                                <div style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);margin-bottom:20px;">
                                    <div style="height:140px;overflow:hidden;background:#f3f4f6;">
                                        @if($kursus && $kursus->thumbnail)
                                            <img src="{{ asset('storage/' . $kursus->thumbnail) }}" alt="{{ $kursus->judul }}" style="width:100%;height:140px;object-fit:cover;" />
                                        @else
                                            <div style="width:100%;height:140px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">No image</div>
                                        @endif
                                    </div>
                                    <div style="padding:16px;">
                                        <div style="font-size:0.9rem;color:#111827;margin-bottom:8px;">{{ $kursus->judul ?? '—' }}</div>
                                        <div style="font-size:0.8rem;color:#6b7280;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">
                                            <span>Progress</span>
                                            <span style="font-weight:600">{{ $progress }}%</span>
                                        </div>
                                        <div style="background:#e6e6e6;border-radius:8px;height:8px;overflow:hidden;margin-bottom:12px;">
                                            <div style="width:{{ $progress }}%;height:100%;background:#0f172a;border-radius:8px;"></div>
                                        </div>

                                        @if($progress >= 100)
                                            <a href="{{ route('kursus.show', $kursus->id ?? 0) }}" class="view-detail-btn" style="display:block;text-align:center;background:white;border:1px solid #e5e7eb;color:#111827;">Lihat Sertifikat</a>
                                        @else
                                            <a href="{{ route('kursus.show', $kursus->id ?? 0) }}" class="view-detail-btn" style="display:block;text-align:center;">Lanjutkan Belajar</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-courses">
                                <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="60" cy="60" r="50" stroke="currentColor" stroke-width="2"/>
                                    <path d="M40 55h40M40 65h40M40 75h25" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <h3 style="margin-bottom: 0.5rem; color: #374151;">Anda belum terdaftar pada pelatihan apapun</h3>
                                <p>Coba telusuri kursus dan daftar untuk memulai.</p>
                            </div>
                        @endif
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
    