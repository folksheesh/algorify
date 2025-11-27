@extends('layouts.template')

@section('title', 'Lihat Sertifikat - Algorify')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content">
            <div style="padding:2rem;">
                <div style="background:white; padding:1.25rem; border-radius:12px; box-shadow:0 8px 24px rgba(14,20,30,0.06);">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
                        <div>
                            <h1 style="margin:0;">{{ $certificate->judul ?? ($certificate->kursus->judul ?? 'Sertifikat') }}</h1>
                            <div style="color:#64748B; margin-top:0.25rem;">Nomor: {{ $certificate->nomor_sertifikat }} • {{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d M Y') : '-' }}</div>
                        </div>

                        <div style="display:flex; gap:0.5rem;">
                            @if($certificate->file_path)
                                <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-primary">Buka di tab baru</a>
                                <a href="{{ asset('storage/' . $certificate->file_path) }}" download class="btn btn-secondary">Unduh</a>
                            @endif
                        </div>
                    </div>

                    <div style="margin-top:1rem; display:flex; gap:1rem;">
                        <div style="flex:1; background:#F8FAFC; padding:1rem; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            @if($certificate->file_path)
                                <img src="{{ asset('storage/' . $certificate->file_path) }}" alt="Sertifikat" style="max-width:100%; max-height:640px; object-fit:contain;">
                            @else
                                <div style="color:#475569; font-weight:600;">Preview tidak tersedia</div>
                            @endif
                        </div>

                        <aside style="width:320px; display:flex; flex-direction:column; gap:0.75rem;">
                            <div style="background:white; padding:1rem; border-radius:8px; border:1px solid #E6EEF9;">
                                <div style="font-weight:700;">Detail Sertifikat</div>
                                <div style="color:#64748B; margin-top:0.5rem;">Pelatihan: {{ $certificate->kursus->judul ?? '-' }}</div>
                                <div style="color:#64748B;">Tanggal Terbit: {{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d M Y') : '-' }}</div>
                                <div style="color:#64748B;">Nomor: {{ $certificate->nomor_sertifikat }}</div>
                                <div style="margin-top:0.5rem;">Status: <strong>{{ ucfirst($certificate->status_sertifikat) }}</strong></div>
                            </div>

                            <div style="background:white; padding:1rem; border-radius:8px; border:1px solid #E6EEF9;">
                                <div style="font-weight:700;">Tindakan</div>
                                <div style="display:flex; gap:0.5rem; margin-top:0.75rem;">
                                    <a href="{{ route('user.sertifikat.index') }}" class="btn btn-secondary">Kembali</a>
                                    @if($certificate->file_path)
                                        <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-primary">Buka</a>
                                    @endif
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </main>

    </div>
@endsection
