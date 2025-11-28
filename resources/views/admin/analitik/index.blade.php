@extends('layouts.template')

@section('title', 'Analitik - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
        }
        .stat-label {
            color: #64748B;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0.5rem 0 0.75rem 0;
        }
        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
            color: #10b981;
        }
        .info-banner {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .info-banner-icon {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem;
            border-radius: 8px;
        }
        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #E2E8F0;
        }
        .table-header h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background: #F8FAFC;
            padding: 0.875rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E2E8F0;
            font-size: 0.875rem;
            color: #1E293B;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #E2E8F0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }
        .status-badge {
            display: inline-flex;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.selesai {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge.berlangsung {
            background: #DBEAFE;
            color: #2563EB;
        }
        .nilai-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
        }
        .nilai-badge.high {
            background: #D1FAE5;
            color: #059669;
        }
        .nilai-badge.medium {
            background: #FEF3C7;
            color: #D97706;
        }
        .distribusi-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .distribusi-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .distribusi-card h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .distribusi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .distribusi-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        .distribusi-badge {
            background: #1E293B;
            color: white;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .search-export-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .search-box {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .btn-export {
            padding: 0.75rem 1.5rem;
            background: #1E293B;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .action-btn {
            padding: 0.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #64748B;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
        }
        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .modal-header p {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0;
        }
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #64748B;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .modal-close:hover {
            background: #F1F5F9;
            color: #1E293B;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .detail-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .detail-label {
            font-size: 0.75rem;
            color: #64748B;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            font-size: 0.875rem;
            color: #1E293B;
            font-weight: 500;
        }
        .nilai-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E2E8F0;
        }
        .nilai-section h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 1rem;
        }
        .nilai-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #F8FAFC;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }
        .nilai-item:last-child {
            margin-bottom: 0;
        }
        .nilai-label {
            font-size: 0.875rem;
            color: #64748B;
        }
        .nilai-score {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
        }
        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #E2E8F0;
            display: flex;
            gap: 0.75rem;
        }
        .btn-modal {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-modal.primary {
            background: #1E293B;
            color: white;
        }
        .btn-modal.secondary {
            background: white;
            color: #1E293B;
            border: 1px solid #E2E8F0;
        }
        .btn-modal:hover {
            opacity: 0.9;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10000;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            max-width: 380px;
            border-left: 4px solid #EF4444;
        }
        .toast-notification.active {
            transform: translateX(0);
        }
        .toast-notification.success {
            border-left-color: #10B981;
        }
        .toast-notification.success .toast-icon {
            color: #10B981;
        }
        .toast-notification.error {
            border-left-color: #EF4444;
        }
        .toast-notification.error .toast-icon {
            color: #EF4444;
        }
        .toast-notification.warning {
            border-left-color: #F59E0B;
        }
        .toast-notification.warning .toast-icon {
            color: #F59E0B;
        }
        .toast-notification.info {
            border-left-color: #3B82F6;
        }
        .toast-notification.info .toast-icon {
            color: #3B82F6;
        }
        .toast-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: #EF4444;
        }
        .toast-content {
            flex: 1;
        }
        .toast-title {
            font-weight: 600;
            font-size: 14px;
            color: #1F2937;
            margin-bottom: 2px;
        }
        .toast-message {
            font-size: 13px;
            color: #6B7280;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <section class="hero-banner" style="margin-bottom: 2rem;">
                    <div class="hero-content">
                        <h1 class="hero-title">Analitik</h1>
                        <p class="hero-description">Dashboard analitik dan statistik pelatihan</p>
                    </div>
                </section>

                <div class="info-banner">
                    <div class="info-banner-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="info-banner-content">
                        <h3>Data real-time - Terakhir diperbarui: {{ now()->format('d M Y, H.i') }}</h3>
                        <p>Live Updates</p>
                    </div>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FEF3C7;">
                            <svg viewBox="0 0 24 24" fill="#D97706" width="24" height="24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Pendapatan</div>
                            <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="stat-change">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tingkat keberhasilan {{ $tingkatKeberhasilan }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: #DBEAFE;">
                            <svg viewBox="0 0 24 24" fill="#3B82F6" width="24" height="24">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Transaksi</div>
                            <div class="stat-value">{{ $totalTransaksi }}</div>
                            <div class="stat-change">
                                <span style="color: #10b981;">{{ $successCount ?? 0 }} Lunas</span>
                                <span style="color: #f59e0b; margin-left: 0.5rem;">{{ $pendingCount ?? 0 }} Pending</span>
                                @if(isset($failedCount) && $failedCount > 0)
                                <span style="color: #ef4444; margin-left: 0.5rem;">{{ $failedCount }} Gagal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Grafik Pendapatan</h3>
                    <div style="height: 200px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                @if(isset($topKursus))
                <div class="table-container">
                    <div class="table-header">
                        <h2>Top Kursus Berdasarkan Pendapatan</h2>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kursus</th>
                                <th>Peserta</th>
                                <th>Kapasitas</th>
                                <th>Fill rate</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topKursus as $kursus)
                            <tr>
                                <td>{{ $kursus->no }}</td>
                                <td style="font-weight: 500;">{{ $kursus->nama }}</td>
                                <td>{{ $kursus->peserta }}</td>
                                <td>{{ $kursus->kapasitas }}</td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $kursus->fill_rate }}%;"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #64748B;">{{ $kursus->fill_rate }}%</span>
                                </td>
                                <td style="font-weight: 600;">Rp {{ number_format($kursus->pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if(isset($distribusiProfesi) || isset($distribusiLokasi))
                <div class="distribusi-section">
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Umur
                        </h3>
                        <div style="height: 150px; background: #F8FAFC; border-radius: 8px; margin-bottom: 1rem;"></div>
                        <div style="color: #64748B; font-size: 0.75rem; margin-bottom: 0.5rem;">Kelompok usia peserta</div>
                        <div class="distribusi-item">
                            <span class="distribusi-label">20-25 tahun</span>
                            <span style="font-size: 0.75rem; color: #64748B;">0 peserta</span>
                        </div>
                        <div class="distribusi-item">
                            <span class="distribusi-label">26-30 tahun</span>
                            <span style="font-size: 0.75rem; color: #64748B;">0 peserta</span>
                        </div>
                        <div class="distribusi-item" style="border: none;">
                            <span class="distribusi-label">31-35 tahun</span>
                            <span style="font-size: 0.75rem; color: #64748B;">0 peserta</span>
                        </div>
                    </div>

                    @if(isset($distribusiProfesi))
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Profesi
                        </h3>
                        <div style="color: #64748B; font-size: 0.75rem; margin-bottom: 1rem;">Jabatan peserta</div>
                        @foreach($distribusiProfesi as $profesi)
                        <div class="distribusi-item">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #3B82F6;"></div>
                                    <span style="font-size: 0.875rem;">{{ $profesi->profesi }}</span>
                                </div>
                                <div style="width: 100%; background: #E2E8F0; height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ $profesi->percentage }}%; height: 100%; background: linear-gradient(90deg, #3B82F6, #2563EB);"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-left: 1rem;">
                                <span class="distribusi-badge">{{ $profesi->jumlah }} peserta</span>
                                <span style="font-size: 0.75rem; color: #64748B;">{{ $profesi->percentage }}% dari total</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(isset($distribusiLokasi))
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Lokasi
                        </h3>
                        <div style="color: #64748B; font-size: 0.75rem; margin-bottom: 1rem;">Kota asal peserta</div>
                        <div style="height: 120px; background: #F8FAFC; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 100px; height: 100px; background: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">Jakarta</div>
                        </div>
                        @foreach($distribusiLokasi as $lokasi)
                        <div class="distribusi-item">
                            <div class="distribusi-label">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $lokasi->lokasi }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span class="distribusi-badge">{{ $lokasi->jumlah }} peserta</span>
                                <span style="font-size: 0.75rem; color: #64748B;">{{ $lokasi->percentage }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                @if(isset($students))
                <div class="table-container">
                    <div class="table-header">
                        <h2>Data Nilai Peserta</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="search-export-bar">
                            <input type="text" class="search-box" placeholder="Cari nama, email, atau ID peserta..." id="searchStudent">
                            <select class="filter-select" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="selesai">Selesai</option>
                                <option value="berlangsung">Berlangsung</option>
                            </select>
                            <select class="filter-select" id="filterPelatihan">
                                <option value="">Semua Pelatihan</option>
                            </select>
                            <button class="btn-export">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Peserta</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Pelatihan</th>
                                <th>Tanggal Mulai</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Nilai Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td style="font-weight: 600;">{{ $student->id }}</td>
                                <td>{{ $student->nama }}</td>
                                <td style="color: #64748B;">{{ $student->email }}</td>
                                <td>{{ $student->pelatihan }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->tanggal_mulai)->format('Y-m-d') }}</td>
                                <td>
                                    <span class="status-badge {{ strtolower($student->status) == 'selesai' ? 'selesai' : 'berlangsung' }}">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $student->progress }}%; background: {{ $student->status == 'Berlangsung' ? '#3B82F6' : '#10b981' }};"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #64748B;">{{ $student->progress }}%</span>
                                </td>
                                <td>
                                    @if($student->nilai)
                                        <span class="nilai-badge {{ $student->nilai >= 90 ? 'high' : 'medium' }}">
                                            {{ $student->nilai }}
                                        </span>
                                    @else
                                        <span style="color: #94A3B8; font-size: 0.875rem;">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="action-btn" onclick="showDetail('{{ $student->id }}', '{{ $student->nama }}', '{{ $student->email }}', '{{ $student->pelatihan }}', '{{ $student->tanggal_mulai }}', '{{ \Carbon\Carbon::parse($student->tanggal_mulai)->addMonths(2)->format('Y-m-d') }}', '{{ $student->status }}', {{ $student->progress }}, {{ $student->nilai ?? 0 }})">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="padding: 1.5rem; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; color: #64748B;">Menampilkan 8 dari 8 peserta</span>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: white; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">Sebelumnya</button>
                            <button style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: white; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">Selanjutnya</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal Detail Nilai Peserta -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Nilai Peserta</h2>
                <p id="modalStudentId"></p>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-group">
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value" id="modalEmail"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Pelatihan</span>
                        <span class="detail-value" id="modalPelatihan"></span>
                    </div>
                </div>
                <div class="detail-group">
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Mulai</span>
                        <span class="detail-value" id="modalTanggalMulai"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Selesai</span>
                        <span class="detail-value" id="modalTanggalSelesai"></span>
                    </div>
                </div>
                <div class="detail-group">
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span id="modalStatus"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Progress</span>
                        <div>
                            <div class="progress-bar" style="margin-top: 0.5rem;">
                                <div class="progress-fill" id="modalProgress" style="width: 0%;"></div>
                            </div>
                            <span style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: block;" id="modalProgressText">0%</span>
                        </div>
                    </div>
                </div>
                <div class="nilai-section">
                    <h3>Rincian Nilai</h3>
                    <div class="nilai-item">
                        <span class="nilai-label">Nilai Kuis</span>
                        <span class="nilai-score" id="modalNilaiKuis">-</span>
                    </div>
                    <div class="nilai-item">
                        <span class="nilai-label">Nilai Ujian</span>
                        <span class="nilai-score" id="modalNilaiUjian">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal primary" onclick="downloadCertificate()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Unduh Sertifikat
                </button>
                <button class="btn-modal secondary" onclick="viewHistory()">
                    Lihat Riwayat Lengkap
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Toast Notification -->
    <div id="toastNotification" class="toast-notification">
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Info</div>
            <div class="toast-message" id="toastMessage">Pesan</div>
        </div>
    </div>
    <script>
        function showToast(title, message, type = 'info') {
            const toast = document.getElementById('toastNotification');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            toast.className = 'toast-notification ' + type;
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            toast.classList.add('active');
            
            setTimeout(() => {
                toast.classList.remove('active');
            }, 4000);
        }

        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Revenue Chart
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Juta Rp)',
                        data: [28, 24, 20, 18, 16, 14, 12, 10, 8, 6, 4, 2],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(30, 41, 59, 0.95)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 13,
                                weight: 600
                            },
                            bodyFont: {
                                size: 14,
                                weight: 700
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + (context.parsed.y * 1000000).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + 'M';
                                },
                                font: {
                                    size: 11
                                },
                                color: '#64748B'
                            },
                            grid: {
                                color: '#F1F5F9',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748B'
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }
        
        // Filter functionality
        const searchInput = document.getElementById('searchStudent');
        const statusFilter = document.getElementById('filterStatus');
        const pelatihanFilter = document.getElementById('filterPelatihan');
        
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const pelatihanValue = pelatihanFilter.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const id = row.cells[0].textContent.toLowerCase();
                const nama = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const pelatihan = row.cells[3].textContent.toLowerCase();
                const status = row.cells[5].textContent.toLowerCase().trim();
                
                // Search filter
                const matchSearch = searchTerm === '' || 
                                   id.includes(searchTerm) || 
                                   nama.includes(searchTerm) || 
                                   email.includes(searchTerm);
                
                // Status filter
                const matchStatus = statusValue === '' || status.includes(statusValue);
                
                // Pelatihan filter
                const matchPelatihan = pelatihanValue === '' || pelatihan.includes(pelatihanValue);
                
                if (matchSearch && matchStatus && matchPelatihan) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput?.addEventListener('input', filterTable);
        statusFilter?.addEventListener('change', filterTable);
        pelatihanFilter?.addEventListener('change', filterTable);
        
        // Modal functions
        function showDetail(id, nama, email, pelatihan, tanggalMulai, tanggalSelesai, status, progress, nilai) {
            document.getElementById('modalStudentId').textContent = nama + ' - ' + id;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPelatihan').textContent = pelatihan;
            document.getElementById('modalTanggalMulai').textContent = tanggalMulai;
            document.getElementById('modalTanggalSelesai').textContent = tanggalSelesai;
            
            // Status badge
            const statusBadge = status.toLowerCase() === 'selesai' 
                ? '<span class="status-badge selesai">Selesai</span>'
                : '<span class="status-badge berlangsung">Berlangsung</span>';
            document.getElementById('modalStatus').innerHTML = statusBadge;
            
            // Progress
            const progressColor = status.toLowerCase() === 'berlangsung' ? '#3B82F6' : '#10b981';
            document.getElementById('modalProgress').style.width = progress + '%';
            document.getElementById('modalProgress').style.background = progressColor;
            document.getElementById('modalProgressText').textContent = progress + '%';
            
            // Nilai (generate dummy values for quiz and exam)
            if (nilai > 0) {
                const nilaiKuis = Math.round(nilai + Math.random() * 4 - 2);
                const nilaiUjian = Math.round(nilai + Math.random() * 4 - 2);
                document.getElementById('modalNilaiKuis').textContent = nilaiKuis;
                document.getElementById('modalNilaiUjian').textContent = nilaiUjian;
            } else {
                document.getElementById('modalNilaiKuis').textContent = '-';
                document.getElementById('modalNilaiUjian').textContent = '-';
            }
            
            document.getElementById('detailModal').classList.add('show');
        }
        
        function closeModal() {
            document.getElementById('detailModal').classList.remove('show');
        }
        
        function downloadCertificate() {
            showToast('Info', 'Mengunduh sertifikat...', 'info');
        }
        
        function viewHistory() {
            showToast('Info', 'Menampilkan riwayat lengkap...', 'info');
        }
        
        // Close modal when clicking outside
        document.getElementById('detailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endpush
