@extends('layouts.template')

@section('title', 'Data Kursus - Algorify')

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
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 1.5rem;
        }
        .search-wrapper {
            margin-bottom: 2rem;
        }
        .search-box {
            position: relative;
            max-width: 500px;
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
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.9375rem;
            color: #1E293B;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .search-input::placeholder {
            color: #94A3B8;
        }
        .btn-add-floating {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1000;
        }
        .btn-add-floating:hover {
            background: #5568d3;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-add-floating svg {
            width: 28px;
            height: 28px;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        @media (max-width: 1400px) {
            .courses-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }
        }
        @media (max-width: 1024px) {
            .courses-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }
        @media (max-width: 640px) {
            .courses-grid {
                grid-template-columns: 1fr;
            }
        }
        .course-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            aspect-ratio: 1;
        }
        .course-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        .course-card:hover .card-overlay {
            opacity: 1;
            visibility: visible;
        }
        .course-thumbnail-container {
            width: 100%;
            height: 60%;
            position: relative;
            overflow: hidden;
        }
        .course-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .course-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.375rem 0.75rem;
            background: #EDE3FF;
            border-radius: 6px;
            font-size: 0.625rem;
            font-weight: 700;
            color: #7A3EF0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }
        .course-content {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            height: 40%;
            justify-content: space-between;
        }
        .course-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1A1A1A;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }
        .course-type {
            font-size: 0.875rem;
            color: #555;
            margin-top: 0.5rem;
            font-weight: 400;
        }
        .course-favorite {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 2;
        }
        .course-favorite:hover {
            background: white;
            transform: scale(1.1);
        }
        .course-favorite svg {
            width: 16px;
            height: 16px;
            color: #64748B;
        }
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
        }
        .overlay-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            color: #64748B;
        }
        .overlay-btn:hover {
            transform: scale(1.1);
        }
        .overlay-btn.view {
            background: #667eea;
            color: white;
        }
        .overlay-btn.view:hover {
            background: #5568d3;
        }
        .overlay-btn.edit:hover {
            background: #DBEAFE;
            color: #0284C7;
        }
        .overlay-btn.delete:hover {
            background: #FEE2E2;
            color: #DC2626;
        }
        .overlay-btn svg {
            width: 18px;
            height: 18px;
        }
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
            display: flex;
            flex-direction: column;
        }
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1A1A1A;
        }
        .modal-subtitle {
            font-size: 0.875rem;
            color: #64748B;
            margin-top: 0.25rem;
        }
        .modal-close {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: #E2E8F0;
        }
        .modal-close svg {
            width: 20px;
            height: 20px;
            color: #64748B;
        }
        .modal-body {
            padding: 2rem;
            padding-bottom: 5rem;
            overflow-y: auto;
            flex: 1 1 0;
            min-height: 0;
            max-height: 500px;
        }
        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .modal-left {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .modal-right {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .section-divider {
            width: 100%;
            height: 1px;
            background: #E2E8F0;
            margin: 0.5rem 0;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .form-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1E293B;
        }
        .form-label .required {
            color: #DC2626;
            margin-left: 0.25rem;
        }
        .form-input,
        .form-select,
        .form-textarea {
            padding: 0.625rem 0.875rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.8125rem;
            color: #1E293B;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        .form-textarea {
            resize: vertical;
            min-height: 70px;
        }
        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #94A3B8;
            font-size: 0.8125rem;
        }
        .upload-area {
            border: 2px dashed #E2E8F0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #F8FAFC;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .upload-area:hover {
            border-color: #667eea;
            background: #F1F5F9;
        }
        .upload-area.has-image {
            padding: 0;
            border: none;
            background: transparent;
        }
        .upload-icon {
            width: 40px;
            height: 40px;
            margin-bottom: 0.75rem;
            color: #94A3B8;
        }
        .upload-text {
            font-size: 0.8125rem;
            color: #64748B;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        .upload-hint {
            font-size: 0.6875rem;
            color: #94A3B8;
        }
        .preview-image {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            max-height: 140px;
        }
        .preview-container {
            position: relative;
        }
        .preview-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 32px;
            height: 32px;
            background: rgba(220, 38, 38, 0.9);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .preview-remove:hover {
            background: #DC2626;
        }
        .preview-remove svg {
            width: 16px;
            height: 16px;
            color: white;
        }
        .preview-card {
            background: #F8FAFC;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 1rem;
            min-height: 120px;
        }
        .preview-card-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 0.75rem;
        }
        .preview-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: #EDE3FF;
            color: #7A3EF0;
            border-radius: 6px;
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.625rem;
        }
        .preview-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1A1A1A;
            margin-bottom: 0.375rem;
            line-height: 1.3;
        }
        .preview-info {
            font-size: 0.8125rem;
            color: #555;
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
        .btn-secondary {
            padding: 0.75rem 1.5rem !important;
            background: white !important;
            color: #64748B !important;
            border: 2px solid #E2E8F0 !important;
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
        .btn-secondary:hover {
            background: #F8FAFC !important;
            border-color: #CBD5E1 !important;
        }
        .btn-primary {
            padding: 0.75rem 1.5rem !important;
            background: #667eea !important;
            color: white !important;
            border: 2px solid #667eea !important;
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
        .btn-primary:hover {
            background: #5568d3 !important;
            border-color: #5568d3 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3) !important;
        }
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-primary svg {
            width: 16px;
            height: 16px;
        }
        @media (max-width: 768px) {
            .modal-grid {
                grid-template-columns: 1fr;
            }
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
            margin-bottom: 1.5rem;
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
                    <h1 class="page-title">Data Kursus</h1>
                    
                    <!-- Search Bar -->
                    <div class="search-wrapper">
                        <div class="search-box">
                            <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                            <input 
                                type="text" 
                                class="search-input" 
                                id="searchKursus"
                                placeholder="Cari kursus berdasarkan judul..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>

                <!-- Floating Add Button -->
                <button class="btn-add-floating" title="Tambah Kursus" onclick="openModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Modal Add/Edit Kursus -->
                <div class="modal-overlay" id="modalKursus">
                    <div class="modal-container">
                        <div class="modal-header">
                            <div>
                                <h2 class="modal-title" id="modalTitle">Tambah Kursus</h2>
                                <p class="modal-subtitle">Masukkan informasi kursus pembelajaran</p>
                            </div>
                            <button class="modal-close" onclick="closeModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="formKursus" action="{{ route('admin.pelatihan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <input type="hidden" name="kursus_id" id="kursusId">
                            
                            <div class="modal-body">
                                <div class="modal-grid">
                                    <!-- Left Column -->
                                    <div class="modal-left">
                                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem;">Informasi Dasar</h3>
                                        
                                        <div class="form-group">
                                            <label class="form-label">
                                                Nama Kursus <span class="required">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                name="judul" 
                                                id="judul"
                                                class="form-input" 
                                                placeholder="Contoh: Peran & Tugas Frontend Developer"
                                                required
                                            >
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Kategori <span class="required">*</span>
                                                </label>
                                                <select name="kategori" id="kategori" class="form-select" required>
                                                    <option value="">Pilih Kategori</option>
                                                    <option value="programming">Programming</option>
                                                    <option value="design">UI/UX Design</option>
                                                    <option value="data_science">Data Science</option>
                                                    <option value="business">Business</option>
                                                    <option value="marketing">Marketing</option>
                                                    <option value="other">Lainnya</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">
                                                    Tipe Kursus <span class="required">*</span>
                                                </label>
                                                <select name="status" id="status" class="form-select" required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="published">Online</option>
                                                    <option value="draft">Hybrid</option>
                                                    <option value="archived">Offline</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">
                                                Deskripsi Kursus
                                            </label>
                                            <textarea 
                                                name="deskripsi" 
                                                id="deskripsi"
                                                class="form-textarea" 
                                                placeholder="Jelaskan tentang kursus ini..."
                                                rows="3"
                                            ></textarea>
                                        </div>

                                        <div class="section-divider"></div>

                                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem;">Detail Kursus</h3>
                                        
                                        <div class="form-group">
                                            <label class="form-label">
                                                Nama Pengajar <span class="required">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                name="pengajar" 
                                                id="pengajar"
                                                class="form-input" 
                                                placeholder="Nama pengajar/instruktur"
                                                value="{{ auth()->user()->name }}"
                                                required
                                            >
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Durasi <span class="required">*</span>
                                                </label>
                                                <input 
                                                    type="text" 
                                                    name="durasi" 
                                                    id="durasi"
                                                    class="form-input" 
                                                    placeholder="Contoh: 8 Minggu"
                                                    required
                                                >
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">
                                                    Harga <span class="required">*</span>
                                                </label>
                                                <input 
                                                    type="text" 
                                                    name="harga" 
                                                    id="harga"
                                                    class="form-input" 
                                                    placeholder="Contoh: Rp 2.500.000"
                                                    required
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="modal-right">
                                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem;">Thumbnail Kursus</h3>
                                        
                                        <div class="form-group">
                                            <input 
                                                type="file" 
                                                name="thumbnail" 
                                                id="thumbnail"
                                                accept="image/png,image/jpeg,image/jpg"
                                                style="display: none;"
                                                onchange="previewThumbnail(event)"
                                            >
                                            <div class="upload-area" id="uploadArea" onclick="document.getElementById('thumbnail').click()">
                                                <svg class="upload-icon" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <p class="upload-text">Upload Thumbnail</p>
                                                <p class="upload-hint">PNG, JPG maksimal 2MB</p>
                                            </div>
                                            <div id="previewContainer" class="preview-container" style="display: none;">
                                                <img id="previewImage" class="preview-image" alt="Preview">
                                                <button type="button" class="preview-remove" onclick="removeThumbnail(event)">
                                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #1A1A1A; margin-bottom: 0.5rem; margin-top: 1rem;">Preview Kartu Kursus</h3>
                                        
                                        <div class="preview-card">
                                            <p class="preview-card-label">No Image</p>
                                            <div id="previewCardContent">
                                                <span class="preview-badge" id="previewBadge">UI/UX DESIGN</span>
                                                <h4 class="preview-title" id="previewTitle">Nama Kursus</h4>
                                                <p class="preview-info" id="previewType">Online</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                                <button type="submit" class="btn-primary">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="btnSubmitText">Simpan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($kursus->count() > 0)
                    <!-- Courses Grid -->
                    <div class="courses-grid">
                        @foreach($kursus as $course)
                        <div class="course-card" onclick="window.location='{{ route('admin.pelatihan.show', $course->id) }}'" style="cursor: pointer;">
                            <div class="course-thumbnail-container">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                         alt="{{ $course->judul }}" 
                                         class="course-thumbnail"
                                         onerror="this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; this.style.objectFit='none';">
                                @else
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <span class="course-badge">{{ strtoupper($course->kategori ?? 'FRONTEND') }}</span>
                                @hasrole('peserta')
                                <button class="course-favorite" onclick="event.stopPropagation();">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                @endhasrole
                                <!-- Overlay with action buttons -->
                                <div class="card-overlay">
                                    <button class="overlay-btn edit" title="Edit" onclick="event.stopPropagation(); editKursus({{ $course->id }})">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button class="overlay-btn delete" title="Hapus" onclick="event.stopPropagation(); deleteKursus({{ $course->id }})">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $course->judul }}</h3>
                                <p class="course-type">{{ ucfirst($course->status ?? 'Hybrid') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z"/>
                            </svg>
                        </div>
                        <h3>Belum Ada Kursus</h3>
                        <p>Mulai tambahkan kursus baru untuk platform Anda</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKursus');
            const courseCards = document.querySelectorAll('.course-card');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    courseCards.forEach(card => {
                        const title = card.querySelector('.course-title');
                        if (title) {
                            const titleText = title.textContent.toLowerCase();
                            
                            if (titleText.includes(searchTerm)) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }

            // Live preview updates
            const judulInput = document.getElementById('judul');
            const kategoriSelect = document.getElementById('kategori');
            const statusSelect = document.getElementById('status');

            if (judulInput) {
                judulInput.addEventListener('input', function() {
                    document.getElementById('previewTitle').textContent = this.value || 'Nama Kursus';
                });
            }

            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const badges = {
                        'programming': 'PROGRAMMING',
                        'design': 'UI/UX DESIGN',
                        'data_science': 'DATA SCIENCE',
                        'business': 'BUSINESS',
                        'marketing': 'MARKETING',
                        'other': 'OTHER'
                    };
                    document.getElementById('previewBadge').textContent = badges[this.value] || 'UI/UX DESIGN';
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    const types = {
                        'published': 'Online',
                        'draft': 'Hybrid',
                        'archived': 'Offline'
                    };
                    document.getElementById('previewType').textContent = types[this.value] || 'Hybrid';
                });
            }
        });

        // Modal functions
        function openModal(mode = 'add') {
            const modal = document.getElementById('modalKursus');
            const form = document.getElementById('formKursus');
            const modalTitle = document.getElementById('modalTitle');
            const btnSubmitText = document.getElementById('btnSubmitText');
            
            if (mode === 'add') {
                modalTitle.textContent = 'Tambah Kursus';
                btnSubmitText.textContent = 'Simpan';
                form.reset();
                document.getElementById('formMethod').value = 'POST';
                form.action = '{{ route("admin.pelatihan.store") }}';
                resetPreview();
            } else if (mode === 'edit') {
                // Edit mode - modal title and button will be set by editKursus function
                // Just open the modal
            }
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('modalKursus');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('formKursus').reset();
            resetPreview();
        }

        function resetPreview() {
            document.getElementById('previewTitle').textContent = 'Nama Kursus';
            document.getElementById('previewBadge').textContent = 'UI/UX DESIGN';
            document.getElementById('previewType').textContent = 'Online';
            document.getElementById('uploadArea').style.display = 'flex';
            document.getElementById('previewContainer').style.display = 'none';
            document.getElementById('thumbnail').value = '';
        }

        function previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('uploadArea').style.display = 'none';
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function removeThumbnail(event) {
            event.stopPropagation();
            document.getElementById('thumbnail').value = '';
            document.getElementById('uploadArea').style.display = 'flex';
            document.getElementById('previewContainer').style.display = 'none';
        }

        function editKursus(id) {
            // Close any open modal first
            const modal = document.getElementById('modalKursus');
            modal.classList.remove('active');
            
            // Fetch course data via AJAX
            fetch(`/admin/pelatihan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formKursus');
                    const modalTitle = document.getElementById('modalTitle');
                    const btnSubmitText = document.getElementById('btnSubmitText');
                    
                    // Reset form first
                    form.reset();
                    
                    modalTitle.textContent = 'Edit Kursus';
                    btnSubmitText.textContent = 'Update';
                    
                    // Set form method to PUT
                    document.getElementById('formMethod').value = 'PUT';
                    form.action = `/admin/pelatihan/${id}`;
                    
                    // Fill form fields
                    document.getElementById('kursusId').value = data.id;
                    document.getElementById('judul').value = data.judul;
                    document.getElementById('kategori').value = data.kategori;
                    document.getElementById('status').value = data.status;
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    document.getElementById('harga').value = data.harga;
                    
                    // Update preview
                    document.getElementById('previewTitle').textContent = data.judul;
                    document.getElementById('previewBadge').textContent = data.kategori || 'UI/UX DESIGN';
                    document.getElementById('previewType').textContent = data.status || 'Online';
                    
                    if (data.thumbnail) {
                        document.getElementById('previewImage').src = '{{ asset("") }}' + data.thumbnail;
                        document.getElementById('uploadArea').style.display = 'none';
                        document.getElementById('previewContainer').style.display = 'block';
                    } else {
                        document.getElementById('uploadArea').style.display = 'flex';
                        document.getElementById('previewContainer').style.display = 'none';
                    }
                    
                    // Open modal with edit mode
                    setTimeout(() => {
                        openModal('edit');
                    }, 100);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data kursus');
                });
        }

        function deleteKursus(id) {
            if (confirm('Apakah Anda yakin ingin menghapus kursus ini?')) {
                fetch(`/admin/pelatihan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Kursus berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus kursus');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus kursus');
                });
            }
        }

        // Close modal on overlay click
        document.getElementById('modalKursus')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endpush
