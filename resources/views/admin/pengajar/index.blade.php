{{--
========================================
HALAMAN DATA PENGAJAR - ADMIN
========================================
Halaman untuk mengelola data pengajar
Features: CRUD, Search, Filter, Export
========================================
--}}

@extends('layouts.template')

@section('title', 'Data Pengajar - Admin')

{{-- Load CSS dan Fonts --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin/pengajar-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- Sidebar Navigation --}}
        @include('components.sidebar')

        {{-- Main Content Area --}}
        <main class="main-content">
            <div class="content-padding">
                {{-- Page Header --}}
                <div class="page-header">
                    <h1>Halaman Data Pengajar</h1>
                </div>

                {{-- Table Container dengan Search & Filter --}}
                <div class="table-container">
                    {{-- Header: Search Box, Filter Status, dan Tombol Tambah --}}
                    <div class="table-header">
                        {{-- Search Box --}}
                        <div class="search-box">
                            {{-- Icon Search --}}
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            {{-- Input pencarian (nama, email, kursus) --}}
                            <input type="text" id="searchInput" placeholder="Cari nama, email, atau kursus.....">
                        </div>

                        {{-- Filter Actions: Status & Tombol Tambah --}}
                        <div class="filter-actions">
                            {{-- Dropdown Filter Status --}}
                            <select id="statusFilter" class="status-dropdown">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>

                            {{-- Tombol Tambah Pengajar Baru --}}
                            <button class="btn-add" onclick="openAddModal()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambah Pengajar
                            </button>
                        </div>
                    </div>

                    {{-- Tabel Data Pengajar --}}
                    <div class="table-wrapper">
                        <table class="data-table">
                            {{-- Header Tabel --}}
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Pengajar</th>
                                    <th>Email</th>
                                    <th>Kursus yang Diajarkan</th>
                                    <th>Status</th>
                                    <th>Jumlah Kursus</th>
                                    <th>Total Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- Body tabel - akan diisi via JavaScript --}}
                            <tbody id="pengajarTableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div id="paginationContainer" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem;"></div>
                    
                    <!-- Hint klik row -->
                    <div class="table-hint" style="margin-top: 0.75rem; text-align: center;">
                        <span style="font-size: 0.75rem; color: #94A3B8;">
                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Klik baris untuk melihat detail pengajar
                        </span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- ========================================
    MODAL: DETAIL PENGAJAR (READ-ONLY)
    ======================================== --}}
    <div id="detailModal" class="modal-overlay">
        <div class="modal-content">
            {{-- Tombol Close Modal --}}
            <button class="modal-close" onclick="closeModal('detailModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal --}}
            <div class="modal-header">
                <h2>Detail Data Pengajar</h2>
                <p>Informasi lengkap mengenai pengajar</p>
            </div>

            {{-- Form Detail (Read-Only) --}}
            <form id="detailForm">
                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" id="detailName" readonly>
                </div>

                {{-- Email dan No. Telepon (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="detailEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-input" id="detailPhone" readonly>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-input" id="detailAlamat" readonly>
                </div>

                {{-- Tanggal Lahir dan Jenis Kelamin (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" id="detailTanggalLahir" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-input" id="detailJenisKelamin" readonly>
                    </div>
                </div>

                {{-- Kursus yang Diajarkan --}}
                <div class="form-group">
                    <label class="form-label">Kursus yang Diajarkan</label>
                    <textarea class="form-input" id="detailKursus" rows="2" readonly></textarea>
                </div>

                {{-- Jumlah Kursus dan Total Siswa (2 kolom) --}}
                <div class="form-row-2">
                    <div>
                        <label class="form-label">Jumlah Kursus</label>
                        <input type="text" class="form-input" id="detailJumlahKelas" readonly>
                    </div>
                    <div>
                        <label class="form-label">Total Siswa</label>
                        <input type="text" class="form-input" id="detailTotalSiswa" readonly>
                    </div>
                </div>

                {{-- Status dan Tanggal Bergabung (2 kolom) --}}
                <div class="form-row-2">
                    <div>
                        <label class="form-label">Status</label>
                        <input type="text" class="form-input" id="detailStatus" readonly>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" class="form-input" id="detailDate" readonly>
                    </div>
                </div>

                {{-- Keahlian (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Keahlian</label>
                    <textarea class="form-input" id="detailKeahlian" rows="2" readonly></textarea>
                </div>

                {{-- Pengalaman (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Pengalaman</label>
                    <textarea class="form-input" id="detailPengalaman" rows="2" readonly></textarea>
                </div>

                {{-- Sertifikasi (akan diisi via JS) --}}
                <div class="form-group">
                    <label class="form-label">Sertifikasi</label>
                    <div id="detailSertifikasiContainer" style="margin-top: 0.5rem;"></div>
                </div>

                {{-- Action Button --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('detailModal')">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ADD/EDIT PENGAJAR - Form untuk menambah atau mengedit data pengajar --}}
    <div id="formModal" class="modal-overlay">
        <div class="modal-content">
            {{-- Tombol Close --}}
            <button class="modal-close" onclick="closeModal('formModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal (title berubah sesuai mode add/edit) --}}
            <div class="modal-header">
                <h2 id="formModalTitle">Tambah Pengajar Baru</h2>
                <p id="formModalDesc">Lengkapi form di bawah untuk menambah pengajar</p>
            </div>

            {{-- Form Input Data Pengajar --}}
            <form id="pengajarForm" enctype="multipart/form-data">
                <input type="hidden" id="pengajarId">

                {{-- Nama Lengkap (Required) --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" class="form-input" id="formName" required placeholder="Masukkan nama lengkap">
                    <div class="error-message" id="nameError"></div>
                </div>

                {{-- Email (Required) --}}
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" id="formEmail" required placeholder="contoh@email.com">
                    <div class="error-message" id="emailError"></div>
                </div>

                {{-- No Telepon dan Tanggal Lahir (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">No. Telepon *</label>
                        <input type="text" class="form-input" id="formPhone" required placeholder="08xxxxxxxxxx">
                        <div class="error-message" id="phoneError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir *</label>
                        <input type="date" class="form-input" id="formTanggalLahir" required>
                    </div>
                </div>

                {{-- Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="passwordGroup">
                    <label class="form-label" id="passwordLabel">Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPassword" placeholder="Minimal 8 karakter"
                            style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPassword', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <small style="color: #64748B; font-size: 0.75rem; display: none;" id="passwordHint">Kosongkan jika tidak
                        ingin mengubah password</small>
                    <div class="error-message" id="passwordError"></div>
                </div>

                {{-- Konfirmasi Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="confirmPasswordGroup">
                    <label class="form-label" id="confirmPasswordLabel">Konfirmasi Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPasswordConfirm" placeholder="Ulangi password"
                            style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPasswordConfirm', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Alamat (Kabupaten/Kota) --}}
                <div class="form-group">
                    <label class="form-label">Alamat (Kabupaten/Kota) *</label>
                    <select class="form-input" id="formAlamat" required>
                        <option value="">Pilih kabupaten/kota</option>
                    </select>
                </div>

                {{-- Jenis Kelamin dan Status (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select class="form-input" id="formJenisKelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="formStatus">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="suspended">Ditangguhkan</option>
                        </select>
                    </div>
                </div>

                {{-- Keahlian (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Keahlian</label>
                    <textarea class="form-input" id="formKeahlian" rows="2"
                        placeholder="Contoh: Python, Machine Learning, Data Science"></textarea>
                </div>

                {{-- Pengalaman (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Pengalaman</label>
                    <textarea class="form-input" id="formPengalaman" rows="3"
                        placeholder="Jelaskan pengalaman mengajar atau bekerja"></textarea>
                </div>

                {{-- Upload Sertifikasi dengan Drag & Drop --}}
                <div class="form-group">
                    <label class="form-label">Upload Sertifikasi</label>
                    <input type="file" id="formSertifikasi" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">

                    {{-- Upload Area (Click or Drag & Drop) --}}
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('formSertifikasi').click()">
                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="upload-text">Klik atau drag & drop file</div>
                        <div class="upload-hint">PDF, JPG, PNG (Max: 2MB)</div>
                    </div>

                    {{-- File Preview (akan tampil setelah file dipilih) --}}
                    <div class="file-preview" id="filePreview">
                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="file-remove" onclick="removeFile(event)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Info file sertifikasi yang sudah ada (untuk mode Edit) --}}
                    <div id="currentSertifikasi" style="margin-top: 0.5rem; display: none;">
                        <small style="color: #059669; font-size: 0.75rem;">📎 File saat ini: <span
                                id="sertifikasiFileName"></span></small>
                    </div>
                    <div class="error-message" id="fileError"></div>
                </div>

                {{-- Action Buttons - Batal dan Simpan --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('formModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS - Popup konfirmasi sebelum menghapus pengajar --}}
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px;">
            {{-- Tombol Close --}}
            <button class="modal-close" onclick="closeModal('deleteModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal Konfirmasi --}}
            <div class="modal-header">
                <h2>Konfirmasi Hapus</h2>
                <p>Apakah Anda yakin ingin menghapus pengajar ini?</p>
            </div>

            {{-- Action Buttons - Batal dan Hapus --}}
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('deleteModal')">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- MODAL SUCCESS - Popup success setelah aksi berhasil dilakukan --}}
    <div id="successModal" class="modal-overlay">
        <div class="success-modal">
            {{-- Icon Success (checkmark) --}}
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            {{-- Title dan Message (akan diupdate via JS) --}}
            <h3 class="success-title" id="successTitle">Berhasil!</h3>
            <p class="success-message" id="successMessage">Data pengajar berhasil ditambahkan</p>
            <button class="btn btn-submit" onclick="closeModal('successModal')" style="width: 100%;">OK</button>
        </div>
    </div>

    {{-- TOAST NOTIFICATION - Notifikasi error/warning di pojok kanan atas --}}
    <div id="toastNotification" class="toast-notification">
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Error</div>
            <div class="toast-message" id="toastMessage">Terjadi kesalahan</div>
        </div>
    </div>

{{-- SCRIPTS SECTION - Load JavaScript untuk halaman ini --}}
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/indonesia-cities.js') }}"></script>
    <script>
        // Initialize Select2 for alamat dropdown dengan data kota
        $(document).ready(function() {
            const alamatSelect = $('#formAlamat');
            
            // Populate cities
            indonesiaCities.forEach(function(city) {
                alamatSelect.append(new Option(city, city, false, false));
            });
            
            // Initialize Select2
            alamatSelect.select2({
                placeholder: 'Pilih kabupaten/kota',
                allowClear: false,
                dropdownParent: $('#formModal'),
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });
        });
    </script>
    <script>
        {{-- Set tema aplikasi --}}
        document.documentElement.setAttribute('data-bs-theme', 'light');

        {{-- CONFIG - CSRF Token dan API Routes untuk JavaScript --}}
        const csrfToken = '{{ csrf_token() }}';
        const apiRoutes = {
            getData: '{{ route("admin.pengajar.data") }}',
            store: '{{ route("admin.pengajar.store") }}'
        };

        /* ========================================
       PENGAJAR MANAGEMENT JAVASCRIPT
       Script untuk halaman Data Pengajar Admin
       ======================================== */

        // ========================================
        // GLOBAL VARIABLES
        // ========================================

        let pengajarData = [];  // Array untuk menyimpan semua data pengajar
        let deleteId = null;    // ID pengajar yang akan dihapus
        let currentPage = 1;  // Halaman saat ini
        let totalPages = 1;   // Total halaman pagination
        let searchTimeout = null; // Timeout untuk debounce search
        let currentSearch = '';   // Kata kunci search saat ini
        let currentStatusFilter = ''; // Filter status saat ini

        // ========================================
        // NOTIFICATION FUNCTIONS
        // ========================================

        /**
         * Menampilkan toast notification di pojok kanan atas
         * @param {string} title - Judul notifikasi
         * @param {string} message - Pesan notifikasi
         * @param {string} type - Tipe notifikasi ('error' atau 'warning')
         */
        function showToast(title, message, type = 'error') {
            const toast = document.getElementById('toastNotification');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');

            toast.className = 'toast-notification ' + type;
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            toast.classList.add('active');

            // Auto hide setelah 4 detik
            setTimeout(() => {
                toast.classList.remove('active');
            }, 4000);
        }

        /**
         * Menampilkan error message di bawah field form
         * @param {string} fieldId - ID element error message
         * @param {string} message - Pesan error
         * @param {string} inputFieldId - ID field input yang bermasalah (untuk scroll dan focus)
         */
        function showFieldError(fieldId, message, inputFieldId = null) {
            const errorElement = document.getElementById(fieldId);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.add('active');

                // Scroll ke field yang bermasalah
                if (inputFieldId) {
                    const inputField = document.getElementById(inputFieldId);
                    if (inputField) {
                        inputField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        inputField.focus();

                        // Highlight field dengan border merah
                        inputField.style.borderColor = '#DC2626';
                        setTimeout(() => {
                            inputField.style.borderColor = '';
                        }, 3000);
                    }
                }

                // Error TIDAK auto hide - harus di-clear manual atau saat submit ulang
            }
        }

        /**
         * Clear semua error message yang aktif
         */
        function clearAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('active');
                el.textContent = '';
            });

            // Reset border color semua input
            document.querySelectorAll('.form-input').forEach(input => {
                input.style.borderColor = '';
            });
        }

        /**
         * Clear error untuk field tertentu saat user mulai mengetik
         * @param {string} inputId - ID input field
         * @param {string} errorId - ID error message element
         */
        function clearFieldError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);

            if (input && error) {
                input.addEventListener('input', function () {
                    error.classList.remove('active');
                    error.textContent = '';
                    input.style.borderColor = '';
                });
            }
        }

        // ========================================
        // DATA LOADING & RENDERING
        // ========================================

        /**
         * Load data pengajar dari server saat halaman dimuat
         */
        document.addEventListener('DOMContentLoaded', function () {
            loadPengajarData();

            // Setup auto-clear error saat user mulai mengetik
            clearFieldError('formName', 'nameError');
            clearFieldError('formEmail', 'emailError');
            clearFieldError('formPhone', 'phoneError');
            clearFieldError('formPassword', 'passwordError');
            clearFieldError('formPasswordConfirm', 'passwordError');
        });

        /**
         * Fetch data pengajar dari API dengan server-side search
         */
        function loadPengajarData(page = 1) {
            currentPage = page;
            
            // Build query string dengan search dan filter
            const params = new URLSearchParams();
            params.append('page', page);
            if (currentSearch) params.append('search', currentSearch);
            if (currentStatusFilter) params.append('status', currentStatusFilter);
            
            fetch(`${apiRoutes.getData}?${params.toString()}`)
                .then(response => {
                    return response.json();
                })
                .then(response => {
                    // Response dari Laravel pagination: {data: [], current_page, last_page, ...}
                    pengajarData = response.data;
                    currentPage = response.current_page;
                    totalPages = response.last_page;
                    renderTable(pengajarData);
                    renderPagination();
                })
                .catch(error => {
                    showToast('Gagal Memuat Data', 'Tidak dapat memuat data pengajar. Silakan refresh halaman.', 'error');
                });
        }

        /**
         * Render tabel dengan data pengajar
         * @param {Array} data - Array data pengajar yang akan ditampilkan
         */
        function renderTable(data) {
            const tbody = document.getElementById('pengajarTableBody');

            // Jika tidak ada data
            if (data.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94A3B8;">
                        Tidak ada data pengajar
                    </td>
                </tr>
            `;
                return;
            }

            // Render setiap baris data
            tbody.innerHTML = data.map((item, index) => {
                // Ambil nama kursus (max 2, sisanya ...)
                const kursusNames = item.kursus && item.kursus.length > 0
                    ? item.kursus.map(k => k.judul).slice(0, 2).join(', ') + (item.kursus.length > 2 ? '...' : '')
                    : '-';
                const jumlahKelas = item.kursus_count || 0;
                const totalSiswa = item.total_siswa || 0;
                const status = item.status || 'active';
                const statusDisplay = status === 'active' ? 'Aktif' : 'Nonaktif';
                const statusClass = status === 'active' ? 'aktif' : 'nonaktif';

                return `
            <tr onclick="showDetail('${item.id}')">
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.email}</td>
                <td>${kursusNames}</td>
                <td>
                    <span class="status-badge ${statusClass}">${statusDisplay}</span>
                </td>
                <td>${jumlahKelas}</td>
                <td>${totalSiswa}</td>
                <td onclick="event.stopPropagation()">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="openEditModal('${item.id}')" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" onclick="openDeleteModal('${item.id}')" title="Hapus">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
            }).join('');
        }

        /**
         * Format tanggal ke format Indonesia
         * @param {string} dateString - String tanggal ISO
         * @returns {string} Tanggal terformat (contoh: 15 Jan 2024)
         */
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        /**
         * Render pagination controls
         */
        function renderPagination() {
            const container = document.getElementById('paginationContainer');
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Previous button
            html += `<button onclick="loadPengajarData(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''} 
                        class="pagination-btn">
                        Sebelumnya
                    </button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<button onclick="loadPengajarData(${i})" 
                                class="pagination-btn ${i === currentPage ? 'active' : ''}">${i}</button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
            }
            
            // Next button
            html += `<button onclick="loadPengajarData(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''} 
                        class="pagination-btn">
                        Selanjutnya
                    </button>`;
            
            container.innerHTML = html;
        }

        // ========================================
        // SEARCH & FILTER FUNCTIONS
        // ========================================

        /**
         * Event listener untuk search input - dengan debounce dan server-side search
         */
        document.getElementById('searchInput').addEventListener('input', function (e) {
            // Debounce: tunggu 300ms setelah user berhenti mengetik
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                loadPengajarData(1); // Reset ke halaman 1 saat search
            }, 300);
        });

        /**
         * Event listener untuk status filter dropdown - server-side
         */
        document.getElementById('statusFilter').addEventListener('change', function (e) {
            currentStatusFilter = e.target.value;
            loadPengajarData(1); // Reset ke halaman 1 saat filter
        });

        // ========================================
        // MODAL FUNCTIONS - DETAIL
        // ========================================

        /**
         * Menampilkan modal detail pengajar (readonly)
         * @param {string} id - ID pengajar yang akan ditampilkan
         */
        function showDetail(id) {
            const pengajar = pengajarData.find(p => String(p.id) === String(id));
            if (!pengajar) return;

            const kursusNames = pengajar.kursus && pengajar.kursus.length > 0
                ? pengajar.kursus.map(k => k.judul).join(', ')
                : 'Belum ada kursus';

            document.getElementById('detailName').value = pengajar.name;
            document.getElementById('detailEmail').value = pengajar.email;
            document.getElementById('detailPhone').value = pengajar.phone || '-';
            document.getElementById('detailAlamat').value = pengajar.address || '-';
            document.getElementById('detailTanggalLahir').value = pengajar.tanggal_lahir || '';
            document.getElementById('detailJenisKelamin').value = pengajar.jenis_kelamin === 'L' ? 'Laki-laki' : pengajar.jenis_kelamin === 'P' ? 'Perempuan' : '-';
            document.getElementById('detailKeahlian').value = pengajar.keahlian || '-';
            document.getElementById('detailPengalaman').value = pengajar.pengalaman || '-';

            // Sertifikasi
            const sertifikasiContainer = document.getElementById('detailSertifikasiContainer');
            if (pengajar.sertifikasi) {
                const fileName = pengajar.sertifikasi.split('/').pop();
                const filePath = `/storage/${pengajar.sertifikasi}`;
                sertifikasiContainer.innerHTML = `<a href="${filePath}" target="_blank" style="color: #5D3FFF; text-decoration: none; font-size: 0.875rem;">📎 ${fileName}</a>`;
            } else {
                sertifikasiContainer.innerHTML = '<span style="color: #94A3B8; font-size: 0.875rem;">Tidak ada sertifikasi</span>';
            }

            document.getElementById('detailKursus').value = kursusNames;
            document.getElementById('detailJumlahKelas').value = (pengajar.kursus_count || 0) + ' Kursus';
            document.getElementById('detailTotalSiswa').value = (pengajar.total_siswa || 0) + ' Siswa';
            const statusDisplay = (pengajar.status || 'active') === 'active' ? 'Aktif' : (pengajar.status === 'inactive' ? 'Nonaktif' : 'Ditangguhkan');
            document.getElementById('detailStatus').value = statusDisplay;
            document.getElementById('detailDate').value = pengajar.created_at ? pengajar.created_at.split('T')[0] : '';

            // Show modal
            document.getElementById('detailModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - ADD
        // ========================================

        /**
         * Membuka modal untuk tambah pengajar baru
         */
        function openAddModal() {
            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Tambah Pengajar Baru';
            document.getElementById('formModalDesc').textContent = 'Lengkapi form di bawah untuk menambah pengajar';

            // Reset form
            document.getElementById('pengajarForm').reset();
            document.getElementById('pengajarId').value = '';

            // Reset Select2
            $('#formAlamat').val(null).trigger('change');

            // Password required untuk tambah data
            document.getElementById('formPassword').required = true;
            document.getElementById('formPasswordConfirm').required = true;
            document.getElementById('passwordLabel').innerHTML = 'Password *';
            document.getElementById('confirmPasswordLabel').innerHTML = 'Konfirmasi Password *';
            document.getElementById('passwordHint').style.display = 'none';

            // Show password fields
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('confirmPasswordGroup').style.display = 'block';
            document.getElementById('currentSertifikasi').style.display = 'none';

            // Clear all errors
            clearAllErrors();

            // Reset upload area
            document.getElementById('filePreview').classList.remove('active');
            document.getElementById('uploadArea').style.display = 'block';
            document.getElementById('formSertifikasi').value = '';

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - EDIT
        // ========================================

        /**
         * Membuka modal untuk edit data pengajar
         * @param {string} id - ID pengajar yang akan diedit
         */
        function openEditModal(id) {
            const pengajar = pengajarData.find(p => String(p.id) === String(id));
            if (!pengajar) return;

            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Edit Data Pengajar';
            document.getElementById('formModalDesc').textContent = 'Perbarui informasi pengajar di bawah ini';

            // Populate form dengan data yang ada
            document.getElementById('pengajarId').value = pengajar.id;
            document.getElementById('formName').value = pengajar.name;
            document.getElementById('formEmail').value = pengajar.email;
            document.getElementById('formPhone').value = pengajar.phone || '';
            document.getElementById('formAlamat').value = pengajar.address || '';
            $('#formAlamat').trigger('change'); // Update Select2
            document.getElementById('formTanggalLahir').value = pengajar.tanggal_lahir || '';
            document.getElementById('formJenisKelamin').value = pengajar.jenis_kelamin || '';
            document.getElementById('formStatus').value = pengajar.status || 'active';
            document.getElementById('formKeahlian').value = pengajar.keahlian || '';
            document.getElementById('formPengalaman').value = pengajar.pengalaman || '';
            document.getElementById('formPassword').value = '';
            document.getElementById('formPasswordConfirm').value = '';

            // Password optional untuk edit
            document.getElementById('formPassword').required = false;
            document.getElementById('formPasswordConfirm').required = false;

            // Sembunyikan field password saat edit
            document.getElementById('passwordGroup').style.display = 'none';
            document.getElementById('confirmPasswordGroup').style.display = 'none';

            // Reset upload area
            document.getElementById('filePreview').classList.remove('active');
            document.getElementById('uploadArea').style.display = 'block';
            document.getElementById('formSertifikasi').value = '';

            // Show current sertifikasi if exists
            if (pengajar.sertifikasi) {
                const fileName = pengajar.sertifikasi.split('/').pop();
                const filePath = `/storage/${pengajar.sertifikasi}`;
                const currentSertContainer = document.getElementById('currentSertifikasi');
                
                currentSertContainer.innerHTML = `
                    <div style="margin-top: 0.5rem; padding: 0.75rem; background: #F0FDF4; border-radius: 8px; border: 1px solid #BBF7D0;">
                        <small style="color: #059669; font-size: 0.75rem;">📎 File saat ini: <a href="${filePath}" target="_blank" style="color: #059669; text-decoration: underline;">${fileName}</a></small>
                    </div>
                `;
                currentSertContainer.style.display = 'block';
            } else {
                document.getElementById('currentSertifikasi').style.display = 'none';
            }

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // FORM SUBMISSION
        // ========================================

        /**
         * Handle form submit untuk tambah/edit pengajar
         */
        document.getElementById('pengajarForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Clear all previous errors
            clearAllErrors();

            const id = document.getElementById('pengajarId').value;
            const password = document.getElementById('formPassword').value;
            const passwordConfirm = document.getElementById('formPasswordConfirm').value;

            // Validasi password match saat tambah atau saat password diisi saat edit
            if (!id || password) {
                if (password !== passwordConfirm) {
                    showFieldError('passwordError', 'Password dan konfirmasi password tidak cocok!', 'formPassword');
                    return;
                }

                // Validasi panjang password minimal 8 karakter
                if (password.length < 8) {
                    showFieldError('passwordError', 'Password minimal 8 karakter!', 'formPassword');
                    return;
                }
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('name', document.getElementById('formName').value);
            formData.append('email', document.getElementById('formEmail').value);
            formData.append('phone', document.getElementById('formPhone').value);
            formData.append('address', document.getElementById('formAlamat').value);
            formData.append('tanggal_lahir', document.getElementById('formTanggalLahir').value);
            formData.append('jenis_kelamin', document.getElementById('formJenisKelamin').value);
            formData.append('status', document.getElementById('formStatus').value);
            formData.append('keahlian', document.getElementById('formKeahlian').value);
            formData.append('pengalaman', document.getElementById('formPengalaman').value);

            if (password) {
                formData.append('password', password);
            }

            const sertifikasiFile = document.getElementById('formSertifikasi').files[0];
            if (sertifikasiFile) {
                formData.append('sertifikasi', sertifikasiFile);
            }

            formData.append('_token', csrfToken);
            if (id) {
                formData.append('_method', 'PUT');
            }

            // Tentukan URL berdasarkan mode (create/update)
            const url = id ? `/admin/pengajar/${id}` : apiRoutes.store;

            // Submit data
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('formModal');

                        // Show success modal
                        document.getElementById('successTitle').textContent = id ? 'Berhasil Diupdate!' : 'Berhasil Ditambahkan!';
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('successModal').classList.add('active');

                        // Reload data
                        loadPengajarData();
                    } else {
                        // Handle validation errors dari server
                        if (data.errors) {
                            // Mapping field errors
                            const errorMapping = {
                                'name': { errorId: 'nameError', inputId: 'formName' },
                                'email': { errorId: 'emailError', inputId: 'formEmail' },
                                'password': { errorId: 'passwordError', inputId: 'formPassword' },
                                'phone': { errorId: 'phoneError', inputId: 'formPhone' },
                                'sertifikasi': { errorId: 'fileError', inputId: 'formSertifikasi' }
                            };

                            // Tampilkan error pertama dan scroll ke field tersebut
                            let firstError = true;
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const mapping = errorMapping[field];
                                if (mapping) {
                                    const errorMsg = Array.isArray(messages) ? messages[0] : messages;
                                    showFieldError(mapping.errorId, errorMsg, firstError ? mapping.inputId : null);
                                    firstError = false;
                                }
                            }

                            // Tampilkan toast untuk error umum
                            if (firstError) {
                                showToast('Validasi Gagal', data.message || 'Periksa kembali data yang Anda masukkan', 'error');
                            }
                        } else {
                            showToast('Gagal Menyimpan', data.message || 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    }
                })
                .catch(error => {
                    showToast('Gagal Menyimpan', 'Tidak dapat menyimpan data. Silakan coba lagi.', 'error');
                });
        });

        // ========================================
        // MODAL FUNCTIONS - DELETE
        // ========================================

        /**
         * Membuka modal konfirmasi hapus
         * @param {number} id - ID pengajar yang akan dihapus
         */
        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        /**
         * Konfirmasi dan proses penghapusan data
         */
        function confirmDelete() {
            if (!deleteId) return;

            fetch(`/admin/pengajar/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('deleteModal');

                        // Show success modal
                        document.getElementById('successTitle').textContent = 'Berhasil Dihapus!';
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('successModal').classList.add('active');

                        // Reload data
                        loadPengajarData();
                        deleteId = null;
                    } else {
                        closeModal('deleteModal');
                        showToast('Gagal Menghapus', data.message || 'Tidak dapat menghapus data pengajar', 'warning');
                    }
                })
                .catch(error => {
                    closeModal('deleteModal');
                    showToast('Gagal Menghapus', 'Terjadi kesalahan saat menghapus data', 'error');
                });
        }

        // ========================================
        // MODAL UTILITIES
        // ========================================

        /**
         * Menutup modal berdasarkan ID
         * @param {string} modalId - ID element modal
         */
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        /**
         * Event listener untuk close modal saat klik overlay
         */
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // ========================================
        // PASSWORD TOGGLE
        // ========================================

        /**
         * Toggle visibility password (show/hide)
         * @param {string} inputId - ID input password
         * @param {HTMLElement} button - Element tombol toggle
         */
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }

        // ========================================
        // FILE UPLOAD HANDLERS
        // ========================================

        const fileInput = document.getElementById('formSertifikasi');
        const uploadArea = document.getElementById('uploadArea');
        const filePreview = document.getElementById('filePreview');

        /**
         * Handle file input change
         */
        fileInput.addEventListener('change', function (e) {
            handleFile(this.files[0]);
        });

        /**
         * Handle drag over event
         */
        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        /**
         * Handle drag leave event
         */
        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        /**
         * Handle drop event
         */
        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            fileInput.files = e.dataTransfer.files;
            handleFile(file);
        });

        /**
         * Validasi dan preview file yang diupload
         * @param {File} file - File object yang diupload
         */
        function handleFile(file) {
            if (!file) return;

            // Clear previous error
            const fileErrorElement = document.getElementById('fileError');
            fileErrorElement.classList.remove('active');
            fileErrorElement.textContent = '';

            // Validate file type
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                showFieldError('fileError', 'Format file tidak valid! Gunakan PDF, JPG, atau PNG', 'formSertifikasi');
                fileInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showFieldError('fileError', 'Ukuran file terlalu besar! Maksimal 2MB', 'formSertifikasi');
                fileInput.value = '';
                return;
            }

            // Show preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            filePreview.classList.add('active');
            uploadArea.style.display = 'none';
        }

        /**
         * Remove selected file
         * @param {Event} e - Event object
         */
        function removeFile(e) {
            e.stopPropagation();
            fileInput.value = '';
            filePreview.classList.remove('active');
            uploadArea.style.display = 'block';
        }

        /**
         * Format ukuran file ke format yang readable
         * @param {number} bytes - Ukuran file dalam bytes
         * @returns {string} Ukuran terformat (contoh: 1.5 MB)
         */
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}    
    </script>
    @endpush

@endsection