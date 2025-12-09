@extends('layouts.template')

@section('title', 'Bank Soal - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin/bank-soal-index.css') }}">
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
                            <input type="text" id="searchInput" placeholder="Cari berdasarkan pertanyaan, kategori, kursus, atau tipe..." onkeyup="debounceSearch()">
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
     * Debounce untuk search input agar tidak terlalu sering request ke server
     */
    let searchTimeout = null;
    function debounceSearch() {
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        searchTimeout = setTimeout(() => {
            loadData(true); // Reset ke halaman 1 saat search
        }, 300);
    }

    /**
     * Load data soal dari server dengan filter
     * Mendukung pencarian, filter kategori, kursus, dan tipe soal
     * @param {boolean} resetPage - true untuk reset ke halaman 1 (digunakan saat search/filter)
     */
    async function loadData(resetPage = true) {
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
            
            // Reset ke halaman 1 jika resetPage true (untuk search/filter)
            if (resetPage) {
                currentPage = 1;
            }
            
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
