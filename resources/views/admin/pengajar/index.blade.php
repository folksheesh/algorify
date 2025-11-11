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
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Custom CSS untuk halaman pengajar --}}
    <link rel="stylesheet" href="{{ asset('css/admin/pengajar.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- Sidebar Navigation --}}
        @include('components.sidebar')
        
        {{-- Main Content Area --}}
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
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
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Tambah Pengajar
                            </button>
                        </div>
                    </div>

                    {{-- Tabel Data Pengajar --}}
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            {{-- Header Tabel --}}
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Pengajar</th>
                                    <th>Email</th>
                                    <th>Kursus yang Diajarkan</th>
                                    <th>Status</th>
                                    <th>Jumlah Kelas</th>
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
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
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
                    <input type="text" class="form-input" id="detailAddress" readonly>
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
                    <input type="text" class="form-input" id="detailKursus" readonly>
                </div>

                {{-- Jumlah Kelas dan Total Siswa (2 kolom) --}}
                <div class="form-row-2">
                    <div>
                        <label class="form-label">Jumlah Kelas</label>
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
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
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
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-input" id="formPhone" placeholder="08xxxxxxxxxx">
                        <div class="error-message" id="phoneError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" id="formTanggalLahir">
                    </div>
                </div>

                {{-- Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="passwordGroup">
                    <label class="form-label" id="passwordLabel">Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPassword" placeholder="Minimal 8 karakter" style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPassword', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <small style="color: #64748B; font-size: 0.75rem; display: none;" id="passwordHint">Kosongkan jika tidak ingin mengubah password</small>
                    <div class="error-message" id="passwordError"></div>
                </div>

                {{-- Konfirmasi Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="confirmPasswordGroup">
                    <label class="form-label" id="confirmPasswordLabel">Konfirmasi Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPasswordConfirm" placeholder="Ulangi password" style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPasswordConfirm', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Alamat (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-input" id="formAddress" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                {{-- Jenis Kelamin dan Status (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-input" id="formJenisKelamin">
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
                    <textarea class="form-input" id="formKeahlian" rows="2" placeholder="Contoh: Python, Machine Learning, Data Science"></textarea>
                </div>

                {{-- Pengalaman (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Pengalaman</label>
                    <textarea class="form-input" id="formPengalaman" rows="3" placeholder="Jelaskan pengalaman mengajar atau bekerja"></textarea>
                </div>

                {{-- Upload Sertifikasi dengan Drag & Drop --}}
                <div class="form-group">
                    <label class="form-label">Upload Sertifikasi</label>
                    <input type="file" id="formSertifikasi" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                    
                    {{-- Upload Area (Click or Drag & Drop) --}}
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('formSertifikasi').click()">
                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="upload-text">Klik atau drag & drop file</div>
                        <div class="upload-hint">PDF, JPG, PNG (Max: 2MB)</div>
                    </div>
                    
                    {{-- File Preview (akan tampil setelah file dipilih) --}}
                    <div class="file-preview" id="filePreview">
                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="file-remove" onclick="removeFile(event)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    {{-- Info file sertifikasi yang sudah ada (untuk mode Edit) --}}
                    <div id="currentSertifikasi" style="margin-top: 0.5rem; display: none;">
                        <small style="color: #059669; font-size: 0.75rem;">📎 File saat ini: <span id="sertifikasiFileName"></span></small>
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
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Error</div>
            <div class="toast-message" id="toastMessage">Terjadi kesalahan</div>
        </div>
    </div>
@endsection

{{-- SCRIPTS SECTION - Load JavaScript untuk halaman ini --}}
@push('scripts')
    <script>
        {{-- Set tema aplikasi --}}
        document.documentElement.setAttribute('data-bs-theme', 'light');

        {{-- CONFIG - CSRF Token dan API Routes untuk JavaScript --}}
        const csrfToken = '{{ csrf_token() }}';
        const apiRoutes = {
            getData: '{{ route("admin.pengajar.data") }}',
            store: '{{ route("admin.pengajar.store") }}'
        };
    </script>
    
    {{-- Load External JavaScript File --}}
    <script src="{{ asset('js/admin/pengajar.js') }}"></script>
@endpush
