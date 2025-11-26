@extends('layouts.template')

@section('title', 'Data Peserta - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/peserta-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <div class="page-header">
                    <h1>Halaman Peserta</h1>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <div class="search-box">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M13 13L17 17" stroke="currentColor" stroke="1.5" />
                            </svg>
                            <input type="text" id="searchInput" placeholder="Cari nama, email, atau no HP.....">
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <select id="statusAkunFilter" class="status-dropdown">
                                <option value="">Semua Status Akun</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th style="width: 20%;">Nama</th>
                                    <th style="width: 22%;">Email</th>
                                    <th style="width: 15%;">No HP</th>
                                    <th style="width: 13%;">Status Akun</th>
                                    <th style="width: 10%;">Kursus</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pesertaTableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div id="paginationContainer" style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem;">
                        <!-- Pagination buttons will be rendered here -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Peserta -->
    <div id="detailModal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
            
            <div class="modal-header">
                <h2>Rincian Data Peserta</h2>
                <p>Semua data peserta tersimpan secara aman dan dapat diakses sesuai hak pengguna.</p>
            </div>

            <form id="detailForm">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" class="form-input" id="modalName" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" id="modalEmail" readonly>
                </div>

                <div class="form-row-3">
                    <div>
                        <label class="form-label">ID *</label>
                        <input type="text" class="form-input" id="modalId" readonly>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Bergabung *</label>
                        <input type="date" class="form-input" id="modalDate" readonly>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <input type="text" class="form-input" id="modalStatus" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">No HP *</label>
                    <input type="text" class="form-input" id="modalPhone" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Domisili</label>
                    <input type="text" class="form-input" id="modalDomisili" readonly>
                </div>

                <div class="form-row-2">
                    <div>
                        <label class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-input" id="modalJenisKelamin" readonly>
                    </div>
                    <div>
                        <label class="form-label">Jumlah Kursus</label>
                        <input type="text" class="form-input" id="modalKursusCount" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kursus yang Diikuti</label>
                    <textarea class="form-input" id="modalKursus" rows="3" readonly></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Tutup</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        let pesertaData = [];
        let currentPage = 1;
        let totalPages = 1;

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPesertaData();
        });

        // Fungsi untuk load data peserta
        function loadPesertaData(page = 1) {
            console.log('Loading peserta data...');
            fetch(`{{ route("admin.peserta.data") }}?page=${page}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    pesertaData = data.data || data; // Handle both paginated and non-paginated
                    currentPage = data.current_page || 1;
                    totalPages = data.last_page || 1;
                    renderTable(pesertaData);
                    renderPagination();
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                    const tbody = document.getElementById('pesertaTableBody');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: #EF4444;">
                                Error loading data: ${error.message}
                            </td>
                        </tr>
                    `;
                });
        }

        // Fungsi untuk render tabel
        function renderTable(data) {
            const tbody = document.getElementById('pesertaTableBody');
            
            console.log('Rendering table with', data.length, 'items');
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #94A3B8;">
                            Tidak ada data peserta
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map((item, index) => {
                const statusTransaksi = item.status_transaksi || 'Pending';
                const statusAkun = item.status || 'Aktif';
                const phoneNumber = item.phone || item.no_hp || '-';
                const kursusCount = item.kursus_count || 0;
                
                // Normalize status text to Indonesian
                let displayStatusAkun = statusAkun;
                if (statusAkun.toLowerCase() === 'active') {
                    displayStatusAkun = 'Aktif';
                } else if (statusAkun.toLowerCase() === 'inactive') {
                    displayStatusAkun = 'Nonaktif';
                }
                
                return `
                <tr onclick="showDetail(${item.id})" style="cursor: pointer;">
                    <td>${item.id}</td>
                    <td>${item.name || '-'}</td>
                    <td>${item.email || '-'}</td>
                    <td>${phoneNumber}</td>
                    <td>
                        <span class="status-badge ${displayStatusAkun === 'Nonaktif' ? 'nonaktif' : 'aktif'}">
                            ${displayStatusAkun}
                        </span>
                    </td>
                    <td>${kursusCount}</td>
                    <td onclick="event.stopPropagation()">
                        <div class="action-buttons">
                            <button class="btn-action btn-delete" onclick="confirmDelete(${item.id})" title="Hapus">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `}).join('');
        }

        // Format tanggal
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            filterData();
        });

        // Filter by status akun
        document.getElementById('statusAkunFilter').addEventListener('change', function(e) {
            filterData();
        });

        // Fungsi filter data
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusAkunFilter = document.getElementById('statusAkunFilter').value;

            let filtered = pesertaData.filter(item => {
                const phoneNumber = item.phone || item.no_hp || '';
                const matchSearch = item.name.toLowerCase().includes(searchTerm) || 
                                   item.email.toLowerCase().includes(searchTerm) ||
                                   phoneNumber.toLowerCase().includes(searchTerm);
                const matchStatusAkun = !statusAkunFilter || (item.status || 'Aktif') === statusAkunFilter;
                return matchSearch && matchStatusAkun;
            });

            renderTable(filtered);
        }

        // Show detail modal
        function showDetail(id) {
            const peserta = pesertaData.find(p => p.id === id);
            if (!peserta) return;

            document.getElementById('modalName').value = peserta.name;
            document.getElementById('modalEmail').value = peserta.email;
            document.getElementById('modalId').value = peserta.id; // Real database ID
            document.getElementById('modalDate').value = peserta.created_at ? peserta.created_at.split('T')[0] : '';
            document.getElementById('modalPhone').value = peserta.phone || peserta.no_hp || '-';
            document.getElementById('modalDomisili').value = peserta.domisili || '-';
            document.getElementById('modalJenisKelamin').value = peserta.jenis_kelamin === 'L' ? 'Laki-laki' : peserta.jenis_kelamin === 'P' ? 'Perempuan' : '-';
            document.getElementById('modalKursusCount').value = peserta.kursus_count || 0;
            document.getElementById('modalKursus').value = peserta.kursus_names || 'Belum mengikuti kursus';
            document.getElementById('modalStatus').value = peserta.status || 'Aktif';

            document.getElementById('detailModal').classList.add('active');
        }

        // Close modal
        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        // Confirm delete
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
                // Implementasi delete
                console.log('Delete peserta:', id);
            }
        }

        // Close modal saat klik di luar
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Render pagination
        function renderPagination() {
            const container = document.getElementById('paginationContainer');
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Previous button
            html += `<button onclick="loadPesertaData(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''} 
                        class="pagination-btn">
                        Sebelumnya
                    </button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<button onclick="loadPesertaData(${i})" 
                                class="pagination-btn ${i === currentPage ? 'active' : ''}">${i}</button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
            }
            
            // Next button
            html += `<button onclick="loadPesertaData(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''} 
                        class="pagination-btn">
                        Selanjutnya
                    </button>`;
            
            container.innerHTML = html;
        }
    </script>
@endpush