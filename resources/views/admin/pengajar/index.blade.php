@extends('layouts.template')

@section('title', 'Data Pengajar - Algorify')

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
        .page-container {
            padding: 0 2rem 2rem;
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .page-title-section h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }
        .search-filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #94A3B8;
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #1E293B;
            transition: all 0.2s;
        }
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .filter-group {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-left: auto;
        }
        .filter-select {
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #1E293B;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2364748B' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
        }
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-add:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-add svg {
            width: 18px;
            height: 18px;
        }
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead {
            background: #F8FAFC;
        }
        .data-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #E2E8F0;
        }
        .data-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #1E293B;
            border-bottom: 1px solid #F1F5F9;
        }
        .data-table tbody tr {
            transition: all 0.2s;
        }
        .data-table tbody tr:hover {
            background: #F8FAFC;
        }
        .instructor-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .instructor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .instructor-details h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .instructor-details p {
            font-size: 0.75rem;
            color: #64748B;
            margin: 0;
        }
        .course-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
        }
        .course-badge {
            padding: 0.25rem 0.625rem;
            background: #EDE3FF;
            color: #7A3EF0;
            border-radius: 6px;
            font-size: 0.6875rem;
            font-weight: 600;
        }
        .course-badge.secondary {
            background: #DBEAFE;
            color: #0284C7;
        }
        .course-badge.tertiary {
            background: #FEE2E2;
            color: #DC2626;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.active {
            background: #D1FAE5;
            color: #059669;
        }
        .status-badge.inactive {
            background: #FEE2E2;
            color: #DC2626;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: #F1F5F9;
            color: #64748B;
        }
        .btn-icon:hover {
            transform: scale(1.1);
        }
        .btn-icon svg {
            width: 16px;
            height: 16px;
        }
        .btn-icon.view:hover {
            background: #DBEAFE;
            color: #0284C7;
        }
        .btn-icon.edit:hover {
            background: #FEF3C7;
            color: #D97706;
        }
        .btn-icon.delete:hover {
            background: #FEE2E2;
            color: #DC2626;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-state-icon svg {
            width: 60px;
            height: 60px;
            color: #94A3B8;
        }
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            font-size: 0.9375rem;
            color: #64748B;
        }
        
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-container {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #E2E8F0;
            background: white;
            flex-shrink: 0;
        }
        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }
        .modal-header p {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0;
        }
        .modal-body {
            padding: 1.5rem 2rem;
            padding-bottom: 5rem;
            overflow-y: scroll;
            overflow-x: hidden;
            flex: 1 1 0;
            min-height: 0;
            max-height: 500px;
        }
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        .modal-body::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 10px;
        }
        .modal-body::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }
        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.4rem;
        }
        .form-label .required {
            color: #DC2626;
            margin-left: 0.25rem;
        }
        .form-input, .form-textarea, .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #1E293B;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-input::placeholder, .form-textarea::placeholder {
            color: #94A3B8;
        }
        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2364748B' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }
        .upload-area {
            border: 2px dashed #E2E8F0;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #F8FAFC;
        }
        .upload-area:hover {
            border-color: #667eea;
            background: #F1F5F9;
        }
        .upload-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.75rem;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
        }
        .upload-icon svg {
            width: 20px;
            height: 20px;
        }
        .upload-text {
            font-size: 0.875rem;
            color: #1E293B;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .upload-hint {
            font-size: 0.75rem;
            color: #64748B;
        }
        .file-input {
            display: none;
        }
        .modal-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid #E2E8F0;
            display: flex !important;
            justify-content: flex-end;
            gap: 1rem;
            background: white;
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            z-index: 10;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
        .btn-modal {
            padding: 0.75rem 1.5rem !important;
            border-radius: 10px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 100px !important;
            font-family: 'Inter', sans-serif !important;
            line-height: 1 !important;
            white-space: nowrap !important;
        }
        .btn-cancel {
            background: white !important;
            color: #64748B !important;
            border: 2px solid #E2E8F0 !important;
        }
        .btn-cancel:hover {
            background: #F8FAFC !important;
            border-color: #CBD5E1 !important;
        }
        .btn-submit {
            background: #667eea !important;
            color: white !important;
            border: 2px solid #667eea !important;
        }
        .btn-submit:hover {
            background: #5568d3 !important;
            border-color: #5568d3 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3) !important;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-top">
                        <div class="page-title-section">
                            <h1>Data Pengajar</h1>
                        </div>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="search-filter-bar">
                        <div class="search-box">
                            <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                            <input 
                                type="text" 
                                class="search-input" 
                                id="searchPengajar"
                                placeholder="Cari nama, email atau kursus..."
                                autocomplete="off"
                            >
                        </div>
                        <div class="filter-group">
                            <select class="filter-select" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                            <select class="filter-select" id="filterKursus">
                                <option value="">Semua Kursus</option>
                                <option value="has">Memiliki Kursus</option>
                                <option value="none">Belum Ada Kursus</option>
                            </select>
                            <button class="btn-add" onclick="openModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Tambah Pengajar
                            </button>
                        </div>
                    </div>
                </div>

                @if($pengajar->count() > 0)
                    <!-- Data Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Kursus yang Diajarkan</th>
                                    <th>Status</th>
                                    <th>Jumlah Kelas</th>
                                    <th>Total Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach($pengajar as $instructor)
                                <tr class="table-row">
                                    <td>{{ str_pad($instructor->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="instructor-info">
                                            <img 
                                                src="{{ $instructor->foto_profil ? asset($instructor->foto_profil) : asset('template/assets/static/images/faces/1.jpg') }}" 
                                                alt="{{ $instructor->name }}"
                                                class="instructor-avatar"
                                            >
                                            <div class="instructor-details">
                                                <h4>{{ $instructor->name }}</h4>
                                                <p>{{ $instructor->phone ?? 'No phone' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $instructor->email }}</td>
                                    <td>
                                        <div class="course-badges">
                                            @forelse($instructor->kursus->take(3) as $index => $kursus)
                                                <span class="course-badge {{ $index == 1 ? 'secondary' : ($index == 2 ? 'tertiary' : '') }}">
                                                    {{ Str::limit($kursus->judul, 20) }}
                                                </span>
                                            @empty
                                                <span style="color: #94A3B8; font-size: 0.75rem;">Belum ada kursus</span>
                                            @endforelse
                                            @if($instructor->kursus->count() > 3)
                                                <span class="course-badge">+{{ $instructor->kursus->count() - 3 }} lainnya</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $instructor->status == 'active' ? 'active' : 'inactive' }}">
                                            <span class="status-dot"></span>
                                            {{ $instructor->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $instructor->kursus_count }}</td>
                                    <td>
                                        @php
                                            $totalSiswa = $instructor->kursus->sum(function($kursus) {
                                                return $kursus->enrollments->count();
                                            });
                                        @endphp
                                        {{ $totalSiswa }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-icon view" title="Lihat Detail">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                            <button class="btn-icon edit" title="Edit" onclick="openEditModal({{ $instructor->id }}, '{{ $instructor->name }}', '{{ $instructor->email }}', '{{ $instructor->phone }}', '{{ $instructor->profesi }}', '{{ $instructor->pendidikan }}', '{{ addslashes($instructor->address) }}', '{{ $instructor->status }}', {{ $instructor->kursus->isNotEmpty() && $instructor->kursus->first() ? $instructor->kursus->first()->id : 'null' }})">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </button>
                                            <button class="btn-icon delete" title="Hapus">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="table-container">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <h3>Belum Ada Pengajar</h3>
                            <p>Mulai tambahkan pengajar baru ke platform Anda</p>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal Tambah Pengajar -->
    <div class="modal-overlay" id="pengajarModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Tambah Pengajar Baru</h2>
                <p>Masukkan data pengajar baru. Semua field yang bertanda * wajib diisi.</p>
            </div>
            <form id="pengajarForm" method="POST" action="{{ route('admin.pengajar.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-input" 
                                placeholder="Masukkan Nama Lengkap Pengajar"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">Email<span class="required">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Masukkan Email Pengajar"
                                required
                            >
                        </div>

                        <!-- No Telp -->
                        <div class="form-group">
                            <label class="form-label">No Telp<span class="required">*</span></label>
                            <input 
                                type="tel" 
                                name="phone" 
                                class="form-input" 
                                placeholder="Masukkan Nomor Telepon Pengajar"
                                required
                            >
                        </div>

                        <!-- Kursus yang diajarkan -->
                        <div class="form-group">
                            <label class="form-label">Kursus yang diajarkan<span class="required">*</span></label>
                            <select name="kursus_id" class="form-select" required>
                                <option value="">Pilih Kursus</option>
                                @if(isset($kursus) && is_iterable($kursus))
                                    @foreach($kursus as $k)
                                        <option value="{{ $k->id }}">{{ $k->judul }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada kursus tersedia</option>
                                @endif
                            </select>
                        </div>

                        <!-- Keahlian -->
                        <div class="form-group">
                            <label class="form-label">Keahlian<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="profesi" 
                                class="form-input" 
                                placeholder="Contoh: Python, UI/UX Design"
                                required
                            >
                        </div>

                        <!-- Pengalaman -->
                        <div class="form-group">
                            <label class="form-label">Pengalaman<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="pendidikan" 
                                class="form-input" 
                                placeholder="Contoh: 10 tahun di Industri Teknologi"
                                required
                            >
                        </div>

                        <!-- Biografi -->
                        <div class="form-group full-width">
                            <label class="form-label">Biografi<span class="required">*</span></label>
                            <textarea 
                                name="address" 
                                class="form-textarea" 
                                placeholder="Tuliskan deskripsi singkat tentang pengajar..."
                                required
                            ></textarea>
                        </div>

                        <!-- Upload Foto -->
                        <div class="form-group full-width">
                            <label class="form-label">Unggah sertifikat sesuai dengan bidang yang relevan<span class="required">*</span></label>
                            <div class="upload-area" onclick="document.getElementById('fotoInput').click()">
                                <div class="upload-icon">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="upload-text">Unggah</p>
                                <p class="upload-hint">Drag & drop atau klik untuk memilih file</p>
                            </div>
                            <input 
                                type="file" 
                                id="fotoInput" 
                                name="foto_profil" 
                                class="file-input"
                                accept="image/*"
                            >
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label">Status<span class="required">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-modal btn-submit">Ke Halaman</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pengajar -->
    <div class="modal-overlay" id="editPengajarModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Edit Pengajar</h2>
                <p>Perbarui data pengajar. Semua field yang bertanda * wajib diisi.</p>
            </div>
            <form id="editPengajarForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="edit_name"
                                class="form-input" 
                                placeholder="Masukkan Nama Lengkap Pengajar"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">Email<span class="required">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                id="edit_email"
                                class="form-input" 
                                placeholder="Masukkan Email Pengajar"
                                required
                            >
                        </div>

                        <!-- No Telp -->
                        <div class="form-group">
                            <label class="form-label">No Telp<span class="required">*</span></label>
                            <input 
                                type="tel" 
                                name="phone" 
                                id="edit_phone"
                                class="form-input" 
                                placeholder="Masukkan Nomor Telepon Pengajar"
                                required
                            >
                        </div>

                        <!-- Kursus yang diajarkan -->
                        <div class="form-group">
                            <label class="form-label">Kursus yang diajarkan<span class="required">*</span></label>
                            <select name="kursus_id" id="edit_kursus" class="form-select" required>
                                <option value="">Pilih Kursus</option>
                                @if(isset($kursus) && is_iterable($kursus))
                                    @foreach($kursus as $k)
                                        <option value="{{ $k->id }}">{{ $k->judul }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada kursus tersedia</option>
                                @endif
                            </select>
                        </div>

                        <!-- Keahlian -->
                        <div class="form-group">
                            <label class="form-label">Keahlian<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="profesi" 
                                id="edit_profesi"
                                class="form-input" 
                                placeholder="Contoh: Python, UI/UX Design"
                                required
                            >
                        </div>

                        <!-- Pengalaman -->
                        <div class="form-group">
                            <label class="form-label">Pengalaman<span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="pendidikan" 
                                id="edit_pendidikan"
                                class="form-input" 
                                placeholder="Contoh: 10 tahun di Industri Teknologi"
                                required
                            >
                        </div>

                        <!-- Biografi -->
                        <div class="form-group full-width">
                            <label class="form-label">Biografi<span class="required">*</span></label>
                            <textarea 
                                name="address" 
                                id="edit_address"
                                class="form-textarea" 
                                placeholder="Tuliskan deskripsi singkat tentang pengajar..."
                                required
                            ></textarea>
                        </div>

                        <!-- Upload Foto -->
                        <div class="form-group full-width">
                            <label class="form-label">Unggah sertifikat sesuai dengan bidang yang relevan</label>
                            <div class="upload-area" onclick="document.getElementById('editFotoInput').click()">
                                <div class="upload-icon">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="upload-text">Unggah</p>
                                <p class="upload-hint">Drag & drop atau klik untuk memilih file</p>
                            </div>
                            <input 
                                type="file" 
                                id="editFotoInput" 
                                name="foto_profil" 
                                class="file-input"
                                accept="image/*"
                            >
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label">Status<span class="required">*</span></label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal btn-submit">Update Pengajar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Modal functions
        function openModal() {
            document.getElementById('pengajarModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('pengajarModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('pengajarForm').reset();
        }
        
        // Close modal on overlay click
        document.getElementById('pengajarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // File upload preview
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const uploadArea = this.previousElementSibling;
                const fileName = file.name;
                uploadArea.querySelector('.upload-text').textContent = fileName;
                uploadArea.querySelector('.upload-hint').textContent = 'File berhasil dipilih';
            }
        });

        // Edit modal file upload preview
        document.getElementById('editFotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const uploadArea = this.previousElementSibling;
                const fileName = file.name;
                uploadArea.querySelector('.upload-text').textContent = fileName;
                uploadArea.querySelector('.upload-hint').textContent = 'File berhasil dipilih';
            }
        });

        // Edit Modal functions
        function openEditModal(id, name, email, phone, profesi, pendidikan, address, status, kursus_id) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_profesi').value = profesi;
            document.getElementById('edit_pendidikan').value = pendidikan;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_kursus').value = kursus_id || '';
            
            // Set form action
            document.getElementById('editPengajarForm').action = `/admin/pengajar/${id}`;
            
            document.getElementById('editPengajarModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeEditModal() {
            document.getElementById('editPengajarModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('editPengajarForm').reset();
        }
        
        // Close edit modal on overlay click
        document.getElementById('editPengajarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPengajar');
            const filterStatus = document.getElementById('filterStatus');
            const filterKursus = document.getElementById('filterKursus');
            const tableRows = document.querySelectorAll('.table-row');
            
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const statusFilter = filterStatus.value;
                const kursusFilter = filterKursus.value;
                
                tableRows.forEach(row => {
                    const name = row.querySelector('.instructor-details h4').textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const courses = row.querySelector('.course-badges').textContent.toLowerCase();
                    const status = row.querySelector('.status-badge').classList.contains('active') ? 'active' : 'inactive';
                    const hasCourses = row.querySelector('.course-badges').textContent.includes('Belum ada kursus') ? 'none' : 'has';
                    
                    const matchSearch = name.includes(searchTerm) || email.includes(searchTerm) || courses.includes(searchTerm);
                    const matchStatus = !statusFilter || status === statusFilter;
                    const matchKursus = !kursusFilter || hasCourses === kursusFilter;
                    
                    if (matchSearch && matchStatus && matchKursus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('input', filterTable);
            }
            if (filterStatus) {
                filterStatus.addEventListener('change', filterTable);
            }
            if (filterKursus) {
                filterKursus.addEventListener('change', filterTable);
            }
        });
    </script>
@endpush
