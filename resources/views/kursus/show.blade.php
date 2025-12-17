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
        <div class="dashboard-container with-topbar" style="font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
            @include('components.sidebar')
            <main class="main-content" style="background: #f8f9fa; min-height: 100vh;">
                <div class="detail-content" style="max-width:1100px;margin:0 auto;padding:40px 0;">
                    <h1 style="font-size:2rem;font-weight:700;margin-bottom:2rem;">{{ $kursus->judul }}</h1>
                    <div style="display:grid;grid-template-columns:1.1fr 1.2fr;gap:2.5rem;align-items:start;">
                        <div>
                            <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1.5rem;background:#fff;">
                                <img src="{{ $kursus->cover ? asset('storage/' . $kursus->cover) : asset('images/default-course.jpg') }}" alt="Cover" style="width:100%;height:220px;object-fit:cover;">
                            </div>
                            <div style="background:#fff;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1.5rem;">
                                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                                    <svg width="24" height="24" fill="none" stroke="#667eea" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 8h8v8H8z"/></svg>
                                    <div>
                                        <div style="font-weight:600;font-size:1rem;">{{ $kursus->modul->count() }} modul</div>
                                        <div style="font-size:0.95em;color:#64748B;">Dapatkan wawasan mendalam tentang fundamental desain UI/UX.</div>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                                    <svg width="24" height="24" fill="none" stroke="#667eea" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    <div>
                                        <div style="font-weight:600;font-size:1rem;">180 Hari waktu akses</div>
                                        <div style="font-size:0.95em;color:#64748B;">Deskripsi</div>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:1rem;">
                                    <svg width="24" height="24" fill="none" stroke="#667eea" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="4"/><path d="M8 12h8"/></svg>
                                    <div>
                                        <div style="font-weight:600;font-size:1rem;">Jadwal Fleksibel</div>
                                        <div style="font-size:0.95em;color:#64748B;">Belajar sesuai kecepatan Anda sendiri</div>
                                    </div>
                                </div>
                            </div>
                            <div style="background:#fff;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                                <div style="font-weight:600;font-size:1rem;margin-bottom:0.5rem;">Instruktur</div>
                                <div style="font-size:1.1em;font-weight:500;">{{ $kursus->user->name ?? 'Nama Instruktur' }}</div>
                                <div style="color:#64748B;font-size:0.95em;">{{ $kursus->user->profesi ?? '-' }}</div>
                                <div style="color:#64748B;font-size:0.95em;margin-top:0.5rem;">{{ $kursus->user->bio ?? '' }}</div>
                            </div>
                        </div>
                        <div>
                            <div style="background:#fff;border-radius:16px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:2rem;">
                                <div style="font-size:1.15rem;font-weight:600;margin-bottom:1rem;">Deskripsi Kursus</div>
                                <div style="font-size:1.05rem;color:#334155;line-height:1.7;">{{ $kursus->deskripsi }}</div>
                            </div>
                            <div style="text-align:center;">
                                <a href="{{ route('user.kursus.pembayaran', $kursus->id) }}" class="enroll-button" style="display:inline-block;background:#2563eb;color:#fff;font-weight:600;font-size:1.1rem;padding:16px 0;width:100%;border-radius:12px;text-decoration:none;box-shadow:0 2px 8px rgba(37,99,235,0.08);transition:background 0.2s;">Daftar Sekarang</a>
                            </div>
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
