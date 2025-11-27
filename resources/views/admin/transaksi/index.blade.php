@extends('layouts.template')

@section('title', 'Transaksi - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
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
        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
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
        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.75rem;
            margin-top: 0.5rem;
        }
        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }
        .stat-change.success {
            color: #10b981;
        }
        .stat-change.neutral {
            color: #64748B;
        }
        .stat-change svg {
            width: 16px;
            height: 16px;
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
        .info-banner-content h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .info-banner-content p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin: 0;
        }
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .filter-group {
            flex: 1;
            min-width: 0;
        }
        .filter-select, .filter-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #1E293B;
            background: white;
            transition: all 0.2s;
        }
        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: #5D3FFF;
        }
        .btn-export {
            background: white;
            border: 1px solid #E2E8F0;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-export:hover {
            background: #F8FAFC;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
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
        .data-table thead {
            background: #F8FAFC;
        }
        .data-table th {
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
        .data-table tbody tr:hover {
            background: #F8FAFC;
        }
        .transaction-code {
            font-weight: 600;
            color: #1E293B;
        }
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .user-name {
            font-weight: 500;
            color: #1E293B;
        }
        .user-email {
            font-size: 0.8rem;
            color: #64748B;
        }
        .amount {
            font-weight: 600;
            color: #1E293B;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        .status-badge.success {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge.pending {
            background: #FEF3C7;
            color: #D97706;
        }
        .status-badge.failed {
            background: #FEE2E2;
            color: #DC2626;
        }
        .status-badge.expired {
            background: #F3F4F6;
            color: #6B7280;
        }
        .method-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #F1F5F9;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #475569;
        }
        .method-badge svg {
            width: 14px;
            height: 14px;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            color: #CBD5E1;
            margin-bottom: 1rem;
        }
        .empty-state h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            color: #94A3B8;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <!-- Hero Banner -->
                <section class="hero-banner" style="margin-bottom: 2rem;">
                    <div class="hero-content">
                        <h1 class="hero-title">Transaksi</h1>
                        <p class="hero-description">Kelola dan pantau transaksi pelatihan TIK</p>
                    </div>
                </section>

                <!-- Info Banner -->
                <div class="info-banner">
                    <div class="info-banner-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="info-banner-content">
                        <h3>Laporan Real-time</h3>
                        <p>Semua data transaksi ditampilkan secara real-time dan konsisten dengan database. Waktu muat laporan dioptimalkan di bawah 3 detik dengan enkripsi penuh.</p>
                    </div>
                </div>

                <!-- Last Update Info -->
                <div style="background: white; padding: 0.875rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #64748B; font-size: 0.875rem;">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <span>Data real-time - Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</span>
                    </div>
                    <button class="btn-export" onclick="window.print()">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                        </svg>
                        Export CSV
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FEF3C7;">
                            <svg viewBox="0 0 24 24" fill="#D97706">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Pendapatan</div>
                            <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="stat-change success">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tingkat keberhasilan {{ $tingkatKeberhasilan }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: #DBEAFE;">
                            <svg viewBox="0 0 24 24" fill="#3B82F6">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Transaksi</div>
                            <div class="stat-value">{{ $totalTransaksi }}</div>
                            <div class="stat-change neutral" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                <span style="color: #10b981;">✓ {{ $successCount ?? 0 }} Lunas</span>
                                <span style="color: #f59e0b;">⏳ {{ $pendingCount ?? 0 }} Pending</span>
                                @if(($failedCount ?? 0) > 0)
                                <span style="color: #ef4444;">✗ {{ $failedCount }} Gagal</span>
                                @endif
                                @if(($expiredCount ?? 0) > 0)
                                <span style="color: #64748B;">⌛ {{ $expiredCount }} Expired</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-group" style="flex: 1.2;">
                            <input type="text" id="searchTable" class="filter-input" placeholder="Cari kode transaksi atau nama...">
                        </div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterStatus" class="filter-select">
                                <option value="">Semua Status</option>
                                <option value="success">Lunas</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Gagal</option>
                                <option value="expired">Kadaluarsa</option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterKursus" class="filter-select">
                                <option value="">Semua Kursus</option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterBulan" class="filter-select">
                                <option value="">Semua Bulan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Daftar Transaksi</h2>
                    </div>
                    @if($transaksi->count() > 0)
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Peserta</th>
                                    <th>Kursus</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi as $item)
                                <tr>
                                    <td class="transaction-code">{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-name">{{ $item->user->name ?? 'N/A' }}</span>
                                            <span class="user-email">{{ $item->user->email ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->kursus->judul ?? 'N/A' }}</td>
                                    <td class="amount">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="method-badge">
                                            @if($item->metode_pembayaran == 'transfer')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Transfer Bank
                                            @elseif($item->metode_pembayaran == 'ewallet')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                E-Wallet
                                            @else
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Virtual Account
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->status == 'success')
                                            <span class="status-badge success">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Lunas
                                            </span>
                                        @elseif($item->status == 'pending')
                                            <span class="status-badge pending">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Pending
                                            </span>
                                        @elseif($item->status == 'failed')
                                            <span class="status-badge failed">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Gagal
                                            </span>
                                        @else
                                            <span class="status-badge expired">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div style="padding: 1.5rem; border-top: 1px solid #E2E8F0;">
                            {{ $transaksi->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                            <h3>Tidak ada transaksi ditemukan</h3>
                            <p>Belum ada transaksi yang tercatat dalam sistem</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Search and filter functionality
        const searchInput = document.getElementById('searchTable');
        const statusFilter = document.getElementById('filterStatus');
        const kursusFilter = document.getElementById('filterKursus');
        const bulanFilter = document.getElementById('filterBulan');
        
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const kursusValue = kursusFilter.value.toLowerCase();
            const bulanValue = bulanFilter.value;
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const kodeTransaksi = row.cells[0].textContent.toLowerCase();
                const tanggal = row.cells[1].textContent;
                const peserta = row.cells[2].textContent.toLowerCase();
                const kursus = row.cells[3].textContent.toLowerCase();
                const status = row.cells[6].textContent.toLowerCase().trim();
                
                // Search filter
                const matchSearch = searchTerm === '' || 
                                   kodeTransaksi.includes(searchTerm) || 
                                   peserta.includes(searchTerm) || 
                                   kursus.includes(searchTerm);
                
                // Status filter
                const matchStatus = statusValue === '' || status.includes(statusValue);
                
                // Kursus filter
                const matchKursus = kursusValue === '' || kursus.includes(kursusValue);
                
                // Bulan filter (extract month from date)
                let matchBulan = true;
                if (bulanValue !== '') {
                    const monthNames = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];
                    const monthIndex = parseInt(bulanValue) - 1;
                    matchBulan = tanggal.toLowerCase().includes(monthNames[monthIndex]);
                }
                
                if (matchSearch && matchStatus && matchKursus && matchBulan) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        kursusFilter.addEventListener('change', filterTable);
        bulanFilter.addEventListener('change', filterTable);
    </script>
@endpush
