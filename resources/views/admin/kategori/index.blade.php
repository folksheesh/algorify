@extends('layouts.template')

@section('title', 'Data Kategori - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .page-header { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #1E293B; margin: 0 0 0.25rem 0; }
        .page-header p { color: #64748B; font-size: 0.875rem; margin: 0; }
        .table-container { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); margin-top: 1.5rem; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; }
        .search-box { position: relative; flex: 1; max-width: 400px; }
        .search-box input { width: 100%; padding: 0.625rem 1rem 0.625rem 2.75rem; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 0.875rem; height: 40px; box-sizing: border-box; }
        .search-box input:focus { outline: none; border-color: #5D3FFF; box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1); }
        .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8; pointer-events: none; }
        .btn-add { padding: 0 1.5rem; background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%); color: white; border: none; border-radius: 10px; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 40px; box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2); }
        .btn-add:hover { background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3); }
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
        .modal-content { background: white; border-radius: 16px; padding: 2rem 2.5rem; max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto; position: relative; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); }
        .modal-header h2 { font-size: 1.375rem; font-weight: 700; color: #0F172A; margin: 0 0 0.5rem 0; }
        .modal-header p { color: #64748B; font-size: 0.8125rem; margin: 0 0 1.5rem 0; }
        .modal-close { position: absolute; top: 1.75rem; right: 2rem; background: transparent; border: none; color: #94A3B8; cursor: pointer; font-size: 1.5rem; }
        .modal-close:hover { color: #64748B; }
        .form-group { margin-bottom: 0.875rem; }
        .form-label { display: block; font-size: 0.8125rem; font-weight: 500; color: #0F172A; margin-bottom: 0.375rem; }
        .form-input { width: 100%; padding: 0.75rem 0.875rem; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8125rem; background: white; color: #475569; transition: all 0.2s; box-sizing: border-box; }
        .form-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        textarea.form-input { font-family: 'Inter', sans-serif; line-height: 1.5; resize: vertical; min-height: 100px; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 0.625rem; margin-top: 1.25rem; }
        .btn { padding: 0.625rem 1.75rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-cancel { background: white; color: #64748B; border: 1px solid #CBD5E1; }
        .btn-cancel:hover { background: #F8FAFC; border-color: #94A3B8; }
        .btn-submit { background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%); color: white; box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2); }
        .btn-submit:hover { background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%); box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3); }
        .btn-danger { background: #EF4444; color: white; }
        .btn-danger:hover { background: #DC2626; }
    </style>
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
@endsection

@push('scripts')
<script>
    let editingId = null;

    // Load data
    async function loadData() {
        try {
            const search = document.getElementById('searchInput').value;
            const response = await fetch(`{{ route("admin.kategori.data") }}?search=${search}`);
            const result = await response.json();
            const tbody = document.getElementById('kategoriTableBody');
            
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3">Tidak ada data kategori</td></tr>';
                return;
            }

            tbody.innerHTML = result.data.map(item => `
                <tr onclick="viewKategori(${item.id})" style="cursor: pointer;">
                    <td>${item.no}</td>
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
        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('kategoriTableBody').innerHTML = '<tr><td colspan="3">Error loading data</td></tr>';
        }
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
            alert('Gagal memuat data kategori');
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
            alert('Gagal memuat data kategori');
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
                alert('Kategori berhasil dihapus');
                loadData();
            } else {
                alert(result.message || 'Gagal menghapus kategori');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kategori');
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
                alert(result.message);
                closeModal();
                loadData();
            } else {
                alert('Gagal menyimpan kategori');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan kategori');
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
