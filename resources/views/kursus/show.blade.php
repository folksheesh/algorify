@extends('layouts.template')

@section('title', 'Algorify - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kursus/show.css') }}">
    
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
                            
                            {{-- Enroll Button - Moved here for compact layout --}}
                            <div class="enroll-section-inline" style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #e5e7eb; text-align: center;">
                                <a href="{{ route('user.kursus.pembayaran', $kursus->slug) }}" class="enroll-btn" style="width: 90%; text-align: center; display: inline-block; padding: 12px 24px; font-size: 0.95rem; border-radius: 10px;">Daftar Sekarang</a>
                            </div>
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
                                @php
                                    $durasiHari = ($kursus->durasi && $kursus->durasi > 0) ? ($kursus->durasi * 7) : 180;
                                @endphp
                                <span class="info-title">{{ $durasiHari }} Hari waktu akses</span>
                            </div>
                            <span class="info-description">Akses materi kapan saja selama periode kursus.</span>
                        </div>

                        {{-- Course Type --}}
                        <div class="course-info-item">
                            <div class="info-header">
                                @php
                                    $tipeKursus = $kursus->tipe_kursus ?? 'online';
                                    $tipeLabel = match($tipeKursus) {
                                        'online' => 'Online',
                                        'offline' => 'Offline',
                                        'hybrid' => 'Hybrid',
                                        default => 'Online'
                                    };
                                    $tipeDesc = match($tipeKursus) {
                                        'online' => 'Belajar dari mana saja secara daring',
                                        'offline' => 'Pembelajaran tatap muka langsung',
                                        'hybrid' => 'Kombinasi online dan tatap muka',
                                        default => 'Belajar dari mana saja secara daring'
                                    };
                                @endphp
                                @if($tipeKursus === 'online')
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <path d="M8 21h8M12 17v4" />
                                </svg>
                                @elseif($tipeKursus === 'offline')
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6l9-4 9 4v12l-9 4-9-4z" />
                                    <path d="M3 6l9 4 9-4" />
                                    <path d="M12 10v12" />
                                </svg>
                                @else
                                <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <path d="M8 21h8M12 17v4" />
                                    <path d="M2 11h20" />
                                </svg>
                                @endif
                                <span class="info-title">Tipe: {{ $tipeLabel }}</span>
                            </div>
                            <span class="info-description">{{ $tipeDesc }}</span>
                        </div>
                    </div>


                    <div class="course-highlight-row">
                        <div class="instructor-section highlight-card">
                            <div class="instructor-label">Pengajar</div>
                            <div class="instructor-name">{{ $kursus->user->name ?? $kursus->pengajar ?? 'Nama Pengajar' }}</div>
                            <div class="instructor-role">{{ $kursus->user->profesi ?? 'Pengajar Profesional' }}</div>
                            <div class="instructor-bio">Pengajar berpengalaman dengan keahlian di bidang
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
