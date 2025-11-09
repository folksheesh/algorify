@extends('layouts.template')

@section('title', 'Data Peserta - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .table-container {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 1;
            min-width: 250px;
            max-width: 400px;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .search-box svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }
        .status-dropdown {
            padding: 0.65rem 2.5rem 0.65rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
            background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 0.75rem center;
            cursor: pointer;
            appearance: none;
        }
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .data-table thead {
            background: #667eea;
            color: white;
        }
        .data-table thead th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        .data-table thead th:first-child {
            border-radius: 8px 0 0 0;
        }
        .data-table thead th:last-child {
            border-radius: 0 8px 0 0;
        }
        .data-table tbody tr {
            border-bottom: 1px solid #F1F5F9;
        }
        .data-table tbody tr:hover {
            background: #F8FAFC;
        }
        .data-table tbody td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #334155;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.aktif {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge.nonaktif {
            background: #FEE2E2;
            color: #DC2626;
        }
        .status-badge.lunas {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge svg {
            width: 14px;
            height: 14px;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-action {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
        .btn-view {
            color: #667eea;
        }
        .btn-delete {
            color: #EF4444;
        }
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            max-width: 560px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            margin-bottom: 1.5rem;
            padding-right: 2.5rem;
        }
        .modal-header h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
        }
        .modal-header p {
            color: #64748B;
            font-size: 0.8125rem;
            margin: 0;
            line-height: 1.5;
            font-weight: 400;
        }
        .modal-close {
            position: absolute;
            top: 1.75rem;
            right: 2rem;
            background: transparent;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }
        .modal-close:hover {
            color: #64748B;
        }
        .modal-close svg {
            display: block;
        }
        .form-group {
            margin-bottom: 0.875rem;
        }
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #0F172A;
            margin-bottom: 0.375rem;
            letter-spacing: -0.01em;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 0.875rem;
            border: none;
            border-radius: 6px;
            font-size: 0.8125rem;
            background: #F1F5F9;
            color: #475569;
            font-weight: 400;
            transition: background 0.2s;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            background: #E2E8F0;
        }
        .form-input:read-only {
            cursor: default;
        }
        .form-input::placeholder {
            color: #94A3B8;
        }
        .form-row-3 {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr 0.8fr;
            gap: 0.75rem;
            margin-bottom: 0.875rem;
        }
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 0.875rem;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.625rem;
            margin-top: 1.25rem;
            padding-top: 0;
        }
        .btn {
            padding: 0.625rem 1.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            letter-spacing: -0.01em;
        }
        .btn-cancel {
            background: white;
            color: #64748B;
            border: 1px solid #CBD5E1;
        }
        .btn-cancel:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
            color: #475569;
        }
        .btn-submit {
            background: #0F172A;
            color: white;
        }
        .btn-submit:hover {
            background: #1E293B;
        }
    </style>
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
                                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            <input type="text" id="searchInput" placeholder="Cari nama atau email.....">
                        </div>
                        <select id="statusFilter" class="status-dropdown">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>Status Transaksi</th>
                                    <th>Status</th>
                                    <th>Kursus</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pesertaTableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
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

                <div class="form-row-2">
                    <div>
                        <label class="form-label">Jumlah Kursus</label>
                        <input type="text" class="form-input" id="modalKursus" readonly>
                    </div>
                    <div>
                        <label class="form-label">Status Pembayaran *</label>
                        <input type="text" class="form-input" id="modalPayment" readonly>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="button" class="btn btn-submit">Ya Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        let pesertaData = [];

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPesertaData();
        });

        // Fungsi untuk load data peserta
        function loadPesertaData() {
            fetch('{{ route("admin.peserta.data") }}')
                .then(response => response.json())
                .then(data => {
                    pesertaData = data;
                    renderTable(pesertaData);
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk render tabel
        function renderTable(data) {
            const tbody = document.getElementById('pesertaTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #94A3B8;">
                            Tidak ada data peserta
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map((item, index) => `
                <tr>
                    <td>${index + 1}A${index + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.email}</td>
                    <td>
                        <span class="status-badge lunas">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Lunas
                        </span>
                    </td>
                    <td>
                        <span class="status-badge ${(item.status || 'Aktif').toLowerCase()}">
                            ${item.status || 'Aktif'}
                        </span>
                    </td>
                    <td>${item.kursus_count || 0}</td>
                    <td>${formatDate(item.created_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" onclick="showDetail(${item.id})" title="Lihat Detail">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-delete" onclick="confirmDelete(${item.id})" title="Hapus">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
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

        // Filter by status
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            filterData();
        });

        // Fungsi filter data
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            let filtered = pesertaData.filter(item => {
                const matchSearch = item.name.toLowerCase().includes(searchTerm) || 
                                   item.email.toLowerCase().includes(searchTerm);
                const matchStatus = !statusFilter || (item.status || 'Aktif') === statusFilter;
                return matchSearch && matchStatus;
            });

            renderTable(filtered);
        }

        // Show detail modal
        function showDetail(id) {
            const peserta = pesertaData.find(p => p.id === id);
            if (!peserta) return;

            document.getElementById('modalName').value = peserta.name;
            document.getElementById('modalEmail').value = peserta.email;
            document.getElementById('modalId').value = `A${peserta.id}A`;
            document.getElementById('modalDate').value = peserta.created_at ? peserta.created_at.split('T')[0] : '';
            document.getElementById('modalKursus').value = peserta.kursus_count || 0;
            document.getElementById('modalPayment').value = 'Lunas';
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
    </script>
@endpush
