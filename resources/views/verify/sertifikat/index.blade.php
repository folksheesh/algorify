@extends('layouts.template')

@section('title', 'Verifikasi Sertifikat - Algorify')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/verify-sertifikat.css') }}">
@endpush

@section('content')
    <div class="verify-page">
        <div class="verify-wrapper">
            <header class="verify-header">
                <div class="logo-row">
                    {{-- Logo Icon using actual logo image --}}
                    <img src="{{ asset('template/img/icon-logo.png') }}" alt="Algorify Logo" class="logo-icon" style="width: 36px; height: 36px; object-fit: contain;">
                    <span class="logo-text">Algorify</span>
                </div>
                
                {{-- Back Button --}}
                <a href="{{ route('user.sertifikat.index') }}" class="btn-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
                <h1>Verifikasi Sertifikat</h1>
                <p class="subtitle">Masukkan nomor sertifikat untuk memverifikasi keasliannya</p>
            </header>

            <section class="verify-card">
                <form class="verify-form" method="POST" action="{{ route('verify.sertifikat.verify') }}">
                    @csrf
                    <div class="input-wrapper">
                        <input
                            type="text"
                            name="nomor"
                            id="nomor"
                            value="{{ old('nomor', $query ?? '') }}"
                            placeholder="Contoh: CERT-ALG-2025-001234"
                            autocomplete="off"
                        >
                        <button type="submit" class="btn-submit">
                            {{-- Search Icon --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                            Verifikasi
                        </button>
                    </div>
                    @error('nomor')
                        <div class="alert error">{{ $message }}</div>
                    @enderror
                    <p class="realtime-note">
                        {{-- Checkmark Circle Icon --}}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Verifikasi dilakukan secara real-time dari database Algorify
                    </p>
                </form>

                @if(isset($query) && $query !== '')
                    @if($result)
                        <div class="result-card success">
                            <div class="result-header">
                                <div class="status-dot success"></div>
                                <div>
                                    <p class="result-label">Sertifikat Valid</p>
                                    <p class="result-number">{{ $result->nomor_sertifikat }}</p>
                                </div>
                            </div>
                            <div class="result-grid">
                                <div>
                                    <p class="result-title">Nama Pemegang</p>
                                    <p class="result-value">{{ $result->user->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="result-title">Pelatihan</p>
                                    <p class="result-value">{{ $result->kursus->judul ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="result-title">Tanggal Terbit</p>
                                    <p class="result-value">{{ \Carbon\Carbon::parse($result->created_at)->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="result-title">Nilai Akhir</p>
                                    <p class="result-value">{{ $enrollment->nilai_akhir ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="result-card error">
                            <div class="result-header">
                                <div class="status-dot error"></div>
                                <div>
                                    <p class="result-label">Sertifikat Tidak Ditemukan</p>
                                    <p class="result-number">{{ $query }}</p>
                                </div>
                            </div>
                            <p class="result-value" style="margin-top: 0.5rem;">Periksa kembali nomor sertifikat atau hubungi admin.</p>
                        </div>
                    @endif
                @endif
            </section>

            <section class="guide-card">
                <div class="guide-header">
                    {{-- Checkmark Circle Icon --}}
                    <svg class="guide-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <h3>Cara Verifikasi Sertifikat</h3>
                </div>
                <ol class="guide-list">
                    <li>
                        <span class="list-number">1.</span>
                        <span class="list-text">Dapatkan nomor sertifikat dari sertifikat fisik atau digital</span>
                    </li>
                    <li>
                        <span class="list-number">2.</span>
                        <span class="list-text">Masukkan nomor sertifikat pada kolom di atas</span>
                    </li>
                    <li>
                        <span class="list-number">3.</span>
                        <span class="list-text">Klik tombol "Verifikasi" atau tekan Enter</span>
                    </li>
                    <li>
                        <span class="list-number">4.</span>
                        <span class="list-text">Sistem akan menampilkan status dan detail sertifikat</span>
                    </li>
                </ol>
                <p class="note">
                    <strong>Catatan:</strong><br>
                    Sertifikat yang valid akan menampilkan detail lengkap seperti nama penerima, pelatihan, tanggal penyelesaian, dan tanda tangan digital.
                </p>
            </section>
        </div>
    </div>
@endsection
