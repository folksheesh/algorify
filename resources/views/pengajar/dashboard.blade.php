@extends('layouts.template')

@section('title', 'Dashboard Pengajar - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            margin-top: 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .admin-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .admin-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.6;
        }
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card-modern {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .stat-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .stat-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon-wrapper svg {
            width: 36px;
            height: 36px;
        }
        .stat-info h3 {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0 0 0.5rem 0;
            font-weight: 500;
            line-height: 1;
        }
        .stat-info p {
            font-size: 2rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
            line-height: 1;
        }
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
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0;
        }
        .section-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0.25rem 0 0 0;
        }
        
        /* Kategori Cards */
        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .kategori-card {
            padding: 1.25rem;
            border-radius: 12px;
            background: #F8FAFC;
            transition: all 0.2s;
        }
        .kategori-card:hover {
            background: #F1F5F9;
            transform: translateY(-2px);
        }
        .kategori-label {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.85rem;
        }
        .kategori-label.programming {
            background: #EEF2FF;
            color: #3A6DFF;
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
            height: 6px;
            border-radius: 3px;
            margin-bottom: 0.85rem;
            background: #E2E8F0;
            overflow: hidden;
        }
        .kategori-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.5s ease;
        }
        .kategori-bar-fill.programming {
            background: linear-gradient(90deg, #3A6DFF, #3A6DFF);
        }
        .kategori-bar-fill.design {
            background: linear-gradient(90deg, #2563EB, #3B82F6);
        }
        .kategori-bar-fill.business {
            background: linear-gradient(90deg, #D97706, #F59E0B);
        }
        .kategori-bar-fill.marketing {
            background: linear-gradient(90deg, #DB2777, #EC4899);
        }
        .kategori-count {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748B;
        }
        
        /* Kursus Populer */
        .kursus-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .kursus-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            background: #F8FAFC;
            border-radius: 12px;
            gap: 1rem;
            transition: all 0.2s;
        }
        .kursus-item:hover {
            background: #F1F5F9;
            transform: translateX(4px);
        }
        .kursus-rank {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8125rem;
            font-weight: 700;
            flex-shrink: 0;
            color: white;
        }
        .kursus-rank.rank-1 {
            background: linear-gradient(135deg, #3A6DFF, #3A6DFF);
        }
        .kursus-rank.rank-2 {
            background: linear-gradient(135deg, #3A6DFF, #3A6DFF);
        }
        .kursus-rank.rank-3 {
            background: linear-gradient(135deg, #10B981, #34D399);
        }
        .kursus-rank.rank-4 {
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
        }
        .kursus-info {
            flex: 1;
        }
        .kursus-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.35rem 0;
        }
        .kursus-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8125rem;
            color: #94A3B8;
        }
        .kursus-meta .siswa {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .kursus-meta .rating {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: #F59E0B;
        }
        .kursus-completion {
            text-align: right;
        }
        .completion-value {
            font-size: 1.125rem;
            font-weight: 700;
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
            padding: 1.25rem;
            background: #F8FAFC;
            border-radius: 12px;
            transition: all 0.2s;
        }
        .performa-card:hover {
            background: #F1F5F9;
            transform: translateY(-2px);
        }
        .performa-month {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 1rem;
        }
        .performa-item {
            margin-bottom: 0.65rem;
        }
        .performa-item:last-child {
            margin-bottom: 0;
        }
        .performa-label {
            font-size: 0.75rem;
            color: #94A3B8;
            margin-bottom: 0.15rem;
        }
        .performa-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1E293B;
        }
        
        /* Responsive container padding */
        .dashboard-content-wrapper {
            padding: 0 2rem 2rem;
        }
        .dashboard-page-header {
            margin: 0 2rem;
        }
        
        /* Responsive Design */
        @media (max-width: 1280px) {
            .kategori-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .performa-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 1024px) {
            .stat-cards-grid {
                grid-template-columns: 1fr;
            }
            .admin-header {
                padding: 2rem 1.5rem;
            }
            .admin-header h1 {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content-wrapper {
                padding: 0 1rem 1rem;
            }
            .dashboard-page-header {
                margin: 0 1rem;
            }
            .kategori-grid {
                grid-template-columns: 1fr;
            }
            .performa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .section-card {
                padding: 1.25rem;
            }
            .stat-card-modern {
                padding: 1.25rem;
            }
            .admin-header {
                padding: 1.5rem 1rem;
                margin-top: 1rem;
            }
            .admin-header h1 {
                font-size: 1.5rem;
            }
            .admin-header p {
                font-size: 0.875rem;
            }
        }
        
        /* Tambahan responsive untuk mobile dengan hamburger menu */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }
        }
        
        @media (max-width: 640px) {
            .dashboard-content-wrapper {
                padding: 0 0.75rem 0.75rem;
            }
            .dashboard-page-header {
                margin: 0 0.75rem;
            }
            .stat-icon-wrapper {
                width: 60px;
                height: 60px;
            }
            .stat-icon-wrapper svg {
                width: 30px;
                height: 30px;
            }
            .stat-info p {
                font-size: 1.75rem;
            }
            .page-header {
                padding: 1rem;
            }
            .page-header h1 {
                font-size: 1.25rem;
            }
            .performa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Topbar Layout Adjustment */
        .dashboard-container.with-topbar {
            padding-top: 64px;
        }
        
        .dashboard-container.with-topbar .main-content {
            padding-top: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .dashboard-container.with-topbar .main-content {
                margin-left: 0;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar Pengajar --}}
    @include('components.topbar-pengajar')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header dashboard-page-header">
                <h1>Dashboard</h1>
            </div>
            
            <div class="dashboard-content-wrapper">
                <!-- Admin Header Banner -->
                <div class="admin-header">
                    <h1>Selamat datang kembali!</h1>
                    <p>Berikut ringkasan aktivitas Anda. Kelola kursus dan pantau perkembangan siswa dengan mudah.</p>
                </div>

                <!-- Stat Cards -->
                <div class="stat-cards-grid">
                    <a href="{{ route('admin.pelatihan.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" fill="white"/>
                                    <path d="M8 10H16M8 14H12" stroke="#3A6DFF" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <h3>Total Kursus</h3>
                                <p>{{ $totalKursus }}</p>
                            </div>
                        </div>
                    </a>

                    <div class="stat-card-modern">
                        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="9" cy="7" r="4" stroke="white" stroke-width="1.5"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Total Siswa</h3>
                            <p>{{ $totalSiswa }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kategori Kursus -->
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">Kategori Kursus</h2>
                            <p class="section-subtitle">Distribusi kursus berdasarkan kategori</p>
                        </div>
                    </div>
                    
                    <div class="kategori-grid">
                        @foreach($kategoriStats as $kategori)
                        <div class="kategori-card">
                            <span class="kategori-label {{ $kategori['slug'] }}">{{ $kategori['nama'] }}</span>
                            <div class="kategori-bar">
                                <div class="kategori-bar-fill {{ $kategori['slug'] }}" style="width: {{ $kategori['percentage'] }}%"></div>
                            </div>
                            <div class="kategori-count">{{ $kategori['total'] }} Kursus</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Kursus Populer -->
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">Kursus Populer</h2>
                            <p class="section-subtitle">Kursus dengan siswa terbanyak</p>
                        </div>
                    </div>
                    
                    <div class="kursus-list">
                        @forelse($kursusPopuler as $index => $kursus)
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
                                        {{ number_format($kursus->rating ?? 4.5 + (rand(-5, 5) / 10), 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="kursus-completion">
                                <div class="completion-value">{{ rand(65, 95) }}%</div>
                                <div class="completion-label">Completion</div>
                            </div>
                        </div>
                        @empty
                        <div class="kursus-item" style="justify-content: center; color: #94A3B8;">
                            <p>Belum ada kursus</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Performa Bulanan -->
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">Performa Bulanan</h2>
                            <p class="section-subtitle">Data 6 bulan terakhir</p>
                        </div>
                    </div>
                    
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
    
    {{-- Footer --}}
    @include('components.footer')
@endsection
