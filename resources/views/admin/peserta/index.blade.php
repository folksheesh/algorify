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
                    
                    <!-- Hint klik row -->
                    <div class="table-hint" style="margin-top: 0.75rem; text-align: center;">
                        <span style="font-size: 0.75rem; color: #94A3B8;">
                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Klik baris untuk melihat detail peserta
                        </span>
                    </div>
                    
                    <!-- Pagination -->
                    <div id="paginationContainer" style="margin-top: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
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
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-input" id="modalAlamat" readonly>
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

    <!-- Modal Edit Status Peserta -->
    <div id="editStatusModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px;">
            <button class="modal-close" onclick="closeEditStatusModal()" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
            
            <div class="modal-header">
                <h2>Edit Status Peserta</h2>
                <p>Ubah status akun peserta</p>
            </div>

            <form id="editStatusForm">
                <input type="hidden" id="editPesertaId">
                
                <div class="form-group">
                    <label class="form-label">Nama Peserta</label>
                    <input type="text" class="form-input" id="editPesertaName" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Akun *</label>
                    <select class="form-input" id="editPesertaStatus" required>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeEditStatusModal()">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Success -->
    <div id="successModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <div style="width: 64px; height: 64px; margin: 0 auto 1rem; background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #0F172A; margin-bottom: 0.5rem;" id="successTitle">Berhasil!</h3>
            <p style="font-size: 0.875rem; color: #64748B; margin-bottom: 1.5rem;" id="successMessage">Status peserta berhasil diperbarui</p>
            <button class="btn btn-submit" onclick="closeSuccessModal()" style="width: 100%;">OK</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        let pesertaData = [];
        let currentPage = 1;
        let totalPages = 1;
        let searchTimeout = null;
        let currentSearch = '';
        let currentStatusFilter = '';

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPesertaData();
        });

        // Fungsi untuk load data peserta dengan server-side search
        function loadPesertaData(page = 1) {
            console.log('Loading peserta data...');
            
            // Build query string dengan search dan filter
            const params = new URLSearchParams();
            params.append('page', page);
            if (currentSearch) params.append('search', currentSearch);
            if (currentStatusFilter) params.append('status', currentStatusFilter);
            
            fetch(`{{ route("admin.peserta.data") }}?${params.toString()}`)
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
                <tr onclick="showDetail('${item.id}')" style="cursor: pointer;" title="Klik untuk lihat detail">
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
                            <button class="btn-action btn-edit" onclick="openEditStatusModal('${item.id}')" title="Edit Status">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-delete" onclick="confirmDelete('${item.id}')" title="Hapus">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
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

        // Search functionality - dengan debounce dan server-side search
        document.getElementById('searchInput').addEventListener('input', function(e) {
            // Debounce: tunggu 300ms setelah user berhenti mengetik
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                loadPesertaData(1); // Reset ke halaman 1 saat search
            }, 300);
        });

        // Filter by status akun - server-side
        document.getElementById('statusAkunFilter').addEventListener('change', function(e) {
            currentStatusFilter = e.target.value;
            loadPesertaData(1); // Reset ke halaman 1 saat filter
        });

        // Show detail modal
        function showDetail(id) {
            const peserta = pesertaData.find(p => String(p.id) === String(id));
            if (!peserta) return;

            document.getElementById('modalName').value = peserta.name;
            document.getElementById('modalEmail').value = peserta.email;
            document.getElementById('modalId').value = peserta.id; // Real database ID
            document.getElementById('modalDate').value = peserta.created_at ? peserta.created_at.split('T')[0] : '';
            document.getElementById('modalPhone').value = peserta.phone || peserta.no_hp || '-';
            document.getElementById('modalAlamat').value = peserta.address || '-';
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

        // Open edit status modal
        function openEditStatusModal(id) {
            const peserta = pesertaData.find(p => String(p.id) === String(id));
            if (!peserta) return;

            document.getElementById('editPesertaId').value = peserta.id;
            document.getElementById('editPesertaName').value = peserta.name;
            
            // Set current status
            let currentStatus = peserta.status || 'active';
            if (currentStatus.toLowerCase() === 'aktif') currentStatus = 'active';
            if (currentStatus.toLowerCase() === 'nonaktif') currentStatus = 'inactive';
            document.getElementById('editPesertaStatus').value = currentStatus;

            document.getElementById('editStatusModal').classList.add('active');
        }

        // Close edit status modal
        function closeEditStatusModal() {
            document.getElementById('editStatusModal').classList.remove('active');
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
        }

        // Handle edit status form submit
        document.getElementById('editStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('editPesertaId').value;
            const status = document.getElementById('editPesertaStatus').value;

            fetch(`/admin/peserta/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditStatusModal();
                    document.getElementById('successTitle').textContent = 'Berhasil!';
                    document.getElementById('successMessage').textContent = data.message || 'Status peserta berhasil diperbarui';
                    document.getElementById('successModal').classList.add('active');
                    loadPesertaData(currentPage);
                } else {
                    alert(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            });
        });

        // Close modals when clicking outside
        document.getElementById('editStatusModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditStatusModal();
        });
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) closeSuccessModal();
        });
    </script>
@endpush