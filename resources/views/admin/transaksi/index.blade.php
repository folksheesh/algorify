@extends('layouts.template')

@section('title', 'Transaksi - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
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
                    <button class="btn-export" onclick="exportTransaksiCsv()">
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
                        <div class="filter-group" style="flex: 1;">
                            <input type="text" id="searchTable" class="filter-input" placeholder="Cari kode transaksi, tanggal, nama, atau kursus...">
                        </div>
                        <button type="button" id="statusMetodeButton" class="filter-button">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                            </svg>
                            <span id="statusMetodeText">Filter</span>
                        </button>
                        <button type="button" id="dateRangeButton" class="filter-button">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span id="dateRangeText">Tanggal</span>
                        </button>
                        <button type="button" id="searchButton" class="filter-search-button">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                            </svg>
                            Cari
                        </button>
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
                                <tr class="table-row-hover" onclick="showTransaksiDetail({{ $item->id }})" data-transaksi-id="{{ $item->id }}">
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

                <!-- Modal Detail Transaksi -->
                <div class="modal-overlay" id="transaksiDetailModal">
                    <div class="modal-detail">
                        <div class="modal-detail-header">
                            <div>
                                <h3>Detail Transaksi</h3>
                                <p class="modal-subtitle">Informasi lengkap transaksi</p>
                            </div>
                            <button type="button" class="modal-close" onclick="closeTransaksiDetail()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="modal-detail-body" id="transaksiDetailContent">
                            <div class="detail-loading">
                                <svg class="spinner" viewBox="0 0 50 50">
                                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                                </svg>
                                <p>Memuat detail transaksi...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Filter Status & Metode -->
                <div class="modal-overlay" id="statusMetodeModal">
                    <div class="modal-detail" style="max-width: 450px;">
                        <div class="modal-detail-header">
                            <div>
                                <h3>Filter Status & Metode</h3>
                                <p class="modal-subtitle">Pilih status transaksi dan metode pembayaran</p>
                            </div>
                            <button type="button" class="modal-close" onclick="closeStatusMetodeModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="modal-detail-body" style="padding: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Status Transaksi</label>
                                    <select id="filterStatus" class="filter-input" style="width: 100%;">
                                        <option value="">Semua Status</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="pending">Pending</option>
                                        <option value="gagal">Gagal</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Metode Pembayaran</label>
                                    <select id="filterMetode" class="filter-input" style="width: 100%;">
                                        <option value="">Semua Metode</option>
                                        <option value="transfer bank">Transfer Bank</option>
                                        <option value="e-wallet">E-Wallet</option>
                                        <option value="kartu kredit">Kartu Kredit</option>
                                        <option value="qris">Qris</option>
                                        <option value="mini market">Mini Market</option>
                                        <option value="kartu debit">Kartu Debit</option>
                                    </select>
                                </div>
                                <div style="display: flex; gap: 0.75rem; margin-top: 0.5rem;">
                                    <button type="button" onclick="clearStatusMetode()" style="flex: 1; padding: 0.625rem; border: 1px solid #E2E8F0; background: white; color: #475569; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                        Reset
                                    </button>
                                    <button type="button" onclick="applyStatusMetode()" style="flex: 1; padding: 0.625rem; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Filter Tanggal -->
                <div class="modal-overlay" id="dateRangeModal">
                    <div class="modal-detail" style="max-width: 450px;">
                        <div class="modal-detail-header">
                            <div>
                                <h3>Filter Rentang Tanggal</h3>
                                <p class="modal-subtitle">Pilih periode transaksi</p>
                            </div>
                            <button type="button" class="modal-close" onclick="closeDateRangeModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="modal-detail-body" style="padding: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Dari Tanggal</label>
                                    <input type="date" id="filterTanggalMulai" class="filter-input" style="width: 100%;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Sampai Tanggal</label>
                                    <input type="date" id="filterTanggalAkhir" class="filter-input" style="width: 100%;">
                                </div>
                                <div style="display: flex; gap: 0.75rem; margin-top: 0.5rem;">
                                    <button type="button" onclick="clearDateRange()" style="flex: 1; padding: 0.625rem; border: 1px solid #E2E8F0; background: white; color: #475569; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                        Reset
                                    </button>
                                    <button type="button" onclick="applyDateRange()" style="flex: 1; padding: 0.625rem; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // ===========================
        // MODAL DETAIL TRANSAKSI
        // ===========================
        
        const transaksiData = {!! json_encode($transaksi->map(function($item) {
            return [
                'id' => $item->id,
                'kode_transaksi' => $item->kode_transaksi,
                'tanggal_transaksi' => $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d M Y, H:i') : '-',
                'tanggal_kadaluarsa' => $item->tanggal_kadaluarsa ? $item->tanggal_kadaluarsa->format('d M Y, H:i') : '-',
                'jumlah' => $item->jumlah,
                'jumlah_formatted' => 'Rp ' . number_format($item->jumlah, 0, ',', '.'),
                'metode_pembayaran' => $item->metode_pembayaran,
                'metode_pembayaran_label' => ucfirst(str_replace('_', ' ', $item->metode_pembayaran)),
                'status' => $item->status,
                'status_label' => $item->status == 'success' ? 'Lunas' : ($item->status == 'pending' ? 'Pending' : 'Gagal'),
                'user_name' => $item->user->name ?? 'N/A',
                'user_email' => $item->user->email ?? 'N/A',
                'kursus_judul' => $item->kursus->judul ?? 'N/A',
                'kursus_harga' => $item->kursus ? 'Rp ' . number_format($item->kursus->harga, 0, ',', '.') : 'N/A',
                'snap_token' => $item->snap_token ?? null,
                'payment_url' => $item->payment_url ?? null,
                'external_id' => $item->external_id ?? null,
                'created_at' => $item->created_at->format('d M Y, H:i'),
                'updated_at' => $item->updated_at->format('d M Y, H:i'),
            ];
        })->values()) !!};
        
        function showTransaksiDetail(transaksiId) {
            const modal = document.getElementById('transaksiDetailModal');
            const contentDiv = document.getElementById('transaksiDetailContent');
            
            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Find transaction data
            const transaksi = transaksiData.find(t => t.id === transaksiId);
            
            if (!transaksi) {
                contentDiv.innerHTML = `
                    <div class="detail-error">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <h3>Data tidak ditemukan</h3>
                        <p>Transaksi tidak ditemukan dalam sistem</p>
                    </div>
                `;
                return;
            }
            
            // Build status badge HTML
            let statusBadgeHtml = '';
            if (transaksi.status === 'success') {
                statusBadgeHtml = `
                    <span class="status-badge success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Lunas
                    </span>
                `;
            } else if (transaksi.status === 'pending') {
                statusBadgeHtml = `
                    <span class="status-badge pending" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pending
                    </span>
                `;
            } else {
                statusBadgeHtml = `
                    <span class="status-badge failed" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Gagal
                    </span>
                `;
            }
            
            // Build payment method icon
            let metodeIcon = '';
            if (transaksi.metode_pembayaran === 'bank_transfer') {
                metodeIcon = `
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 20px; height: 20px; color: #3B82F6;">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                `;
            } else if (transaksi.metode_pembayaran === 'qris') {
                metodeIcon = `
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 20px; height: 20px; color: #10B981;">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd"/>
                    </svg>
                `;
            } else {
                metodeIcon = `
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 20px; height: 20px; color: #8B5CF6;">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                `;
            }
            
            // Populate modal content
            contentDiv.innerHTML = `
                <div class="detail-sections">
                    <!-- Status Section -->
                    <div class="detail-status-section">
                        <div class="detail-status-header">
                            <div>
                                <h3>${transaksi.kode_transaksi}</h3>
                                <p class="detail-date">${transaksi.tanggal_transaksi}</p>
                            </div>
                            ${statusBadgeHtml}
                        </div>
                        <div class="detail-amount">
                            <span class="detail-amount-label">Total Pembayaran</span>
                            <span class="detail-amount-value">${transaksi.jumlah_formatted}</span>
                        </div>
                    </div>
                    
                    <!-- Info Grid -->
                    <div class="detail-info-grid">
                        <!-- Informasi Transaksi -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                                <h4>Informasi Transaksi</h4>
                            </div>
                            <div class="detail-card-content">
                                <div class="detail-row">
                                    <span class="detail-label">Kode Transaksi</span>
                                    <span class="detail-value">${transaksi.kode_transaksi}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Metode Pembayaran</span>
                                    <span class="detail-value" style="display: flex; align-items: center; gap: 0.5rem;">
                                        ${metodeIcon}
                                        ${transaksi.metode_pembayaran_label}
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Tanggal Transaksi</span>
                                    <span class="detail-value">${transaksi.tanggal_transaksi}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Tanggal Kadaluarsa</span>
                                    <span class="detail-value">${transaksi.tanggal_kadaluarsa}</span>
                                </div>
                                ${transaksi.external_id ? `
                                <div class="detail-row">
                                    <span class="detail-label">External ID</span>
                                    <span class="detail-value" style="font-family: monospace; font-size: 0.8125rem;">${transaksi.external_id}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Informasi Peserta -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <h4>Informasi Peserta</h4>
                            </div>
                            <div class="detail-card-content">
                                <div class="detail-row">
                                    <span class="detail-label">Nama Lengkap</span>
                                    <span class="detail-value">${transaksi.user_name}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value">${transaksi.user_email}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Kursus -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                <h4>Informasi Kursus</h4>
                            </div>
                            <div class="detail-card-content">
                                <div class="detail-row">
                                    <span class="detail-label">Judul Kursus</span>
                                    <span class="detail-value">${transaksi.kursus_judul}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Harga Kursus</span>
                                    <span class="detail-value">${transaksi.kursus_harga}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Sistem -->
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <h4>Informasi Sistem</h4>
                            </div>
                            <div class="detail-card-content">
                                <div class="detail-row">
                                    <span class="detail-label">Dibuat Pada</span>
                                    <span class="detail-value">${transaksi.created_at}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Terakhir Update</span>
                                    <span class="detail-value">${transaksi.updated_at}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function closeTransaksiDetail() {
            const modal = document.getElementById('transaksiDetailModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on overlay click
        document.getElementById('transaksiDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransaksiDetail();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('transaksiDetailModal');
                if (modal.classList.contains('active')) {
                    closeTransaksiDetail();
                }
                const dateModal = document.getElementById('dateRangeModal');
                if (dateModal.classList.contains('active')) {
                    closeDateRangeModal();
                }
                const statusModal = document.getElementById('statusMetodeModal');
                if (statusModal.classList.contains('active')) {
                    closeStatusMetodeModal();
                }
            }
        });
        
        // ===========================
        // STATUS & METODE MODAL
        // ===========================
        
        const statusMetodeButton = document.getElementById('statusMetodeButton');
        const statusMetodeModal = document.getElementById('statusMetodeModal');
        const statusMetodeText = document.getElementById('statusMetodeText');
        const filterStatus = document.getElementById('filterStatus');
        const filterMetode = document.getElementById('filterMetode');
        
        // Open status metode modal
        statusMetodeButton.addEventListener('click', function() {
            statusMetodeModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close status metode modal
        function closeStatusMetodeModal() {
            statusMetodeModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on overlay click
        statusMetodeModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusMetodeModal();
            }
        });
        
        // Apply status metode filter
        function applyStatusMetode() {
            updateStatusMetodeText();
            closeStatusMetodeModal();
            applyFilters();
        }
        
        // Clear status metode
        function clearStatusMetode() {
            filterStatus.value = '';
            filterMetode.value = '';
            statusMetodeText.textContent = 'Filter';
            closeStatusMetodeModal();
            applyFilters();
        }
        
        // Update button text
        function updateStatusMetodeText() {
            const status = filterStatus.value;
            const metode = filterMetode.value;
            const parts = [];
            
            if (status) {
                const statusLabels = { 'lunas': 'Lunas', 'pending': 'Pending', 'gagal': 'Gagal' };
                parts.push(statusLabels[status] || status);
            }
            if (metode) {
                const metodeShort = {
                    'transfer bank': 'Transfer',
                    'e-wallet': 'E-Wallet',
                    'kartu kredit': 'Kredit',
                    'qris': 'QRIS',
                    'mini market': 'Minimarket',
                    'kartu debit': 'Debit'
                };
                parts.push(metodeShort[metode] || metode);
            }
            
            statusMetodeText.textContent = parts.length > 0 ? parts.join(' | ') : 'Filter';
        }
        
        // Initialize from URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status')) filterStatus.value = urlParams.get('status');
        if (urlParams.get('metode')) filterMetode.value = urlParams.get('metode');
        updateStatusMetodeText();
        
        // ===========================
        // DATE RANGE MODAL
        // ===========================
        
        const dateRangeButton = document.getElementById('dateRangeButton');
        const dateRangeModal = document.getElementById('dateRangeModal');
        const dateRangeText = document.getElementById('dateRangeText');
        
        // Open date range modal
        dateRangeButton.addEventListener('click', function() {
            dateRangeModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close date range modal
        function closeDateRangeModal() {
            dateRangeModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on overlay click
        dateRangeModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDateRangeModal();
            }
        });
        
        // Apply date range filter
        function applyDateRange() {
            const mulai = document.getElementById('filterTanggalMulai').value;
            const akhir = document.getElementById('filterTanggalAkhir').value;
            
            // Update button text
            if (mulai && akhir) {
                dateRangeText.textContent = `${formatDateDisplay(mulai)} - ${formatDateDisplay(akhir)}`;
            } else if (mulai) {
                dateRangeText.textContent = `Dari ${formatDateDisplay(mulai)}`;
            } else if (akhir) {
                dateRangeText.textContent = `Sampai ${formatDateDisplay(akhir)}`;
            } else {
                dateRangeText.textContent = 'Pilih Rentang Tanggal';
            }
            
            closeDateRangeModal();
            applyFilters();
        }
        
        // Clear date range
        function clearDateRange() {
            document.getElementById('filterTanggalMulai').value = '';
            document.getElementById('filterTanggalAkhir').value = '';
            dateRangeText.textContent = 'Tanggal';
            closeDateRangeModal();
            applyFilters();
        }
        
        // Format date for display
        function formatDateDisplay(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }
        
        // Initialize button text from URL params
        if (urlParams.get('tanggal_mulai') || urlParams.get('tanggal_akhir')) {
            const mulai = urlParams.get('tanggal_mulai');
            const akhir = urlParams.get('tanggal_akhir');
            if (mulai && akhir) {
                dateRangeText.textContent = `${formatDateDisplay(mulai)} - ${formatDateDisplay(akhir)}`;
            } else if (mulai) {
                dateRangeText.textContent = `Dari ${formatDateDisplay(mulai)}`;
            } else if (akhir) {
                dateRangeText.textContent = `Sampai ${formatDateDisplay(akhir)}`;
            }
        }
        
        // ===========================
        // SEARCH AND FILTER
        // ===========================
        
        // Search and filter functionality - Server-side
        const searchInput = document.getElementById('searchTable');
        const statusFilter = document.getElementById('filterStatus');
        const metodeFilter = document.getElementById('filterMetode');
        const searchButton = document.getElementById('searchButton');
        
        // Set initial values from URL params
        if (urlParams.get('search')) searchInput.value = urlParams.get('search');

        if (urlParams.get('tanggal_mulai')) document.getElementById('filterTanggalMulai').value = urlParams.get('tanggal_mulai');
        if (urlParams.get('tanggal_akhir')) document.getElementById('filterTanggalAkhir').value = urlParams.get('tanggal_akhir');
        
        // Function to apply filters via server-side request
        function applyFilters() {
            const params = new URLSearchParams();
            
            if (searchInput.value) params.append('search', searchInput.value);
            if (filterStatus.value) params.append('status', filterStatus.value);
            if (filterMetode.value) params.append('metode', filterMetode.value);
            
            const tanggalMulai = document.getElementById('filterTanggalMulai').value;
            const tanggalAkhir = document.getElementById('filterTanggalAkhir').value;
            if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
            if (tanggalAkhir) params.append('tanggal_akhir', tanggalAkhir);
            
            // Redirect to same page with query params (page will reset to 1)
            const queryString = params.toString();
            window.location.href = '{{ route("admin.transaksi.index") }}' + (queryString ? '?' + queryString : '');
        }
        
        // Search only when clicking button or pressing Enter
        searchButton.addEventListener('click', () => applyFilters());
        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                applyFilters();
            }
        });
        
        // Export CSV function
        function exportTransaksiCsv() {
            const params = new URLSearchParams();
            
            if (searchInput.value) params.append('search', searchInput.value);
            if (filterStatus.value) params.append('status', filterStatus.value);
            if (filterMetode.value) params.append('metode', filterMetode.value);
            
            const tanggalMulai = document.getElementById('filterTanggalMulai').value;
            const tanggalAkhir = document.getElementById('filterTanggalAkhir').value;
            if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
            if (tanggalAkhir) params.append('tanggal_akhir', tanggalAkhir);
            
            // Open export URL with filters
            const queryString = params.toString();
            window.location.href = '{{ route("admin.transaksi.export-csv") }}' + (queryString ? '?' + queryString : '');
        }
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