@extends('layouts.template')

@section('title', 'Dashboard Pengajar - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .dashboard-header {
            margin-bottom: 1.5rem;
        }
        .dashboard-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .dashboard-header p {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0;
        }
        
        /* Stat Cards */
        .stat-cards-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-width: 200px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon.purple {
            background: #EEF2FF;
        }
        .stat-icon.purple svg {
            color: #6366F1;
        }
        .stat-icon.orange {
            background: #FFF7ED;
        }
        .stat-icon.orange svg {
            color: #F97316;
        }
        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
        .stat-content {
            flex: 1;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1E293B;
            line-height: 1.2;
        }
        .stat-label {
            font-size: 0.8125rem;
            color: #64748B;
            margin-top: 0.25rem;
        }
        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: #10B981;
            margin-left: auto;
            align-self: flex-start;
        }
        .stat-change svg {
            width: 14px;
            height: 14px;
        }
        .stat-sublabel {
            font-size: 0.75rem;
            color: #94A3B8;
            margin-top: 0.125rem;
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .section-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0 0 1.25rem 0;
        }
        
        /* Kategori Cards */
        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .kategori-card {
            padding: 1rem;
            border-radius: 12px;
            background: #FAFAFA;
        }
        .kategori-label {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        .kategori-label.programming {
            background: #EEF2FF;
            color: #6366F1;
        }
        .kategori-label.design {
            background: #DBEAFE;
            color: #2563EB;
        }
        .kategori-label.business {
            background: #FEF3C7;
            color: #D97706;
        }
        .kategori-label.marketing {
            background: #FCE7F3;
            color: #DB2777;
        }
        .kategori-bar {
            height: 4px;
            border-radius: 2px;
            margin-bottom: 0.75rem;
        }
        .kategori-bar.programming {
            background: #6366F1;
            width: 100%;
        }
        .kategori-bar.design {
            background: #2563EB;
            width: 50%;
        }
        .kategori-bar.business {
            background: #D97706;
            width: 42%;
        }
        .kategori-bar.marketing {
            background: #DB2777;
            width: 8%;
        }
        .kategori-count {
            font-size: 0.8125rem;
            color: #64748B;
        }
        
        /* Kursus Populer */
        .kursus-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .kursus-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #FAFAFA;
            border-radius: 12px;
            gap: 1rem;
        }
        .kursus-rank {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        .kursus-rank.rank-1 {
            background: #6366F1;
            color: white;
        }
        .kursus-rank.rank-2 {
            background: #8B5CF6;
            color: white;
        }
        .kursus-rank.rank-3 {
            background: #10B981;
            color: white;
        }
        .kursus-rank.rank-4 {
            background: #3B82F6;
            color: white;
        }
        .kursus-info {
            flex: 1;
        }
        .kursus-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .kursus-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.75rem;
            color: #94A3B8;
        }
        .kursus-meta .siswa {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .kursus-meta .rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #F59E0B;
        }
        .kursus-completion {
            text-align: right;
        }
        .completion-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1E293B;
        }
        .completion-label {
            font-size: 0.75rem;
            color: #94A3B8;
        }
        
        /* Performa Bulanan */
        .performa-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
        }
        .performa-card {
            padding: 1rem;
            background: #FAFAFA;
            border-radius: 12px;
        }
        .performa-month {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.75rem;
        }
        .performa-item {
            margin-bottom: 0.5rem;
        }
        .performa-item:last-child {
            margin-bottom: 0;
        }
        .performa-label {
            font-size: 0.75rem;
            color: #94A3B8;
        }
        .performa-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1E293B;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .kategori-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .performa-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .stat-cards-row {
                flex-direction: column;
            }
            .kategori-grid {
                grid-template-columns: 1fr;
            }
            .performa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Header -->
                <div class="dashboard-header">
                    <h1>Dashboard</h1>
                    <p>Selamat datang kembali! Berikut ringkasan aktivitas Anda.</p>
                </div>
                
                <!-- Stat Cards -->
                <div class="stat-cards-row">
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $totalKursus }}</div>
                            <div class="stat-label">Total Kursus</div>
                            <div class="stat-sublabel">dari bulan lalu</div>
                        </div>
                        <div class="stat-change">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 15l-6-6-6 6"/>
                            </svg>
                            +12%
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $totalSiswa }}</div>
                            <div class="stat-label">Total Siswa</div>
                            <div class="stat-sublabel">dari bulan lalu</div>
                        </div>
                        <div class="stat-change">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 15l-6-6-6 6"/>
                            </svg>
                            +23%
                        </div>
                    </div>
                </div>
                
                <!-- Kategori Kursus -->
                <div class="section-card">
                    <h2 class="section-title">Kategori Kursus</h2>
                    <p class="section-subtitle">Distribusi kursus berdasarkan kategori</p>
                    
                    <div class="kategori-grid">
                        @foreach($kategoriStats as $kategori)
                        <div class="kategori-card">
                            <span class="kategori-label {{ strtolower($kategori['slug']) }}">{{ $kategori['nama'] }}</span>
                            <div class="kategori-bar {{ strtolower($kategori['slug']) }}" style="width: {{ $kategori['percentage'] }}%"></div>
                            <div class="kategori-count">{{ $kategori['total'] }} Kursus</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Kursus Populer -->
                <div class="section-card">
                    <h2 class="section-title">Kursus Populer</h2>
                    <p class="section-subtitle">Kursus dengan siswa terbanyak</p>
                    
                    <div class="kursus-list">
                        @foreach($kursusPopuler as $index => $kursus)
                        <div class="kursus-item">
                            <div class="kursus-rank rank-{{ $index + 1 }}">#{{ $index + 1 }}</div>
                            <div class="kursus-info">
                                <h4 class="kursus-name">{{ $kursus->judul }}</h4>
                                <div class="kursus-meta">
                                    <span class="siswa">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                        </svg>
                                        {{ $kursus->enrollments_count }} siswa
                                    </span>
                                    <span class="rating">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        {{ number_format($kursus->rating ?? 4.5, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="kursus-completion">
                                <div class="completion-value">{{ $kursus->completion_rate ?? rand(65, 95) }}%</div>
                                <div class="completion-label">Completion</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Performa Bulanan -->
                <div class="section-card">
                    <h2 class="section-title">Performa Bulanan</h2>
                    <p class="section-subtitle">Data 6 bulan terakhir</p>
                    
                    <div class="performa-grid">
                        @foreach($performaBulanan as $bulan)
                        <div class="performa-card">
                            <div class="performa-month">{{ $bulan['nama'] }}</div>
                            <div class="performa-item">
                                <div class="performa-label">Siswa</div>
                                <div class="performa-value">{{ $bulan['siswa'] }}</div>
                            </div>
                            <div class="performa-item">
                                <div class="performa-label">Kursus</div>
                                <div class="performa-value">{{ $bulan['kursus'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
