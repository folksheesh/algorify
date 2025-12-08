@extends('layouts.template')

@section('title', 'Peserta Kursus - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #F1F5F9;
            color: #64748B;
            text-decoration: none;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: #E2E8F0;
            color: #1E293B;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }
        .page-subtitle {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0.25rem 0 0 0;
        }
        
        /* Course Header Banner */
        .course-header-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .course-header-banner h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }
        .course-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }
        .course-stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            opacity: 0.95;
        }
        
        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .table-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0;
        }
        .table-count {
            font-size: 0.875rem;
            color: #64748B;
        }
        
        /* Custom Table */
        .peserta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .peserta-table th {
            background: #F8FAFC;
            padding: 1rem;
            text-align: left;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #E2E8F0;
        }
        .peserta-table td {
            padding: 1rem;
            border-bottom: 1px solid #F1F5F9;
            font-size: 0.9375rem;
            color: #1E293B;
            vertical-align: middle;
        }
        .peserta-table tr:hover td {
            background: #FAFAFA;
        }
        .peserta-table tr:last-child td {
            border-bottom: none;
        }
        
        /* User Cell */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        .user-info h4 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.15rem 0;
        }
        .user-info p {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0;
        }
        
        /* Nilai Badge */
        .nilai-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.85rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            min-width: 50px;
        }
        .nilai-badge.excellent {
            background: #DCFCE7;
            color: #16A34A;
        }
        .nilai-badge.good {
            background: #DBEAFE;
            color: #2563EB;
        }
        .nilai-badge.average {
            background: #FEF3C7;
            color: #D97706;
        }
        .nilai-badge.poor {
            background: #FEE2E2;
            color: #DC2626;
        }
        .nilai-badge.pending {
            background: #F1F5F9;
            color: #94A3B8;
        }
        
        /* Progress Bar */
        .progress-cell {
            min-width: 120px;
        }
        .progress-bar-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .progress-bar {
            flex: 1;
            height: 8px;
            background: #E2E8F0;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .progress-text {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748B;
            min-width: 40px;
        }
        
        /* Average Cell */
        .rata-rata {
            font-size: 1rem;
            font-weight: 700;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-state svg {
            width: 80px;
            height: 80px;
            color: #CBD5E1;
            margin-bottom: 1rem;
        }
        .empty-state h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #64748B;
            margin: 0 0 0.5rem 0;
        }
        .empty-state p {
            font-size: 0.875rem;
            color: #94A3B8;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .peserta-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .course-stats {
                flex-wrap: wrap;
                gap: 1rem;
            }
            .table-card {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-left">
                        <a href="{{ route('admin.pelatihan.show', $kursus->id) }}" class="back-btn">
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
