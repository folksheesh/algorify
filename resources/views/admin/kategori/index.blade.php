@extends('layouts.template')

@section('title', 'Data Kategori - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/kategori-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <div class="page-header">
                    <div>
                        <h1>Data Kategori</h1>
                        <p>Kelola kategori pelatihan</p>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <div class="search-box">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <input type="text" id="searchInput" placeholder="Cari kategori..." onkeyup="loadData()">
                        </div>
                        <button class="btn-add" onclick="openAddModal()">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 3.75V14.25M3.75 9H14.25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Tambah Kategori
                        </button>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategoriTableBody">
                            <tr><td colspan="3">Loading...</td></tr>
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

    {{-- Modal Tambah/Edit Kategori --}}
    <div class="modal-overlay" id="kategoriModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Kategori</h2>
                <p id="modalSubtitle">Isi form di bawah untuk menambahkan kategori</p>
            </div>
            <form id="kategoriForm">
                <input type="hidden" id="kategoriId">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" class="form-input" id="namaKategori" placeholder="Masukkan nama kategori" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-input" id="deskripsi" placeholder="Masukkan deskripsi kategori"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal View Kategori --}}
    <div class="modal-overlay" id="viewModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
            <div class="modal-header">
                <h2>Detail Kategori</h2>
                <p>Informasi kategori pelatihan</p>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-input" id="viewNamaKategori" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" id="viewDeskripsi" readonly></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeViewModal()">Tutup</button>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div id="toastNotification" class="toast-notification">
        <div class="toast-icon-wrapper" id="toastIconWrapper">
            <svg class="toast-icon" id="toastIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Notifikasi</div>
            <div class="toast-msg" id="toastMessage">Pesan notifikasi</div>
        </div>
        <button class="toast-close-btn" onclick="closeToast()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <style>
    .toast-notification { position: fixed; top: 20px; right: 20px; padding: 1rem 1.25rem; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15); z-index: 10001; display: none; align-items: center; gap: 0.75rem; min-width: 320px; max-width: 450px; animation: toastSlideIn 0.3s ease; border-left: 4px solid #10B981; }
    .toast-notification.active { display: flex; }
    .toast-notification.success { border-left-color: #10B981; }
    .toast-notification.error { border-left-color: #EF4444; }
    .toast-notification.warning { border-left-color: #F59E0B; }
    .toast-notification.info { border-left-color: #3B82F6; }
    .toast-icon-wrapper { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .toast-notification.success .toast-icon-wrapper { background: #D1FAE5; color: #10B981; }
    .toast-notification.error .toast-icon-wrapper { background: #FEE2E2; color: #EF4444; }
    .toast-notification.warning .toast-icon-wrapper { background: #FEF3C7; color: #F59E0B; }
    .toast-notification.info .toast-icon-wrapper { background: #DBEAFE; color: #3B82F6; }
    .toast-icon { width: 20px; height: 20px; }
    .toast-content { flex: 1; }
    .toast-title { font-weight: 600; font-size: 0.875rem; color: #1E293B; margin-bottom: 0.125rem; }
    .toast-msg { font-size: 0.8125rem; color: #64748B; line-height: 1.4; }
    .toast-close-btn { background: transparent; border: none; cursor: pointer; padding: 0.25rem; color: #94A3B8; border-radius: 4px; transition: all 0.2s; }
    .toast-close-btn:hover { background: #F1F5F9; color: #64748B; }
    @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes toastSlideOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100px); } }
    </style>
@endsection

@push('scripts')
<script>
    let toastTimeout = null;
    
    function showToast(title, message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        
        if (toastTimeout) clearTimeout(toastTimeout);
        
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toast.className = 'toast-notification ' + type + ' active';
        
        const iconSvg = document.getElementById('toastIcon');
        if (type === 'success') {
            iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
        } else if (type === 'error') {
            iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
        } else if (type === 'warning') {
            iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
        } else {
            iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
        }
        
        toastTimeout = setTimeout(() => closeToast(), 4000);
    }
    
    function closeToast() {
        const toast = document.getElementById('toastNotification');
        toast.style.animation = 'toastSlideOut 0.3s ease forwards';
        setTimeout(() => {
            toast.classList.remove('active');
            toast.style.animation = '';
        }, 300);
    }

    let editingId = null;
    let kategoriData = [];
    let filteredData = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    // Load data
    async function loadData() {
        try {
            const search = document.getElementById('searchInput').value;
            const response = await fetch(`{{ route("admin.kategori.data") }}?search=${search}`);
            const result = await response.json();
            
            kategoriData = result.data;
            filteredData = kategoriData;
            currentPage = 1;
            
            renderTable();
            renderPagination();
        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('kategoriTableBody').innerHTML = '<tr><td colspan="3">Error loading data</td></tr>';
            updatePaginationInfo(0, 0, 0);
        }
    }

    // Render table dengan pagination
    function renderTable() {
        const tbody = document.getElementById('kategoriTableBody');
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);
        
        if (filteredData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3">Tidak ada data kategori</td></tr>';
            updatePaginationInfo(0, 0, 0);
            return;
        }

        tbody.innerHTML = paginatedData.map((item, index) => `
            <tr onclick="viewKategori(${item.id})" style="cursor: pointer;">
                <td>${startIndex + index + 1}</td>
                <td>${item.nama_kategori}</td>
                <td onclick="event.stopPropagation()">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editKategori(${item.id})" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteKategori(${item.id})" title="Hapus">
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

    // Open add modal
    function openAddModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'Tambah Kategori';
        document.getElementById('modalSubtitle').textContent = 'Isi form di bawah untuk menambahkan kategori';
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategoriModal').classList.add('active');
    }

    // View kategori
    async function viewKategori(id) {
        try {
            const response = await fetch(`{{ route("admin.kategori.show", ":id") }}`.replace(':id', id));
            const result = await response.json();
            document.getElementById('viewNamaKategori').value = result.data.nama_kategori;
            document.getElementById('viewDeskripsi').value = result.data.deskripsi || '-';
            document.getElementById('viewModal').classList.add('active');
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal', 'Gagal memuat data kategori', 'error');
        }
    }

    // Edit kategori
    async function editKategori(id) {
        try {
            const response = await fetch(`{{ route("admin.kategori.show", ":id") }}`.replace(':id', id));
            const result = await response.json();
            editingId = id;
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('modalSubtitle').textContent = 'Update informasi kategori';
            document.getElementById('namaKategori').value = result.data.nama_kategori;
            document.getElementById('deskripsi').value = result.data.deskripsi || '';
            document.getElementById('kategoriModal').classList.add('active');
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal', 'Gagal memuat data kategori', 'error');
        }
    }

    // Delete kategori
    async function deleteKategori(id) {
        if (!confirm('Yakin ingin menghapus kategori ini?')) return;
        
        try {
            const response = await fetch(`{{ route("admin.kategori.destroy", ":id") }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                showToast('Berhasil', 'Kategori berhasil dihapus', 'success');
                loadData();
            } else {
                showToast('Gagal', result.message || 'Gagal menghapus kategori', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menghapus kategori', 'error');
        }
    }

    // Submit form
    document.getElementById('kategoriForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            nama_kategori: document.getElementById('namaKategori').value,
            deskripsi: document.getElementById('deskripsi').value
        };

        try {
            const url = editingId 
                ? `{{ route("admin.kategori.update", ":id") }}`.replace(':id', editingId)
                : '{{ route("admin.kategori.store") }}';
            
            const response = await fetch(url, {
                method: editingId ? 'PUT' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                showToast('Berhasil', result.message, 'success');
                closeModal();
                loadData();
            } else {
                showToast('Gagal', 'Gagal menyimpan kategori', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menyimpan kategori', 'error');
        }
    });

    function closeModal() {
        document.getElementById('kategoriModal').classList.remove('active');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('active');
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', loadData);
</script>
@endpush
