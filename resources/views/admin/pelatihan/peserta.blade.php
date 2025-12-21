@extends('layouts.template')

@section('title', 'Peserta Kursus - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-peserta.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-left">
                        <a href="{{ route('admin.pelatihan.show', $kursus->slug) }}" class="back-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="page-title">Detail Peserta</h1>
                            <p class="page-subtitle">Daftar peserta dan nilai kursus</p>
                        </div>
                    </div>
                </div>
                
                <!-- Course Header Banner -->
                <div class="course-header-banner">
                    <h2>{{ $kursus->judul }}</h2>
                    <div class="course-stats">
                        <div class="course-stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                            <span>{{ $pesertaData->count() }} Peserta Terdaftar</span>
                        </div>
                        <div class="course-stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <span>{{ $ujianList->count() }} Ujian/Quiz</span>
                        </div>
                    </div>
                </div>
                
                <!-- Table Card -->
                <div class="table-card">
                    <div class="table-header">
                        <div>
                            <h2 class="table-title">Daftar Peserta</h2>
                            <p class="table-count">Menampilkan {{ $pesertaData->count() }} peserta</p>
                        </div>
                    </div>
                    
                    @if($pesertaData->count() > 0)
                        <table class="peserta-table">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Progress</th>
                                    @foreach($ujianList as $ujian)
                                        <th style="text-align: center;">
                                            {{ Str::limit($ujian->judul, 15) }}
                                            <br>
                                            <span style="font-weight: 400; font-size: 0.7rem; text-transform: capitalize;">{{ $ujian->tipe }}</span>
                                        </th>
                                    @endforeach
                                    <th style="text-align: center;">Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaData as $peserta)
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                @if($peserta['foto_profil'])
                                                    <img src="{{ asset('storage/' . $peserta['foto_profil']) }}" alt="{{ $peserta['name'] }}" class="user-avatar">
                                                @else
                                                    <div class="user-avatar">
                                                        {{ strtoupper(substr($peserta['name'], 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="user-info">
                                                    <h4>{{ $peserta['name'] }}</h4>
                                                    <p>{{ $peserta['email'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="progress-cell">
                                            <div class="progress-bar-wrapper">
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: {{ $peserta['progress'] ?? 0 }}%"></div>
                                                </div>
                                                <span class="progress-text">{{ $peserta['progress'] ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        @foreach($peserta['nilai_list'] as $nilai)
                                            <td style="text-align: center;">
                                                @if($nilai['nilai'] !== null)
                                                    @php
                                                        $nilaiClass = 'pending';
                                                        if ($nilai['nilai'] >= 85) $nilaiClass = 'excellent';
                                                        elseif ($nilai['nilai'] >= 70) $nilaiClass = 'good';
                                                        elseif ($nilai['nilai'] >= 50) $nilaiClass = 'average';
                                                        else $nilaiClass = 'poor';
                                                    @endphp
                                                    <span class="nilai-badge {{ $nilaiClass }}">{{ number_format($nilai['nilai'], 0) }}</span>
                                                @else
                                                    <span class="nilai-badge pending">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td style="text-align: center;">
                                            @if($peserta['rata_rata'] !== null)
                                                @php
                                                    $avgClass = 'pending';
                                                    if ($peserta['rata_rata'] >= 85) $avgClass = 'excellent';
                                                    elseif ($peserta['rata_rata'] >= 70) $avgClass = 'good';
                                                    elseif ($peserta['rata_rata'] >= 50) $avgClass = 'average';
                                                    else $avgClass = 'poor';
                                                @endphp
                                                <span class="nilai-badge {{ $avgClass }} rata-rata">{{ number_format($peserta['rata_rata'], 1) }}</span>
                                            @else
                                                <span class="nilai-badge pending rata-rata">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <h3>Belum Ada Peserta</h3>
                            <p>Belum ada peserta yang terdaftar di kursus ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection
