@extends('layouts.template')

@section('title', 'Transaksi - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/transaksi-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                {{-- Page Header --}}
                <div class="page-header">
                    <h1>Halaman Data Transaksi</h1>
                </div>

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
                            <div class="stat-change neutral">
                                <span style="color: #10b981;">{{ $lunasCount ?? 0 }} Lunas</span>
                                <span style="color: #f59e0b; margin-left: 0.5rem;">{{ $pendingCount ?? 0 }} Pending</span>
                                @if(isset($failedCount) && $failedCount > 0)
                                <span style="color: #ef4444; margin-left: 0.5rem;">{{ $failedCount }} Gagal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-group" style="flex: 1.2;">
                            <input type="text" id="searchTable" class="filter-input" placeholder="Cari kode transaksi, tanggal, nama, atau kursus...">
                        </div>
                        <div style="width: 1.5rem;"></div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterStatus" class="filter-select">
                                <option value="">Semua Status</option>
                                <option value="lunas">Lunas</option>
                                <option value="pending">Pending</option>
                                <option value="gagal">Gagal</option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterMetode" class="filter-select">
                                <option value="">Semua Metode</option>
                                <option value="transfer bank">Transfer Bank</option>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="kartu kredit">Kartu Kredit</option>
                                <option value="qris">Qris</option>
                                <option value="mini market">Mini Market</option>
                                <option value="kartu debit">Kartu Debit</option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0.8;">
                            <select id="filterPeriode" class="filter-select">
                                <option value="">Semua Waktu</option>
                                <option value="hari_ini">Hari Ini</option>
                                <option value="7_hari">7 Hari Terakhir</option>
                                <option value="bulan_ini">Bulan Ini</option>
                                <option value="bulan_lalu">Bulan Lalu</option>
                                <option value="tahun_ini">Tahun Ini</option>
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
                                <tr class="table-row-hover">
                                    <td class="transaction-code">{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d M Y, H:i') : '-' }}</td>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-name">{{ $item->user->name ?? 'N/A' }}</span>
                                            <span class="user-email">{{ $item->user->email ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->kursus->judul ?? 'N/A' }}</td>
                                    <td class="amount">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $metodeClass = match($item->metode_pembayaran) {
                                                'qris' => 'method-qris',
                                                'mini_market' => 'method-mini',
                                                'kartu_debit' => 'method-debit',
                                                'e_wallet' => 'method-ewallet',
                                                'credit_card' => 'method-cc',
                                                'bank_transfer' => 'method-bank',
                                                default => ''
                                            };
                                        @endphp
                                        <span class="method-badge {{ $metodeClass }}">
                                            @if($item->metode_pembayaran == 'bank_transfer')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Transfer Bank
                                            @elseif($item->metode_pembayaran == 'e_wallet')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                E-Wallet
                                            @elseif($item->metode_pembayaran == 'credit_card')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Kartu Kredit
                                            @elseif($item->metode_pembayaran == 'qris')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd"/>
                                                    <path d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM16 13a1 1 0 100 2h1a1 1 0 100-2h-1z"/>
                                                    <path d="M16 16a1 1 0 102 0v-3a1 1 0 10-2 0v3zM13 13a1 1 0 102 0 1 1 0 00-2 0zM13 16a1 1 0 102 0 1 1 0 00-2 0z"/>
                                                </svg>
                                                Qris
                                            @elseif($item->metode_pembayaran == 'mini_market')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Mini Market
                                            @elseif($item->metode_pembayaran == 'kartu_debit')
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Kartu Debit
                                            @else
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran)) }}
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
                                        @elseif($item->status == 'expired' || $item->status == 'failed')
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

                        <!-- Pagination seperti halaman pengajar -->
                        <div style="padding: 1.5rem; border-top: 1px solid #E2E8F0; display: flex; justify-content: center; gap: 0.5rem;">
                            @if ($transaksi->onFirstPage())
                                <button disabled class="pagination-btn">
                                    Sebelumnya
                                </button>
                            @else
                                <a href="{{ $transaksi->previousPageUrl() }}" class="pagination-btn">
                                    Sebelumnya
                                </a>
                            @endif

                            @php
                                $currentPage = $transaksi->currentPage();
                                $lastPage = $transaksi->lastPage();
                            @endphp
                            @for ($i = 1; $i <= $lastPage; $i++)
                                @if ($i == 1 || $i == $lastPage || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                                    @if ($i == $currentPage)
                                        <button class="pagination-btn active">{{ $i }}</button>
                                    @else
                                        <a href="{{ $transaksi->url($i) }}" class="pagination-btn">{{ $i }}</a>
                                    @endif
                                @elseif ($i == $currentPage - 3 || $i == $currentPage + 3)
                                    <span class="pagination-ellipsis">...</span>
                                @endif
                            @endfor

                            @if ($transaksi->hasMorePages())
                                <a href="{{ $transaksi->nextPageUrl() }}" class="pagination-btn">
                                    Selanjutnya
                                </a>
                            @else
                                <button disabled class="pagination-btn">
                                    Selanjutnya
                                </button>
                            @endif
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
        
        // Search and filter functionality - Server-side
        const searchInput = document.getElementById('searchTable');
        const statusFilter = document.getElementById('filterStatus');
        const metodeFilter = document.getElementById('filterMetode');
        const periodeFilter = document.getElementById('filterPeriode');
        let searchTimeout = null;
        
        // Set initial values from URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('search')) searchInput.value = urlParams.get('search');
        if (urlParams.get('status')) statusFilter.value = urlParams.get('status');
        if (urlParams.get('metode')) metodeFilter.value = urlParams.get('metode');
        if (urlParams.get('periode')) periodeFilter.value = urlParams.get('periode');
        
        // Function to apply filters via server-side request
        function applyFilters() {
            const params = new URLSearchParams();
            
            if (searchInput.value) params.append('search', searchInput.value);
            if (statusFilter.value) params.append('status', statusFilter.value);
            if (metodeFilter.value) params.append('metode', metodeFilter.value);
            if (periodeFilter.value) params.append('periode', periodeFilter.value);
            
            // Redirect to same page with query params (page will reset to 1)
            const queryString = params.toString();
            window.location.href = '{{ route("admin.transaksi.index") }}' + (queryString ? '?' + queryString : '');
        }
        
        // Search dengan debounce 500ms
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500);
        });
        
        // Filter langsung apply saat berubah
        statusFilter.addEventListener('change', applyFilters);
        metodeFilter.addEventListener('change', applyFilters);
        periodeFilter.addEventListener('change', applyFilters);
    </script>
    
    <style>
        /* Hover effect untuk table row dengan scale */
        .table-row-hover {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .table-row-hover:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background-color: #F8FAFC;
            position: relative;
            z-index: 1;
        }
    </style>
@endpush