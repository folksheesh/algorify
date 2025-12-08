@extends('layouts.template')

@section('title', 'Bank Soal - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .page-header { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #1E293B; margin: 0 0 0.25rem 0; }
        .page-header p { color: #64748B; font-size: 0.875rem; margin: 0; }
        .table-container { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); margin-top: 1.5rem; }
        .filter-section { margin-bottom: 1.75rem; }
        .section-label { font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em; }
        .search-box { position: relative; width: 100%; margin-bottom: 1.25rem; }
        .search-box input { width: 100%; padding: 0.625rem 1rem 0.625rem 2.75rem; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 0.875rem; height: 42px; box-sizing: border-box; }
        .search-box input:focus { outline: none; border-color: #5D3FFF; box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1); }
        .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8; pointer-events: none; }
        .filter-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.75rem; }
        .status-dropdown { width: 100%; padding: 0 2.5rem 0 1rem; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 0.875rem; font-weight: 500; color: #334155; background: white; cursor: pointer; height: 42px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M7 10L12 15L17 10' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.875rem center; background-size: 18px; appearance: none; }
        .status-dropdown:focus { outline: none; border-color: #5D3FFF; box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1); }
        .actions-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; gap: 1rem; }
        .import-export-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .btn-secondary { padding: 0.5rem 1rem; background: white; color: #64748B; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; height: 36px; transition: all 0.2s; text-decoration: none; }
        .btn-secondary:hover { background: #F8FAFC; border-color: #94A3B8; color: #475569; }
        
        /* File Management Buttons - Same size */
        .btn-file { padding: 0 0.875rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; height: 36px; transition: all 0.2s; text-decoration: none; border: 1px solid; white-space: nowrap; }
        .btn-template { background: #F0FDF4; color: #15803D; border-color: #86EFAC; }
        .btn-template:hover { background: #DCFCE7; border-color: #4ADE80; color: #166534; }
        .btn-import { background: #EFF6FF; color: #1D4ED8; border-color: #93C5FD; }
        .btn-import:hover { background: #DBEAFE; border-color: #60A5FA; color: #1E40AF; }
        .btn-export { background: #FEF3C7; color: #B45309; border-color: #FCD34D; }
        .btn-export:hover { background: #FDE68A; border-color: #FBBF24; color: #92400E; }
        
        .btn-add { padding: 0 1.25rem; background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%); color: white; border: none; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; height: 36px; box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2); transition: all 0.2s; }
        .btn-add:hover { background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3); }
        @media (max-width: 768px) {
            .filter-grid { grid-template-columns: repeat(2, 1fr); }
            .actions-row { flex-direction: column; align-items: stretch; }
            .import-export-group { justify-content: center; }
        }
        @media (max-width: 480px) {
            .filter-grid { grid-template-columns: 1fr; }
        }
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .data-table thead { background: #5D3FFF; color: white; }
        .data-table thead th { padding: 1rem; text-align: center; font-weight: 600; font-size: 0.875rem; }
        .data-table thead th:first-child { border-radius: 8px 0 0 0; }
        .data-table thead th:last-child { border-radius: 0 8px 0 0; }
        .data-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: all 0.2s; }
        .data-table tbody tr:hover { background: #F8FAFC; transform: scale(1.01); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        .data-table tbody td { padding: 1rem; font-size: 0.875rem; color: #334155; text-align: center; }
        .action-buttons { display: flex; gap: 0.5rem; justify-content: center; }
        .btn-action { padding: 0.5rem; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; background: transparent; }
        .btn-action:hover { transform: scale(1.1); }
        .btn-edit { color: #F59E0B; }
        .btn-delete { color: #EF4444; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.65); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: white; border-radius: 16px; padding: 2rem 2.5rem; max-width: 640px; width: 100%; max-height: 90vh; overflow-y: auto; position: relative; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); }
        .modal-header h2 { font-size: 1.375rem; font-weight: 700; color: #0F172A; margin: 0 0 0.5rem 0; }
        .modal-header p { color: #64748B; font-size: 0.8125rem; margin: 0 0 1.5rem 0; }
        .modal-close { position: absolute; top: 1.75rem; right: 2rem; background: transparent; border: none; color: #94A3B8; cursor: pointer; font-size: 1.5rem; }
        .modal-close:hover { color: #64748B; }
        .form-group { margin-bottom: 0.875rem; }
        .form-label { display: block; font-size: 0.8125rem; font-weight: 500; color: #0F172A; margin-bottom: 0.375rem; }
        .form-input { width: 100%; padding: 0.75rem 0.875rem; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8125rem; background: white; color: #475569; transition: all 0.2s; box-sizing: border-box; }
        .form-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        select.form-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M7 10L12 15L17 10' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px; padding-right: 2.5rem; cursor: pointer; }
        textarea.form-input { font-family: 'Inter', sans-serif; line-height: 1.5; resize: vertical; min-height: 80px; }
        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .opsi-container { display: none; margin-top: 1rem; margin-bottom: 1rem; }
        .opsi-container.active { display: block; }
        .opsi-list { margin-bottom: 1rem; }
        .opsi-item { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
        .opsi-item input[type="checkbox"], .opsi-item input[type="radio"] { width: 18px; height: 18px; cursor: pointer; accent-color: #5D3FFF; }
        .opsi-item input[type="text"] { flex: 1; }
        .opsi-item .btn-remove { padding: 0.5rem; background: #FEE2E2; color: #DC2626; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; }
        .opsi-item .btn-remove:hover { background: #FECACA; }
        .btn-add-opsi { padding: 0.5rem 1rem; background: white; color: #5D3FFF; border: 1px solid #5D3FFF; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
        .btn-add-opsi:hover { background: #F5F3FF; }
        .file-upload-wrapper { position: relative; }
        .file-upload-label { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; border: 2px dashed #E2E8F0; border-radius: 8px; cursor: pointer; transition: all 0.2s; background: #FAFAFA; }
        .file-upload-label:hover { border-color: #5D3FFF; background: #F5F3FF; }
        .file-upload-label svg { color: #64748B; }
        .file-upload-label span { color: #64748B; font-size: 0.8125rem; }
        .file-upload-label.has-file { border-color: #10B981; background: #ECFDF5; }
        .file-upload-label.has-file svg { color: #10B981; }
        .file-upload-label.has-file span { color: #059669; font-weight: 500; }
        .file-upload-wrapper input[type="file"] { position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 0.625rem; margin-top: 1.25rem; }
        .btn { padding: 0.625rem 1.75rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-cancel { background: white; color: #64748B; border: 1px solid #CBD5E1; }
        .btn-cancel:hover { background: #F8FAFC; }
        .btn-submit { background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%); color: white; box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2); }
        .btn-submit:hover { background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%); }
        .type-badge { display: inline-block; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; min-width: 140px; text-align: center; }
        .type-badge.pilihan_ganda { background: #DBEAFE; color: #1E40AF; }
        .type-badge.multi_jawaban { background: #FEF3C7; color: #92400E; }
        .type-badge.essay { background: #D1FAE5; color: #065F46; }
        
        /* Toast Notification Styles */
        .toast-container { position: fixed; top: 2rem; right: 2rem; z-index: 99999; }
        .toast { background: white; padding: 1rem 1.5rem; border-radius: 10px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; min-width: 300px; animation: slideIn 0.3s ease-out; }
        .toast.success { border-left: 4px solid #10B981; }
        .toast.error { border-left: 4px solid #EF4444; }
        .toast.warning { border-left: 4px solid #F59E0B; }
        .toast-icon { font-size: 1.5rem; }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem; }
        .toast-message { font-size: 0.8125rem; color: #64748B; }
        .toast-close { background: none; border: none; cursor: pointer; color: #94A3B8; font-size: 1.25rem; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single { height: 38px; border: 1px solid #E2E8F0; border-radius: 6px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; padding-left: 0.875rem; color: #475569; font-size: 0.8125rem; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
        .select2-container--default.select2-container--focus .select2-selection--single { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        .select2-dropdown { border: 1px solid #E2E8F0; border-radius: 6px; }
        .select2-search--dropdown .select2-search__field { border: 1px solid #E2E8F0; border-radius: 6px; padding: 0.5rem; font-size: 0.8125rem; }
        .select2-results__option { font-size: 0.8125rem; padding: 0.5rem 0.875rem; }
        .select2-results__option--highlighted { background-color: #F5F3FF !important; color: #5D3FFF !important; }

        /* Pagination Styles */
        .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #E2E8F0; }
        .pagination-info { font-size: 0.875rem; color: #64748B; }
        .pagination-buttons { display: flex; gap: 0.5rem; align-items: center; }
        .pagination-btn { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px; background: white; color: #334155; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .pagination-btn:hover:not(.disabled):not(.active) { border-color: #5D3FFF; color: #5D3FFF; background: rgba(93, 63, 255, 0.05); }
        .pagination-btn.active { background: #5D3FFF; border-color: #5D3FFF; color: white; }
        .pagination-btn.disabled { opacity: 0.5; cursor: not-allowed; }
        .pagination-ellipsis { padding: 0 0.5rem; color: #94A3B8; }
    </style>
@endpush

@section('content')
    @role('pengajar')
    <div style="padding-top: 64px;">
        @include('components.topbar-pengajar')
    @endrole
    
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <div class="page-header">
                    <div>
                        <h1>Bank Soal</h1>
                        <p>Kelola soal kuis dan ujian</p>
                    </div>
                </div>

                <div class="table-container">
                    {{-- Filter Section --}}
                    <div class="filter-section">
                        <div class="section-label">Pencarian & Filter</div>
                        
                        {{-- Search Bar (Full Width) --}}
                        <div class="search-box">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <input type="text" id="searchInput" placeholder="Cari berdasarkan pertanyaan, kategori, kursus, atau tipe..." onkeyup="loadData()">
                        </div>

                        {{-- Filter Dropdowns (3 columns) --}}
                        <div class="filter-grid">
                            <select class="status-dropdown" id="kursusFilter" onchange="loadData()">
                                <option value="">Semua Kursus</option>
                            </select>
                            <select class="status-dropdown" id="kategoriFilter" onchange="loadData()">
                                <option value="">Semua Kategori</option>
                            </select>
                            <select class="status-dropdown" id="tipeFilter" onchange="loadData()">
                                <option value="">Semua Tipe</option>
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="multi_jawaban">Pilihan Ganda Multi</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Actions Row --}}
                    <div class="section-label">Manajemen File</div>
                    <div class="actions-row">
                        <div class="import-export-group">
                            <a href="{{ route('admin.bank-soal.download-template') }}" class="btn-file btn-template">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="12" y1="18" x2="12" y2="12"/>
                                    <line x1="9" y1="15" x2="15" y2="15"/>
                                </svg>
                                Unduh Template
                            </a>
                            <button class="btn-file btn-import" onclick="openImportModal()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                Import CSV
                            </button>
                            <button class="btn-file btn-export" onclick="exportCsv()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Export CSV
                            </button>
                        </div>
                        <button class="btn-add" onclick="openAddModal()">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 3.75V14.25M3.75 9H14.25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Tambah Soal
                        </button>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pertanyaan</th>
                                <th>Kategori</th>
                                <th>Tipe</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="soalTableBody">
                            <tr><td colspan="6">Loading...</td></tr>
                        </tbody>
                    </table>
                    
                    <!-- Pagination Container -->
                    <div class="pagination-container" id="paginationContainer">
                        <div class="pagination-info">
                            Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalData">0</span> data
                        </div>
                        <div class="pagination-buttons" id="paginationButtons">
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Tambah/Edit Soal --}}
    <div class="modal-overlay" id="soalModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Soal</h2>
                <p id="modalSubtitle">Isi form di bawah untuk menambahkan soal</p>
            </div>
            <form id="soalForm">
                <input type="hidden" id="soalId">
                
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select class="form-input" id="kategoriId">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Soal *</label>
                        <select class="form-input" id="tipeSoal" onchange="toggleOpsi()" required>
                            <option value="">Pilih Tipe</option>
                            <option value="pilihan_ganda">Pilihan Ganda</option>
                            <option value="multi_jawaban">Multi Jawaban</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan *</label>
                    <textarea class="form-input" id="pertanyaan" placeholder="Tulis pertanyaan di sini" required></textarea>
                </div>

                <div id="opsiContainer" class="opsi-container">
                    <label class="form-label">Pilihan Jawaban</label>
                    <div id="opsiList" class="opsi-list">
                        <!-- Options will be added dynamically -->
                    </div>
                    <button type="button" id="btnAddOpsi" class="btn-add-opsi" onclick="addOpsi()">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3.33334V12.6667M3.33334 8H12.6667" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tambah Pilihan
                    </button>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Poin per soal</label>
                        <select class="form-input" id="poin">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lampiran (Opsional)</label>
                        <div class="file-upload-wrapper">
                            <label for="lampiran" class="file-upload-label" id="fileLabel">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span id="fileText">Pilih file atau drag & drop (JPG, PNG, PDF)</span>
                            </label>
                            <input type="file" class="form-input" id="lampiran" accept=".jpg,.jpeg,.png,.pdf" onchange="updateFileLabel()">
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal View Soal --}}
    <div class="modal-overlay" id="viewModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
            <div class="modal-header">
                <h2>Detail Soal</h2>
                <p>Informasi lengkap soal</p>
            </div>
            <div class="form-group">
                <label class="form-label">Pertanyaan</label>
                <textarea class="form-input" id="viewPertanyaan" readonly></textarea>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Tipe Soal</label>
                    <input type="text" class="form-input" id="viewTipe" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-input" id="viewKategori" readonly>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeViewModal()">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Import CSV --}}
    <div class="modal-overlay" id="importModal">
        <div class="modal-content" style="max-width: 500px;">
            <button class="modal-close" onclick="closeImportModal()">&times;</button>
            <div class="modal-header">
                <h2>Import Soal dari CSV</h2>
                <p>Upload file CSV untuk mengimport soal secara massal</p>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">File CSV *</label>
                    <div class="file-upload-wrapper">
                        <label for="importFile" class="file-upload-label" id="importFileLabel">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span id="importFileText">Pilih file CSV atau drag & drop</span>
                        </label>
                        <input type="file" id="importFile" name="file" accept=".csv" onchange="updateImportFileLabel()" style="position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1;">
                    </div>
                </div>
                <div style="background: #F0F9FF; border: 1px solid #0EA5E9; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                    <p style="color: #0369A1; font-size: 0.8125rem; margin: 0;">
                        <strong>Format CSV:</strong><br>
                        Kolom: pertanyaan, tipe_soal, opsi_jawaban, jawaban_benar, poin<br>
                        - tipe_soal: pilihan_ganda atau multi_jawaban<br>
                        - opsi_jawaban: dipisahkan dengan | (contoh: Opsi A|Opsi B|Opsi C)<br>
                        - jawaban_benar: index jawaban (0,1,2,... atau 0,2 untuk multi)
                    </p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeImportModal()">Batal</button>
                    <button type="submit" class="btn btn-submit" id="btnImport">Import</button>
                </div>
            </form>
        </div>
    </div>
    
    @role('pengajar')
    </div>
    @endrole
@endsection

@push('scripts')
<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS for searchable dropdown -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // ============================================
    // GLOBAL VARIABLES
    // ============================================
    let editingId = null;        // ID soal yang sedang diedit
    let opsiCounter = 0;         // Counter untuk membuat ID unik pada setiap opsi
    const MAX_OPTIONS = 6;       // Batas maksimal pilihan jawaban
    
    // Pagination variables
    let soalData = [];
    let filteredData = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    // ============================================
    // TOAST NOTIFICATION SYSTEM
    // ============================================
    /**
     * Menampilkan toast notification
     * @param {string} message - Pesan yang akan ditampilkan
     * @param {string} type - Tipe toast: 'success', 'error', atau 'warning'
     * @param {number} duration - Durasi tampil dalam ms (default: 3000)
     */
    function showToast(message, type = 'success', duration = 3000) {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        // Icon berdasarkan tipe
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠'
        };
        
        // Judul berdasarkan tipe
        const titles = {
            success: 'Berhasil',
            error: 'Gagal',
            warning: 'Peringatan'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove setelah durasi
        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // ============================================
    // DATA LOADING FUNCTIONS
    // ============================================
    
    /**
     * Load categories untuk dropdown filter dan form
     * Menggunakan Select2 untuk searchable dropdown
     */
    async function loadCategories() {
        try {
            const response = await fetch('{{ route("admin.kategori.data") }}');
            const result = await response.json();
            const select = document.getElementById('kategoriId');
            const filterSelect = document.getElementById('kategoriFilter');
            
            // Populate dropdown options
            result.data.forEach(item => {
                select.innerHTML += `<option value="${item.id}">${item.nama_kategori}</option>`;
                filterSelect.innerHTML += `<option value="${item.id}">${item.nama_kategori}</option>`;
            });
            
            // Initialize Select2 hanya jika belum diinisialisasi
            if (!$('#kategoriId').hasClass('select2-hidden-accessible')) {
                $('#kategoriId').select2({
                    placeholder: 'Pilih Kategori',
                    allowClear: false,
                    width: '100%',
                    dropdownParent: $('#soalModal')
                });
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            showToast('Gagal memuat data kategori', 'error');
        }
    }

    /**
     * Load kursus list untuk dropdown filter
     */
    async function loadKursusList() {
        try {
            const response = await fetch('{{ route("admin.bank-soal.kursus-list") }}');
            const result = await response.json();
            const filterSelect = document.getElementById('kursusFilter');
            
            result.data.forEach(item => {
                filterSelect.innerHTML += `<option value="${item.id}">${item.judul}</option>`;
            });
        } catch (error) {
            console.error('Error loading kursus:', error);
            showToast('Gagal memuat data kursus', 'error');
        }
    }

    /**
     * Load data soal dari server dengan filter
     * Mendukung pencarian, filter kategori, kursus, dan tipe soal
     */
    async function loadData() {
        try {
            // Ambil nilai filter
            const search = document.getElementById('searchInput').value;
            const kategori = document.getElementById('kategoriFilter').value;
            const kursus = document.getElementById('kursusFilter').value;
            const tipe = document.getElementById('tipeFilter').value;
            
            // Build query parameters
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (kategori) params.append('kategori', kategori);
            if (kursus) params.append('kursus', kursus);
            if (tipe) params.append('tipe_soal', tipe);
            
            // Fetch data dari server
            const response = await fetch(`{{ route("admin.bank-soal.data") }}?${params.toString()}`);
            const result = await response.json();
            
            soalData = result.data;
            filteredData = soalData;
            currentPage = 1;
            
            renderTable();
            renderPagination();
        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('soalTableBody').innerHTML = '<tr><td colspan="6">Error loading data</td></tr>';
            updatePaginationInfo(0, 0, 0);
            showToast('Gagal memuat data soal', 'error');
        }
    }

    /**
     * Render table dengan pagination
     */
    function renderTable() {
        const tbody = document.getElementById('soalTableBody');
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);
        
        // Handle empty data
        if (filteredData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">Tidak ada data soal</td></tr>';
            updatePaginationInfo(0, 0, 0);
            return;
        }

        // Render table rows
        tbody.innerHTML = paginatedData.map((item, index) => `
            <tr onclick="viewSoal(${item.id})" style="cursor: pointer;">
                <td>${startIndex + index + 1}</td>
                <td style="text-align:left; max-width:300px;">${item.pertanyaan.substring(0, 80)}${item.pertanyaan.length > 80 ? '...' : ''}</td>
                <td>${item.kategori}</td>
                <td><span class="type-badge ${item.tipe_soal}">${item.tipe_soal === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Pilihan Ganda Multi'}</span></td>
                <td>${item.poin || 1}</td>
                <td onclick="event.stopPropagation()">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editSoal(${item.id})" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteSoal(${item.id})" title="Hapus">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        updatePaginationInfo(startIndex + 1, Math.min(endIndex, filteredData.length), filteredData.length);
    }

    // Update info pagination
    function updatePaginationInfo(start, end, total) {
        document.getElementById('showingStart').textContent = total > 0 ? start : 0;
        document.getElementById('showingEnd').textContent = end;
        document.getElementById('totalData').textContent = total;
    }

    // Render pagination buttons
    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const container = document.getElementById('paginationButtons');
        
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '';
        
        // Previous button
        html += `<button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                        onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>`;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                                onclick="goToPage(${i})">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span class="pagination-ellipsis">...</span>`;
            }
        }
        
        // Next button
        html += `<button class="pagination-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                        onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>`;
        
        container.innerHTML = html;
    }

    // Go to specific page
    function goToPage(page) {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
        renderPagination();
    }

    function toggleOpsi() {
        const tipe = document.getElementById('tipeSoal').value;
        const opsiContainer = document.getElementById('opsiContainer');
        const opsiList = document.getElementById('opsiList');
        
        if (tipe === 'pilihan_ganda' || tipe === 'multi_jawaban') {
            opsiContainer.classList.add('active');
            // Initialize with 2 options if empty
            if (opsiList.children.length === 0) {
                addOpsi();
                addOpsi();
            }
            updateInputTypes();
        } else {
            opsiContainer.classList.remove('active');
        }
    }

    function updateInputTypes() {
        const tipe = document.getElementById('tipeSoal').value;
        const inputType = tipe === 'pilihan_ganda' ? 'radio' : 'checkbox';
        const opsiList = document.getElementById('opsiList');
        
        opsiList.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            const parent = input.parentElement;
            const isChecked = input.checked;
            const newInput = document.createElement('input');
            newInput.type = inputType;
            newInput.name = 'jawaban_benar';
            newInput.value = input.value;
            newInput.checked = isChecked;
            parent.replaceChild(newInput, input);
        });
    }

    /**
     * Tambah pilihan jawaban baru
     * Maksimal 6 pilihan jawaban
     */
    function addOpsi() {
        const opsiList = document.getElementById('opsiList');
        
        // Validasi maksimal 6 opsi
        if (opsiList.children.length >= MAX_OPTIONS) {
            showToast(`Maksimal ${MAX_OPTIONS} pilihan jawaban`, 'warning');
            return;
        }
        
        opsiCounter++;
        const tipe = document.getElementById('tipeSoal').value;
        const inputType = tipe === 'pilihan_ganda' ? 'radio' : 'checkbox';
        
        const opsiItem = document.createElement('div');
        opsiItem.className = 'opsi-item';
        opsiItem.innerHTML = `
            <input type="${inputType}" name="jawaban_benar" value="${opsiCounter}">
            <input type="text" class="form-input" placeholder="Tulis pilihan jawaban" data-opsi-id="${opsiCounter}">
            <button type="button" class="btn-remove" onclick="removeOpsi(this)">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        `;
        
        opsiList.appendChild(opsiItem);
    }

    /**
     * Hapus pilihan jawaban
     * Minimal 2 opsi harus tetap ada
     */
    function removeOpsi(button) {
        const opsiList = document.getElementById('opsiList');
        if (opsiList.children.length > 2) {
            button.parentElement.remove();
        } else {
            showToast('Minimal harus ada 2 pilihan jawaban', 'warning');
        }
    }

    function updateFileLabel() {
        const fileInput = document.getElementById('lampiran');
        const fileLabel = document.getElementById('fileLabel');
        const fileText = document.getElementById('fileText');
        
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            fileText.textContent = fileName;
            fileLabel.classList.add('has-file');
        } else {
            fileText.textContent = 'Pilih file atau drag & drop (JPG, PNG, PDF)';
            fileLabel.classList.remove('has-file');
        }
    }

    function openAddModal() {
        editingId = null;
        opsiCounter = 0;
        document.getElementById('modalTitle').textContent = 'Tambah Soal';
        document.getElementById('modalSubtitle').textContent = 'Isi form di bawah untuk menambahkan soal';
        document.getElementById('soalForm').reset();
        document.getElementById('opsiList').innerHTML = '';
        document.getElementById('opsiContainer').classList.remove('active');
        
        // Enable inputs
        document.getElementById('poin').disabled = false;
        document.getElementById('lampiran').disabled = false;
        
        // Show tambah pilihan button
        const btnAddOpsi = document.getElementById('btnAddOpsi');
        if (btnAddOpsi) btnAddOpsi.style.display = 'flex';
        
        // Show submit button
        document.querySelector('.btn-submit').style.display = 'inline-block';
        
        updateFileLabel();
        document.getElementById('soalModal').classList.add('active');
    }

    /**
     * Melihat detail soal dalam mode read-only
     * @param {number} id - ID soal yang akan dilihat
     */
    async function viewSoal(id) {
        try {
            const response = await fetch(`{{ route("admin.bank-soal.index") }}/${id}`);
            const result = await response.json();
            const soal = result.data;
            
            // Set modal title
            document.getElementById('modalTitle').textContent = 'Detail Soal';
            
            // Fill form with data
            document.getElementById('pertanyaan').value = soal.pertanyaan;
            document.getElementById('tipeSoal').value = soal.tipe_soal;
            
            // Set kategori with Select2
            $('#kategoriId').val(soal.kategori_id || '').trigger('change');
            
            // Load opsi jawaban if exists
            if (soal.tipe_soal === 'pilihan_ganda' || soal.tipe_soal === 'multi_jawaban') {
                const opsiContainer = document.getElementById('opsiContainer');
                const opsiList = document.getElementById('opsiList');
                opsiContainer.classList.add('active');
                opsiList.innerHTML = '';
                
                const opsiJawaban = soal.opsi_jawaban || [];
                const jawabanBenar = Array.isArray(soal.jawaban_benar) ? soal.jawaban_benar : [soal.jawaban_benar];
                
                opsiJawaban.forEach((opsi, index) => {
                    opsiCounter++;
                    const inputType = soal.tipe_soal === 'pilihan_ganda' ? 'radio' : 'checkbox';
                    const isChecked = jawabanBenar.includes(index);
                    
                    const opsiDiv = document.createElement('div');
                    opsiDiv.className = 'opsi-item';
                    opsiDiv.innerHTML = `
                        <input type="${inputType}" name="jawaban_benar" value="${opsiCounter}" ${isChecked ? 'checked' : ''} disabled>
                        <input type="text" class="form-input" value="${opsi}" readonly>
                        <button type="button" class="btn-remove" style="visibility: hidden;">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    `;
                    opsiList.appendChild(opsiDiv);
                });
            }
            
            // Disable all inputs (read-only mode)
            document.getElementById('pertanyaan').readOnly = true;
            document.getElementById('tipeSoal').disabled = true;
            $('#kategoriId').prop('disabled', true);
            document.getElementById('poin').disabled = true;
            document.getElementById('lampiran').disabled = true;
            
            // Hide tambah pilihan button
            const btnAddOpsi = document.getElementById('btnAddOpsi');
            if (btnAddOpsi) btnAddOpsi.style.display = 'none';
            
            // Hide submit button, show only close
            document.querySelector('.btn-submit').style.display = 'none';
            
            document.getElementById('soalModal').classList.add('active');
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal memuat detail soal', 'error');
        }
    }

    /**
     * Edit soal yang sudah ada
     * Load data soal dan populate form
     * @param {number} id - ID soal yang akan diedit
     */
    async function editSoal(id) {
        try {
            const response = await fetch(`{{ route("admin.bank-soal.index") }}/${id}`);
            const result = await response.json();
            const soal = result.data;
            
            editingId = id;
            document.getElementById('modalTitle').textContent = 'Edit Soal';
            
            // Fill form with data
            document.getElementById('pertanyaan').value = soal.pertanyaan;
            document.getElementById('tipeSoal').value = soal.tipe_soal;
            document.getElementById('poin').value = soal.poin || '1';
            
            // Set Select2 value
            $('#kategoriId').val(soal.kategori_id || '').trigger('change');
            
            // Load opsi jawaban if exists
            if (soal.tipe_soal === 'pilihan_ganda' || soal.tipe_soal === 'multi_jawaban') {
                const opsiContainer = document.getElementById('opsiContainer');
                const opsiList = document.getElementById('opsiList');
                opsiContainer.classList.add('active');
                opsiList.innerHTML = '';
                opsiCounter = 0;
                
                const opsiJawaban = soal.opsi_jawaban || [];
                const jawabanBenar = Array.isArray(soal.jawaban_benar) ? soal.jawaban_benar : [soal.jawaban_benar];
                
                if (opsiJawaban.length > 0) {
                    opsiJawaban.forEach((opsi, index) => {
                        opsiCounter++;
                        const inputType = soal.tipe_soal === 'pilihan_ganda' ? 'radio' : 'checkbox';
                        const isChecked = jawabanBenar.includes(index);
                        
                        const opsiDiv = document.createElement('div');
                        opsiDiv.className = 'opsi-item';
                        opsiDiv.innerHTML = `
                            <input type="${inputType}" name="jawaban_benar" value="${opsiCounter}" ${isChecked ? 'checked' : ''}>
                            <input type="text" class="form-input" placeholder="Tulis pilihan jawaban" value="${opsi}">
                            <button type="button" class="btn-remove" onclick="removeOpsi(this)" ${opsiList.children.length < 2 ? 'disabled' : ''}>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        `;
                        opsiList.appendChild(opsiDiv);
                    });
                } else {
                    // Default 2 opsi if no data
                    addOpsi();
                    addOpsi();
                }
                
                updateInputTypes();
            }
            
            // Enable all inputs (edit mode)
            document.getElementById('pertanyaan').readOnly = false;
            document.getElementById('tipeSoal').disabled = false;
            $('#kategoriId').prop('disabled', false);
            document.getElementById('poin').disabled = false;
            document.getElementById('lampiran').disabled = false;
            
            // Show tambah pilihan button
            const btnAddOpsi = document.getElementById('btnAddOpsi');
            if (btnAddOpsi) btnAddOpsi.style.display = 'flex';
            
            // Show submit button
            document.querySelector('.btn-submit').style.display = 'inline-block';
            
            document.getElementById('soalModal').classList.add('active');
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal memuat data soal', 'error');
        }
    }

    /**
     * Hapus soal dengan konfirmasi
     * @param {number} id - ID soal yang akan dihapus
     */
    async function deleteSoal(id) {
        if (!confirm('Yakin ingin menghapus soal ini?')) return;
        
        try {
            const response = await fetch(`{{ route("admin.bank-soal.index") }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                showToast('Soal berhasil dihapus', 'success');
                loadData();
            } else {
                showToast(result.message || 'Gagal menghapus soal', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus soal', 'error');
        }
    }

    /**
     * Handle submit form soal
     * Menggunakan FormData untuk mendukung file upload
     */
    document.getElementById('soalForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('pertanyaan', document.getElementById('pertanyaan').value);
        formData.append('tipe_soal', document.getElementById('tipeSoal').value);
        formData.append('kategori_id', document.getElementById('kategoriId').value);
        formData.append('poin', document.getElementById('poin').value);
        
        const tipe = document.getElementById('tipeSoal').value;
        if (tipe === 'pilihan_ganda' || tipe === 'multi_jawaban') {
            const opsiList = document.getElementById('opsiList');
            const opsi = [];
            const jawabanBenar = [];
            
            // Validasi maksimal 6 opsi
            if (opsiList.children.length > MAX_OPTIONS) {
                showToast(`Maksimal ${MAX_OPTIONS} pilihan jawaban`, 'warning');
                return;
            }
            
            opsiList.querySelectorAll('.opsi-item').forEach((item, index) => {
                const textInput = item.querySelector('input[type="text"]');
                const checkInput = item.querySelector('input[type="radio"], input[type="checkbox"]');
                
                if (textInput && textInput.value.trim()) {
                    opsi.push(textInput.value.trim());
                    if (checkInput && checkInput.checked) {
                        jawabanBenar.push(index);
                    }
                }
            });
            
            if (opsi.length < 2) {
                showToast('Minimal harus ada 2 pilihan jawaban', 'warning');
                return;
            }
            
            if (jawabanBenar.length === 0) {
                showToast('Pilih minimal satu jawaban yang benar', 'warning');
                return;
            }
            
            formData.append('opsi_jawaban', JSON.stringify(opsi));
            formData.append('jawaban_benar', JSON.stringify(tipe === 'pilihan_ganda' ? jawabanBenar[0] : jawabanBenar));
        }
        
        // Tambahkan file jika ada
        const file = document.getElementById('lampiran').files[0];
        if (file) formData.append('lampiran', file);

        try {
            const url = editingId 
                ? `{{ route("admin.bank-soal.index") }}/${editingId}`
                : '{{ route("admin.bank-soal.store") }}';
            
            // Tambahkan _method untuk update
            if (editingId) {
                formData.append('_method', 'PUT');
            }
            
            // Submit dengan FormData (multipart/form-data untuk file upload)
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    // JANGAN set Content-Type, biarkan browser set otomatis
                },
                body: formData // Kirim FormData langsung, JANGAN JSON.stringify()
            });

            // Validasi response sebelum parse JSON
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error:', errorText);
                showToast('Terjadi kesalahan dari server. Periksa console untuk detail.', 'error');
                return;
            }

            const result = await response.json();
            
            if (result.success) {
                showToast(editingId ? 'Soal berhasil diupdate' : 'Soal berhasil ditambahkan', 'success');
                closeModal();
                loadData();
            } else {
                showToast(result.message || 'Gagal menyimpan soal', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menyimpan soal', 'error');
        }
    });

    function closeModal() {
        document.getElementById('soalModal').classList.remove('active');
        document.getElementById('soalForm').reset();
        document.getElementById('opsiList').innerHTML = '';
        opsiCounter = 0;
        editingId = null;
        
        // Reset read-only states
        document.getElementById('pertanyaan').readOnly = false;
        document.getElementById('tipeSoal').disabled = false;
        $('#kategoriId').prop('disabled', false);
        document.getElementById('poin').disabled = false;
        document.getElementById('lampiran').disabled = false;
        
        // Show tambah pilihan button
        const btnAddOpsi = document.getElementById('btnAddOpsi');
        if (btnAddOpsi) btnAddOpsi.style.display = 'flex';
        
        // Show submit button
        document.querySelector('.btn-submit').style.display = 'inline-block';
        
        updateFileLabel();
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('active');
    }

    // ============================================
    // IMPORT & EXPORT FUNCTIONS
    // ============================================
    function openImportModal() {
        document.getElementById('importModal').classList.add('active');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.remove('active');
        document.getElementById('importForm').reset();
        document.getElementById('importFileText').textContent = 'Pilih file CSV atau drag & drop';
        document.getElementById('importFileLabel').classList.remove('has-file');
    }

    function updateImportFileLabel() {
        const input = document.getElementById('importFile');
        const label = document.getElementById('importFileLabel');
        const text = document.getElementById('importFileText');
        
        if (input.files && input.files[0]) {
            text.textContent = input.files[0].name;
            label.classList.add('has-file');
        } else {
            text.textContent = 'Pilih file CSV atau drag & drop';
            label.classList.remove('has-file');
        }
    }

    document.getElementById('importForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('importFile');
        if (!fileInput.files || !fileInput.files[0]) {
            showToast('Pilih file CSV terlebih dahulu', 'warning');
            return;
        }
        
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        
        const btnImport = document.getElementById('btnImport');
        const originalText = btnImport.textContent;
        btnImport.disabled = true;
        btnImport.textContent = 'Mengimport...';
        
        try {
            const response = await fetch('{{ route("admin.bank-soal.import-csv") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                closeImportModal();
                loadData();
                
                // Show errors if any
                if (result.errors && result.errors.length > 0) {
                    setTimeout(() => {
                        alert('Beberapa baris gagal diimport:\n\n' + result.errors.join('\n'));
                    }, 500);
                }
            } else {
                showToast(result.message || 'Gagal mengimport file', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengimport file', 'error');
        } finally {
            btnImport.disabled = false;
            btnImport.textContent = originalText;
        }
    });

    function exportCsv() {
        // Get current filters
        const search = document.getElementById('searchInput').value;
        const tipe = document.getElementById('tipeFilter').value;
        const kursus = document.getElementById('kursusFilter').value;
        const kategori = document.getElementById('kategoriFilter').value;
        
        // Build query string
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (tipe) params.append('tipe_soal', tipe);
        if (kursus) params.append('kursus', kursus);
        if (kategori) params.append('kategori', kategori);
        
        // Open export URL with filters
        const url = '{{ route("admin.bank-soal.export-csv") }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        loadKursusList();
        loadData();
    });
</script>
@endpush
