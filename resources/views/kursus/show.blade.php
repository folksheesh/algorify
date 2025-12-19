@extends('layouts.template')

@section('title', 'Algorify - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kursus/show.css') }}">
    <style>
        .course-detail-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding:24px;
            padding-top: 0px
        }

        .course-title-main {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .course-main-section {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .course-image-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        .course-image-container img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
        }

        .learning-objectives-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .learning-objectives-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
        }

        .learning-objectives-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .learning-objectives-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: #374151;
            line-height: 1.6;
        }

        .learning-objectives-list li:last-child {
            margin-bottom: 0;
        }

        .check-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            color: #2563eb;
            margin-top: 2px;
        }

        .course-info-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .course-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .course-info-item .info-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .course-info-item .info-icon {
            width: 22px;
            height: 22px;
            color: #6366f1;
        }

        .course-info-item .info-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .course-info-item .info-description {
            font-size: 0.875rem;
            color: #64748b;
            padding-left: 30px;
        }

        .instructor-section {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .instructor-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .instructor-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .instructor-role {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.75rem;
        }

        .instructor-bio {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.6;
        }

        .course-highlight-row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: stretch;
            margin-bottom: 2rem;
        }

        .highlight-card {
            flex: 1 1 320px;
            min-width: 120px;
            min-height: 170px;
            display: flex;
            flex-direction: column;
        }

        .price-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            height: 100%;
        }

        .price-section .price-title {
            font-size: 0.95rem;
            color: #64748b;
        }

        .price-section .price-value {
            font-size: 2rem;
            font-weight: 700;
            color: #5D3FFF;
        }

        .price-section .price-desc {
            font-size: 0.95rem;
            color: #475569;
        }

        .price-section .price-note {
            font-size: 0.9rem;
            color: #64748b;
        }

        .enroll-section {
            text-align: center;
        }

        .enroll-btn {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 16px 80px;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }

        .enroll-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            color: #fff;
        }

        @media (max-width: 900px) {
            .course-main-section {
                grid-template-columns: 1fr;
            }

            .course-info-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .course-info-item .info-description {
                padding-left: 30px;
            }
        }

        @media (max-width: 576px) {
            .course-detail-wrapper {
                padding: 20px 16px;
            }

            .course-title-main {
                font-size: 1.35rem;
            }

            .enroll-btn {
                width: 100%;
                padding: 14px 24px;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')

    <div class="detail-container">
        <div class="dashboard-container with-topbar"
            style="font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
            @include('components.sidebar')
            <main class="main-content" style="background: #f8f9fa; min-height: 100vh;">
                @include('components.topbar-user')
                <div class="course-detail-wrapper">
                    {{-- Course Title --}}
                    <h1 class="course-title-main">{{ $kursus->judul }}</h1>

                    {{-- Main Section: Image + Learning Objectives --}}
                    <div class="course-main-section">
                        {{-- Course Image --}}
                        <div class="course-image-container">
                            @php
                                $thumbnailUrl = resolve_thumbnail_url(
                                    $kursus->thumbnail,
                                    asset('images/default-course.jpg'),
                                );
                            @endphp
                            <img src="{{ $thumbnailUrl }}" alt="{{ $kursus->judul }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/default-course.jpg') }}';">
                        </div>

                        {{-- Learning Objectives --}}
                        <div class="learning-objectives-card">
                            <h2 class="learning-objectives-title">Yang akan Anda pelajari</h2>
                            <ul class="learning-objectives-list">
                                @php
                                    // Parse the description to create learning objectives
                                    // Split by sentences and take the first few meaningful ones
                                    $description = $kursus->deskripsi;
                                    $sentences = preg_split('/(?<=[.!?])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);

                                    // Generate learning objectives from the description
                                    $objectives = [];
                                    foreach ($sentences as $sentence) {
                                        $sentence = trim($sentence);
                                        if (strlen($sentence) > 20 && count($objectives) < 4) {
                                            $objectives[] = $sentence;
                                        }
                                    }

                                    // If we don't have enough objectives, create some based on the course
if (count($objectives) < 2) {
    $objectives = [
        'Memahami konsep dasar dan fundamental dari ' . strtolower($kursus->judul),
        'Menerapkan pengetahuan dalam proyek nyata',
        'Mendapatkan keterampilan praktis yang relevan dengan industri',
        'Mengembangkan portfolio dan kemampuan profesional',
                                        ];
                                    }
                                @endphp

                                @foreach ($objectives as $objective)
                                    <li>
                                        <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $objective }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Course Info Row --}}
                    <div class="course-info-row">
                        {{-- Modul Count --}}
                        <div class="course-info-item">
                            <div class="info-header">
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <path d="M9 9h6v6H9z" />
                                </svg>
                                <span class="info-title">{{ $kursus->modul->count() }} modul</span>
                            </div>
                            <span class="info-description">Dapatkan wawasan mendalam tentang fundamental
                                {{ strtolower($kursus->judul) }}.</span>
                        </div>

                        {{-- Duration --}}
                        <div class="course-info-item">
                            <div class="info-header">
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span class="info-title">180 Hari waktu akses</span>
                            </div>
                            <span class="info-description">Akses materi kapan saja selama periode kursus.</span>
                        </div>

                        {{-- Flexible Schedule --}}
                        <div class="course-info-item">
                            <div class="info-header">
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                                <span class="info-title">Jadwal Fleksibel</span>
                            </div>
                            <span class="info-description">Belajar sesuai kecepatan Anda sendiri</span>
                        </div>
                    </div>


                    <div class="course-highlight-row">
                        <div class="instructor-section highlight-card">
                            <div class="instructor-label">Instruktur</div>
                            <div class="instructor-name">{{ $kursus->pengajar->name ?? 'Nama Instruktur' }}</div>
                            <div class="instructor-role">{{ $kursus->pengajar->profesi ?? 'Senior Instructor' }}</div>
                            <div class="instructor-bio">Instruktur berpengalaman dengan keahlian di bidang
                                {{ strtolower($kursus->judul) }} yang siap membimbing Anda mencapai tujuan pembelajaran.</div>
                        </div>
                        <div class="price-section highlight-card">
                            <div class="price-title">Ketentuan Harga</div>
                            <div class="price-value">
                                @if($kursus->harga == 0)
                                    Gratis
                                @else
                                    Rp{{ number_format($kursus->harga,0,',','.') }}
                                @endif
                            </div>
                            <div class="price-desc">
                                Harga berlaku untuk seluruh akses materi, modul, dan fitur kursus ini selama periode aktif.
                            </div>
                            <div class="price-note">Pembayaran dapat dilakukan melalui berbagai metode yang tersedia di halaman pembayaran.</div>
                        </div>
                    </div>

                    {{-- Enroll Button --}}
                    <div class="enroll-section">
                        <a href="{{ route('user.kursus.pembayaran', $kursus->id) }}" class="enroll-btn">Daftar Sekarang</a>
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
