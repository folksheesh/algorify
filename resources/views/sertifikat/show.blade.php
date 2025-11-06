@extends('layouts.template')

@section('title', 'Sertifikat Saya')

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content">
            <header class="main-header">
                <h1 class="section-title">Sertifikat Saya</h1>
                <p class="muted">Dapatkan sertifikat untuk setiap pelatihan yang telah Anda selesaikan</p>
            </header>

            <section style="max-width:900px;">
                <div style="display:flex;flex-direction:column;gap:24px;">
                    <div style="display:flex;gap:24px;align-items:flex-start;">
                        {{-- Card sertifikat --}}
                            @if(!empty($dbError) && $dbError)
                                <div class="alert alert-warning">Terjadi masalah koneksi database. Sertifikat tidak dapat ditampilkan saat ini.</div>
                            @elseif(empty($sertifikat))
                                <div class="alert alert-info">Sertifikat tidak ditemukan.</div>
                            @else
                        <div style="width:320px;border-radius:12px;overflow:hidden;background:#fff;border:1px solid #eef2f6;box-shadow:0 8px 24px rgba(15,23,42,0.04);">
                            <div style="height:140px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f3f5ff 0%, #fff 100%);">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C13.1046 2 14 2.89543 14 4C14 5.10457 13.1046 6 12 6C10.8954 6 10 5.10457 10 4C10 2.89543 10.8954 2 12 2Z" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 20C6 16 9 14 12 14C15 14 18 16 18 20" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div style="padding:16px;">
                                <div style="font-weight:600;margin-bottom:6px;">{{ $sertifikat->kursus->judul ?? ($sertifikat->judul ?? '—') }}</div>
                                <div class="muted" style="font-size:0.9rem;margin-bottom:12px;">{{ $sertifikat->kursus->pengajar->name ?? $sertifikat->user->name ?? '—' }}</div>

                                <div style="font-size:0.9rem;color:#64748b;margin-bottom:8px;">Tanggal Selesai: <span style="color:#111827;font-weight:600">{{ optional($sertifikat->tanggal_terbit)->format('d F Y') ?? '-' }}</span></div>
                                <div style="font-size:0.9rem;color:#64748b;margin-bottom:12px;">Nilai Akhir: <span style="color:#10b981;font-weight:700">{{ $sertifikat->nilai_akhir ?? ($sertifikat->nilai ?? '-') }}</span></div>

                                @if(!empty($sertifikat->file_path) && file_exists(public_path('storage/'.$sertifikat->file_path)))
                                    <a href="{{ asset('storage/'.$sertifikat->file_path) }}" class="btn-primary" style="display:inline-block;padding:8px 14px;border-radius:8px;text-decoration:none;color:#fff;">Download Sertifikat (PDF)</a>
                                @else
                                    <button class="btn-primary" disabled style="opacity:0.7">Sertifikat belum tersedia</button>
                                @endif
                                <div style="margin-top:10px">
                                    <a href="{{ route('sertifikat.verify.form', ['nomor' => $sertifikat->nomor_sertifikat]) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded text-sm">Verifikasi Sertifikat</a>
                                </div>
                            </div>
                        </div>

                        {{-- Info / cara mendapatkan sertifikat --}}
                        <div style="flex:1;">
                            <div style="background:#fff;border:1px solid #eef2f6;border-radius:12px;padding:18px;box-shadow:0 6px 18px rgba(15,23,42,0.04);">
                                <h3 style="margin:0 0 8px 0;font-size:16px;">Cara Mendapatkan Sertifikat</h3>
                                <ul style="margin:8px 0 0 0;padding-left:1.1rem;color:#475569;">
                                    <li style="margin-bottom:8px;">Selesaikan semua modul dalam pelatihan (100%)</li>
                                    <li style="margin-bottom:8px;">Lulus quiz final dengan nilai minimal 70</li>
                                    <li>Sertifikat dapat didownload dalam format PDF</li>
                                </ul>
                            </div>
                        </div>
                            @endif
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection
