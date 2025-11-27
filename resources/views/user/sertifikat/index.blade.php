@extends('layouts.template')

@section('title', 'Sertifikat Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <header class="main-header">
                <div class="search-container">
                    <input type="search" class="search-input" placeholder="Cari sertifikat saya..." aria-label="Cari sertifikat" />
                    <button type="submit" class="search-button" aria-label="Cari">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </button>
                </div>
            </header>
            <section class="hero-banner">
                <div class="hero-content">
                    <h1 class="hero-title">Sertifikat Saya</h1>
                    <p class="hero-description">Lihat dan kelola sertifikat yang telah Anda peroleh</p>
                </div>
            </section>
            <section class="stats-section">
                <div style="margin-top: 1.5rem;">
                    @if(isset($certificates) && $certificates->count() > 0)
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1rem;">
                            @foreach($certificates as $cert)
                                <div style="background:white; border-radius:12px; padding:1rem; box-shadow:0 8px 24px rgba(14,20,30,0.06); display:flex; flex-direction:column; gap:0.75rem;">
                                    <div style="display:flex; gap:1rem; align-items:center;">
                                        <div style="width:108px; height:108px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#f0f3ff,#eef9ff); border-radius:8px;">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"><path d="M12 2l3 6 6 .9-4.5 3.9L19 20 12 16.8 5 20l1.5-6.2L2 8.9 8 8 12 2z" fill="#3b82f6"/></svg>
                                        </div>
                                        <div style="flex:1">
                                            <div style="font-weight:700; color:#0f172a; font-size:1.05rem;">{{ $cert->judul ?? ($cert->kursus->judul ?? 'Sertifikat') }}</div>
                                            <div style="color:#64748B; font-size:0.9rem; margin-top:0.25rem;">{{ $cert->kursus->pengajar->name ?? '-' }} • {{ $cert->tanggal_terbit ? $cert->tanggal_terbit->format('d M Y') : '-' }}</div>
                                            <div style="margin-top:0.5rem; display:flex; align-items:center; gap:0.75rem;">
                                                <div style="background:#EEF2FF; padding:0.5rem 0.75rem; border-radius:999px; font-weight:600; color:#1E293B; font-size:0.85rem;">Sertifikat Tersedia</div>
                                                <div style="color:#22c55e; font-weight:700; font-size:0.9rem;">{{ $cert->status_sertifikat ?? 'active' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display:flex; justify-content:space-between; align-items:center; gap:0.75rem; margin-top:auto;">
                                        <div style="color:#475569; font-size:0.9rem;">Nomor: <strong>{{ $cert->nomor_sertifikat }}</strong></div>
                                        <div style="display:flex; gap:0.5rem;">
                                            <a href="{{ route('user.sertifikat.show', $cert->id) }}" class="btn btn-secondary" style="padding:0.5rem 0.75rem;">Lihat</a>
                                            @if($cert->file_path)
                                                <a href="{{ asset('storage/' . $cert->file_path) }}" download class="btn btn-primary" style="padding:0.5rem 0.75rem;">Download Sertifikat (PDF)</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top:1rem;">{{ $certificates->links() }}</div>
                    @else
                        <div style="padding: 2rem; background: white; border-radius: 12px; margin-top: 2rem;">
                            <h2 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: #1E293B;">Belum Ada Sertifikat</h2>
                            <p style="color: #64748B;">Kamu belum menyelesaikan kursus dengan standar penilaian yang menghasilkan sertifikat. Selesaikan pelatihan untuk mendapatkan sertifikat.</p>
                        </div>
                    @endif

                    <!-- Informational box similar to screenshot -->
                    <div style="margin-top:1.5rem; background:linear-gradient(90deg, #EFF6FF, #FDF2F8); border-radius:12px; padding:1rem; border:1px solid #E6EEF9; display:flex; gap:1rem; align-items:flex-start;">
                        <div style="width:48px; height:48px; display:flex; align-items:center; justify-content:center; background:#fff;border-radius:8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 2l3 6 6 .9-4.5 3.9L19 20 12 16.8 5 20l1.5-6.2L2 8.9 8 8 12 2z" fill="#3b82f6"/></svg>
                        </div>
                        <div>
                            <div style="font-weight:700; color:#0f172a;">Cara Mendapatkan Sertifikat</div>
                            <ul style="margin:0.5rem 0 0 0; padding-left:1.25rem; color:#475569;">
                                <li>Selesaikan semua modul dalam pelatihan (100%)</li>
                                <li>Lulus kuis akhir dengan nilai minimal sesuai ketentuan</li>
                                <li>Sertifikat dapat diunduh dalam format PDF</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
