{{--
========================================
HALAMAN DATA ADMIN - ADMIN PANEL
========================================
Halaman untuk mengelola data admin
Features: CRUD, Search, Filter, Export
========================================
--}}

@extends('layouts.template')

@section('title', 'Data Admin - Admin Panel')

{{-- Load CSS dan Fonts --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-index.css') }}">
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
                    <h1>Halaman Data Admin</h1>
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
                            {{-- Input pencarian (nama, email) --}}
                            <input type="text" id="searchInput" placeholder="Cari nama atau email.....">
                        </div>

                        {{-- Filter Actions: Status & Tombol Tambah --}}
                        <div class="filter-actions">
                            {{-- Dropdown Filter Status --}}
                            <select id="statusFilter" class="status-dropdown">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>

                            {{-- Tombol Tambah Admin Baru --}}
                            <button class="btn-add" onclick="openAddModal()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambah Admin
                            </button>
                        </div>
                    </div>

                    {{-- Tabel Data Admin --}}
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            {{-- Header Tabel --}}
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Admin</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- Body tabel - akan diisi via JavaScript --}}
                            <tbody id="adminTableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div id="paginationContainer" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem;"></div>
                </div>
            </div>
        </main>
    </div>

    {{-- ========================================
    MODAL: DETAIL ADMIN (READ-ONLY)
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
                <h2>Detail Data Admin</h2>
                <p>Informasi lengkap mengenai admin</p>
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

                {{-- Action Button --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('detailModal')">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ADD/EDIT ADMIN - Form untuk menambah atau mengedit data admin --}}
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
                <h2 id="formModalTitle">Tambah Admin Baru</h2>
                <p id="formModalDesc">Lengkapi form di bawah untuk menambah admin</p>
            </div>

            {{-- Form Input Data Admin --}}
            <form id="adminForm" enctype="multipart/form-data">
                <input type="hidden" id="adminId">

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

                {{-- Action Buttons - Batal dan Simpan --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('formModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS - Popup konfirmasi sebelum menghapus admin --}}
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
                <p>Apakah Anda yakin ingin menghapus admin ini?</p>
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
            <p class="success-message" id="successMessage">Data admin berhasil ditambahkan</p>
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
    <script>
        {{-- Set tema aplikasi --}}
        document.documentElement.setAttribute('data-bs-theme', 'light');

        {{-- CONFIG - CSRF Token dan API Routes untuk JavaScript --}}
        const csrfToken = '{{ csrf_token() }}';
        const apiRoutes = {
            getData: '{{ route("admin.admin.data") }}',
            store: '{{ route("admin.admin.store") }}'
        };

        /* ========================================
       ADMIN MANAGEMENT JAVASCRIPT
       Script untuk halaman Data Admin
       ======================================== */

        // ========================================
        // GLOBAL VARIABLES
        // ========================================

        let adminData = [];  // Array untuk menyimpan semua data admin
        let deleteId = null;    // ID admin yang akan dihapus
        let currentPage = 1;  // Halaman saat ini
        let totalPages = 1;   // Total halaman pagination

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
         * Load data admin dari server saat halaman dimuat
         */
        document.addEventListener('DOMContentLoaded', function () {
            loadAdminData();

            // Setup auto-clear error saat user mulai mengetik
            clearFieldError('formName', 'nameError');
            clearFieldError('formEmail', 'emailError');
            clearFieldError('formPhone', 'phoneError');
            clearFieldError('formPassword', 'passwordError');
            clearFieldError('formPasswordConfirm', 'passwordError');
        });

        /**
         * Fetch data admin dari API
         */
        function loadAdminData(page = 1) {
            currentPage = page;
            fetch(`${apiRoutes.getData}?page=${page}`)
                .then(response => {
                    return response.json();
                })
                .then(response => {
                    // Response dari Laravel pagination: {data: [], current_page, last_page, ...}
                    adminData = response.data;
                    currentPage = response.current_page;
                    totalPages = response.last_page;
                    renderTable(adminData);
                    renderPagination();
                })
                .catch(error => {
                    showToast('Gagal Memuat Data', 'Tidak dapat memuat data admin. Silakan refresh halaman.', 'error');
                });
        }

        /**
         * Render tabel dengan data admin
         * @param {Array} data - Array data admin yang akan ditampilkan
         */
        function renderTable(data) {
            const tbody = document.getElementById('adminTableBody');

            // Jika tidak ada data
            if (data.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: #94A3B8;">
                        Tidak ada data admin
                    </td>
                </tr>
            `;
                return;
            }

            // Render setiap baris data
            tbody.innerHTML = data.map((item, index) => {
                const status = item.status || 'active';
                const statusDisplay = status === 'active' ? 'Aktif' : 'Nonaktif';
                const statusClass = status === 'active' ? 'aktif' : 'nonaktif';
                const phone = item.phone || '-';

                return `
            <tr onclick="showDetail(${item.id})">
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.email}</td>
                <td>${phone}</td>
                <td>
                    <span class="status-badge ${statusClass}">${statusDisplay}</span>
                </td>
                <td onclick="event.stopPropagation()">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="openEditModal(${item.id})" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" onclick="openDeleteModal(${item.id})" title="Hapus">
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
            html += `<button onclick="loadAdminData(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''} 
                        class="pagination-btn">
                        Sebelumnya
                    </button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<button onclick="loadAdminData(${i})" 
                                class="pagination-btn ${i === currentPage ? 'active' : ''}">${i}</button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
            }
            
            // Next button
            html += `<button onclick="loadAdminData(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''} 
                        class="pagination-btn">
                        Selanjutnya
                    </button>`;
            
            container.innerHTML = html;
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

        // ========================================
        // SEARCH & FILTER FUNCTIONS
        // ========================================

        /**
         * Event listener untuk search input
         */
        document.getElementById('searchInput').addEventListener('input', function (e) {
            filterData();
        });

        /**
         * Event listener untuk status filter dropdown
         */
        document.getElementById('statusFilter').addEventListener('change', function (e) {
            filterData();
        });

        /**
         * Filter data berdasarkan search term dan status
         */
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            const filtered = adminData.filter(item => {
                // Search di nama dan email
                const nameMatch = item.name.toLowerCase().includes(searchTerm);
                const emailMatch = item.email.toLowerCase().includes(searchTerm);

                const matchSearch = nameMatch || emailMatch;
                const matchStatus = !statusFilter || (item.status || 'active') === statusFilter;
                return matchSearch && matchStatus;
            });

            // Urutkan hasil filter berdasarkan ID dari kecil ke besar
            const sortedFiltered = filtered.sort((a, b) => a.id - b.id);

            renderTable(sortedFiltered);
        }

        // ========================================
        // MODAL FUNCTIONS - DETAIL
        // ========================================

        /**
         * Menampilkan modal detail admin (readonly)
         * @param {number} id - ID admin yang akan ditampilkan
         */
        function showDetail(id) {
            const admin = adminData.find(p => p.id === id);
            if (!admin) return;

            // Populate form fields
            document.getElementById('detailName').value = admin.name;
            document.getElementById('detailEmail').value = admin.email;
            document.getElementById('detailPhone').value = admin.phone || '-';
            document.getElementById('detailAddress').value = admin.address || '-';
            document.getElementById('detailTanggalLahir').value = admin.tanggal_lahir || '';
            document.getElementById('detailJenisKelamin').value = admin.jenis_kelamin === 'L' ? 'Laki-laki' : admin.jenis_kelamin === 'P' ? 'Perempuan' : '-';

            const statusDisplay = (admin.status || 'active') === 'active' ? 'Aktif' : (admin.status === 'inactive' ? 'Nonaktif' : 'Ditangguhkan');
            document.getElementById('detailStatus').value = statusDisplay;
            document.getElementById('detailDate').value = admin.created_at ? admin.created_at.split('T')[0] : '';

            // Show modal
            document.getElementById('detailModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - ADD
        // ========================================

        /**
         * Membuka modal untuk tambah admin baru
         */
        function openAddModal() {
            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Tambah Admin Baru';
            document.getElementById('formModalDesc').textContent = 'Lengkapi form di bawah untuk menambah admin';

            // Reset form
            document.getElementById('adminForm').reset();
            document.getElementById('adminId').value = '';

            // Password required untuk tambah data
            document.getElementById('formPassword').required = true;
            document.getElementById('formPasswordConfirm').required = true;
            document.getElementById('passwordLabel').innerHTML = 'Password *';
            document.getElementById('confirmPasswordLabel').innerHTML = 'Konfirmasi Password *';
            document.getElementById('passwordHint').style.display = 'none';

            // Show password fields
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('confirmPasswordGroup').style.display = 'block';

            // Clear all errors
            clearAllErrors();

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - EDIT
        // ========================================

        /**
         * Membuka modal untuk edit data admin
         * @param {number} id - ID admin yang akan diedit
         */
        function openEditModal(id) {
            const admin = adminData.find(p => p.id === id);
            if (!admin) return;

            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Edit Data Admin';
            document.getElementById('formModalDesc').textContent = 'Perbarui informasi admin di bawah ini';

            // Populate form dengan data yang ada
            document.getElementById('adminId').value = admin.id;
            document.getElementById('formName').value = admin.name;
            document.getElementById('formEmail').value = admin.email;
            document.getElementById('formPhone').value = admin.phone || '';
            document.getElementById('formAddress').value = admin.address || '';
            document.getElementById('formTanggalLahir').value = admin.tanggal_lahir || '';
            document.getElementById('formJenisKelamin').value = admin.jenis_kelamin || '';
            document.getElementById('formStatus').value = admin.status || 'active';
            document.getElementById('formPassword').value = '';
            document.getElementById('formPasswordConfirm').value = '';

            // Password optional untuk edit
            document.getElementById('formPassword').required = false;
            document.getElementById('formPasswordConfirm').required = false;

            // Sembunyikan field password saat edit
            document.getElementById('passwordGroup').style.display = 'none';
            document.getElementById('confirmPasswordGroup').style.display = 'none';

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // FORM SUBMISSION
        // ========================================

        /**
         * Handle form submit untuk tambah/edit admin
         */
        document.getElementById('adminForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Clear all previous errors
            clearAllErrors();

            const id = document.getElementById('adminId').value;
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
            formData.append('address', document.getElementById('formAddress').value);
            formData.append('tanggal_lahir', document.getElementById('formTanggalLahir').value);
            formData.append('jenis_kelamin', document.getElementById('formJenisKelamin').value);
            formData.append('status', document.getElementById('formStatus').value);

            if (password) {
                formData.append('password', password);
            }

            formData.append('_token', csrfToken);
            if (id) {
                formData.append('_method', 'PUT');
            }

            // Tentukan URL berdasarkan mode (create/update)
            const url = id ? `/admin/admin/${id}` : apiRoutes.store;

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
                        loadAdminData();
                    } else {
                        // Handle validation errors dari server
                        if (data.errors) {
                            // Mapping field errors
                            const errorMapping = {
                                'name': { errorId: 'nameError', inputId: 'formName' },
                                'email': { errorId: 'emailError', inputId: 'formEmail' },
                                'password': { errorId: 'passwordError', inputId: 'formPassword' },
                                'phone': { errorId: 'phoneError', inputId: 'formPhone' }
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
         * @param {number} id - ID admin yang akan dihapus
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

            fetch(`/admin/admin/${deleteId}`, {
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
                        loadAdminData();
                        deleteId = null;
                    } else {
                        closeModal('deleteModal');
                        showToast('Gagal Menghapus', data.message || 'Tidak dapat menghapus data admin', 'warning');
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