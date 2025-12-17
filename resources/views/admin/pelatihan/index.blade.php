@extends('layouts.template')

@section('title', 'Data Kursus - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-index.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
@endpush

@section('content')
    {{-- Topbar Pengajar --}}
    @role('pengajar')
    @include('components.topbar-pengajar')
    @endrole
    
    <div class="dashboard-container @role('pengajar') with-topbar @endrole">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-top">
                        <h1 class="page-title">Data Kursus</h1>
                    </div>
                    
                    <!-- Search Bar & Filters -->
                    <div class="search-filter-wrapper">
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
                        
                        <div class="filters-container">
                            @php
                                // Count total filter items (each comma-separated value counts as 1)
                                $kategoriCount = request('kategori') ? count(array_filter(explode(',', request('kategori')))) : 0;
                                $pengajarCount = request('pengajar_id') ? count(array_filter(explode(',', request('pengajar_id')))) : 0;
                                $tipeCount = request('tipe_kursus') ? count(array_filter(explode(',', request('tipe_kursus')))) : 0;
                                $totalFilters = $kategoriCount + $pengajarCount + $tipeCount;
                            @endphp
                            <button type="button" class="btn-filter {{ $totalFilters > 0 ? 'has-filters' : '' }}" onclick="openFilterModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                </svg>
                                <span>Filter</span>
                                @if($totalFilters > 0)
                                    <span class="filter-badge">{{ $totalFilters }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================
                     FILTER MODAL COMPONENT
                     ======================================== -->
                <div class="filter-modal-overlay" id="filterModal">
                    <div class="filter-modal-container">
                        
                        <!-- ====== MODAL HEADER (Sticky) ====== -->
                        <div class="filter-modal-header">
                            <div class="filter-header-content">
                                <h2 class="filter-modal-title">Filter Kursus</h2>
                                <p class="filter-modal-subtitle">Pilih kriteria untuk menyaring kursus</p>
                            </div>
                            <button class="filter-modal-close" onclick="closeFilterModal()" aria-label="Tutup modal">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        <!-- ====== SCROLLABLE CONTENT ====== -->
                        <div class="filter-modal-body">
                            
                            <!-- ====== ACTIVE FILTERS SECTION ====== -->
                            <div class="active-filters-section" id="activeFiltersSection" style="display: none;">
                                <div class="active-filters-header">
                                    <span class="active-filters-label">Filter Aktif</span>
                                    <button type="button" class="clear-all-btn" onclick="clearAllFilters()">
                                        Hapus Semua
                                    </button>
                                </div>
                                <div class="active-filters-chips" id="activeFiltersChips">
                                    <!-- Active filter chips will be rendered here by JavaScript -->
                                </div>
                            </div>

                            <!-- ====== KATEGORI SECTION (Collapsible) ====== -->
                            <div class="filter-collapsible-section" data-section="kategori">
                                <button type="button" class="collapsible-header" onclick="toggleSection('kategori')">
                                    <div class="collapsible-title">
                                        <svg class="section-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                        </svg>
                                        <span>Kategori</span>
                                        <span class="selected-count" id="kategoriCount" style="display: none;">0</span>
                                    </div>
                                    <svg class="collapsible-arrow" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="collapsible-content" id="kategoriContent">
                                    <!-- Autocomplete Search Input -->
                                    <div class="autocomplete-wrapper">
                                        <div class="autocomplete-input-box">
                                            <svg class="autocomplete-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                            </svg>
                                            <input 
                                                type="text" 
                                                class="autocomplete-input" 
                                                id="kategoriSearch"
                                                placeholder="Cari kategori..."
                                                autocomplete="off"
                                                oninput="handleKategoriSearch(this.value)"
                                                onfocus="showKategoriDropdown()"
                                            >
                                        </div>
                                        <!-- Autocomplete Dropdown -->
                                        <div class="autocomplete-dropdown" id="kategoriDropdown">
                                            @foreach($kategoris as $kategori)
                                                <div class="autocomplete-item" 
                                                     data-value="{{ $kategori->slug }}" 
                                                     data-label="{{ $kategori->nama_kategori }}"
                                                     onclick="selectKategori('{{ $kategori->slug }}', '{{ $kategori->nama_kategori }}')">
                                                    {{ $kategori->nama_kategori }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Kategori Chips -->
                                    <div class="chips-group" id="kategoriChips">
                                        @php
                                            $selectedKategoris = request('kategori') ? explode(',', request('kategori')) : [];
                                        @endphp
                                        @foreach($kategoris as $kategori)
                                            <button type="button" 
                                                    class="filter-chip {{ in_array($kategori->slug, $selectedKategoris) ? 'selected' : '' }}" 
                                                    data-value="{{ $kategori->slug }}"
                                                    data-label="{{ $kategori->nama_kategori }}"
                                                    onclick="toggleChip(this, 'kategori')">
                                                {{ $kategori->nama_kategori }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- ====== TIPE KURSUS SECTION (Collapsible) ====== -->
                            <div class="filter-collapsible-section" data-section="tipeKursus">
                                <button type="button" class="collapsible-header" onclick="toggleSection('tipeKursus')">
                                    <div class="collapsible-title">
                                        <svg class="section-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                        </svg>
                                        <span>Tipe Kursus</span>
                                        <span class="selected-count" id="tipeKursusCount" style="display: none;">0</span>
                                    </div>
                                    <svg class="collapsible-arrow" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="collapsible-content" id="tipeKursusContent">
                                    <!-- Autocomplete Search Input -->
                                    <div class="autocomplete-wrapper">
                                        <div class="autocomplete-input-box">
                                            <svg class="autocomplete-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                            </svg>
                                            <input 
                                                type="text" 
                                                class="autocomplete-input" 
                                                id="tipeKursusSearch"
                                                placeholder="Cari tipe kursus..."
                                                autocomplete="off"
                                                oninput="handleTipeKursusSearch(this.value)"
                                                onfocus="showTipeKursusDropdown()"
                                            >
                                        </div>
                                        <!-- Autocomplete Dropdown -->
                                        <div class="autocomplete-dropdown" id="tipeKursusDropdown">
                                            <div class="autocomplete-item" data-value="online" data-label="Online" onclick="selectTipeKursus('online', 'Online')">Online</div>
                                            <div class="autocomplete-item" data-value="offline" data-label="Offline" onclick="selectTipeKursus('offline', 'Offline')">Offline</div>
                                            <div class="autocomplete-item" data-value="hybrid" data-label="Hybrid" onclick="selectTipeKursus('hybrid', 'Hybrid')">Hybrid</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tipe Kursus Chips -->
                                    @php
                                        $selectedTipes = request('tipe_kursus') ? explode(',', request('tipe_kursus')) : [];
                                    @endphp
                                    <div class="chips-group" id="tipeKursusChips">
                                        <button type="button" 
                                                class="filter-chip {{ in_array('online', $selectedTipes) ? 'selected' : '' }}" 
                                                data-value="online"
                                                data-label="Online"
                                                onclick="toggleChip(this, 'tipeKursus')">
                                            <svg class="chip-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                            </svg>
                                            Online
                                        </button>
                                        <button type="button" 
                                                class="filter-chip {{ in_array('offline', $selectedTipes) ? 'selected' : '' }}" 
                                                data-value="offline"
                                                data-label="Offline"
                                                onclick="toggleChip(this, 'tipeKursus')">
                                            <svg class="chip-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                            </svg>
                                            Offline
                                        </button>
                                        <button type="button" 
                                                class="filter-chip {{ in_array('hybrid', $selectedTipes) ? 'selected' : '' }}" 
                                                data-value="hybrid"
                                                data-label="Hybrid"
                                                onclick="toggleChip(this, 'tipeKursus')">
                                            <svg class="chip-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13 7H7v6h6V7z"/>
                                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                            </svg>
                                            Hybrid
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- ====== PENGAJAR SECTION (Collapsible - Filter Lanjutan) ====== -->
                            <div class="filter-collapsible-section" data-section="pengajar">
                                <button type="button" class="collapsible-header" onclick="toggleSection('pengajar')">
                                    <div class="collapsible-title">
                                        <svg class="section-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Pengajar</span>
                                        <span class="selected-count" id="pengajarCount" style="display: none;">0</span>
                                    </div>
                                    <svg class="collapsible-arrow" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="collapsible-content collapsed" id="pengajarContent">
                                    <!-- Autocomplete Search Input -->
                                    <div class="autocomplete-wrapper">
                                        <div class="autocomplete-input-box">
                                            <svg class="autocomplete-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                            </svg>
                                            <input 
                                                type="text" 
                                                class="autocomplete-input" 
                                                id="pengajarSearch"
                                                placeholder="Cari nama pengajar..."
                                                autocomplete="off"
                                                oninput="handlePengajarSearch(this.value)"
                                                onfocus="showPengajarDropdown()"
                                            >
                                        </div>
                                        <!-- Autocomplete Dropdown -->
                                        <div class="autocomplete-dropdown" id="pengajarDropdown">
                                            @foreach($pengajars as $pengajar)
                                                <div class="autocomplete-item" 
                                                     data-value="{{ $pengajar->id }}" 
                                                     data-label="{{ $pengajar->name }}"
                                                     onclick="selectPengajar('{{ $pengajar->id }}', '{{ $pengajar->name }}')">
                                                    <div class="autocomplete-item-avatar">
                                                        {{ strtoupper(substr($pengajar->name, 0, 1)) }}
                                                    </div>
                                                    {{ $pengajar->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Pengajar Chips -->
                                    @php
                                        $selectedPengajars = request('pengajar_id') ? explode(',', request('pengajar_id')) : [];
                                    @endphp
                                    <div class="chips-group chips-wrap" id="pengajarChips">
                                        @foreach($pengajars as $pengajar)
                                            <button type="button" 
                                                    class="filter-chip {{ in_array((string)$pengajar->id, $selectedPengajars) ? 'selected' : '' }}" 
                                                    data-value="{{ $pengajar->id }}"
                                                    data-label="{{ $pengajar->name }}"
                                                    onclick="toggleChip(this, 'pengajar')">
                                                {{ $pengajar->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ====== MODAL FOOTER (Sticky) ====== -->
                        <div class="filter-modal-footer">
                            <button type="button" class="btn-reset-filter" onclick="resetFilters()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                                Reset Filter
                            </button>
                            <button type="button" class="btn-apply-filter" onclick="applyFilters()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>

                @hasanyrole('admin|super admin')
                <!-- Floating Add Button -->
                <button class="btn-add-floating" title="Tambah Kursus" onclick="openModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
                @endhasanyrole

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
                                <!-- Upload Gambar Section -->
                                <div class="form-group" style="margin-bottom: 2rem;">
                                    <label class="form-label">
                                        Upload Gambar Kursus
                                    </label>
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
                                        <p class="upload-text">Drag & drop Gambar</p>
                                        <p class="upload-hint">Format: PNG atau JPG (Max 1MB)</p>
                                        <button type="button" class="btn-upload" onclick="event.stopPropagation(); document.getElementById('thumbnail').click();">Pilih File</button>
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

                                <!-- Informasi Dasar Section -->
                                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1rem;">Informasi Dasar</h3>
                                
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
                                    @error('judul')
                                        <span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Kategori <span class="required">*</span>
                                        </label>
                                        <select name="kategori" id="kategori" class="form-select" required>
                                            <option value="" disabled selected hidden>Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->slug }}">{{ $kategori->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Tipe Kursus <span class="required">*</span>
                                        </label>
                                        <select name="tipe_kursus" id="tipe_kursus" class="form-select" required>
                                            <option value="" disabled selected hidden>Pilih Tipe Kursus</option>
                                            <option value="online">Online</option>
                                            <option value="hybrid">Hybrid</option>
                                            <option value="offline">Offline</option>
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

                                <!-- Detail Kursus Section -->
                                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1rem; margin-top: 1.5rem;">Detail Kursus</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        Nama Pengajar <span class="required">*</span>
                                    </label>
                                    <select name="pengajar_id" id="pengajar_id" class="form-select" required>
                                        <option value="" disabled selected hidden>Pilih Pengajar</option>
                                        @foreach($pengajars as $pengajar)
                                            <option value="{{ $pengajar->id }}">{{ $pengajar->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Durasi <span class="required">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="durasi" 
                                            id="durasi"
                                            class="form-input" 
                                            placeholder="Contoh: 8"
                                            min="1"
                                            step="1"
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
                                            inputmode="numeric"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Kapasitas Peserta (Opsional)
                                        </label>
                                        <input 
                                            type="number" 
                                            name="kapasitas" 
                                            id="kapasitas"
                                            class="form-input" 
                                            placeholder="Contoh: 30"
                                            min="1"
                                            step="1"
                                        >
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
                    <!-- No-match message (hidden by default) -->
                    <div id="noMatchMessage" style="display:none;background:#fff;padding:1rem;border-radius:8px;margin-bottom:1rem;border:1px dashed #e5e7eb;color:#374151;"></div>
                    <!-- Courses Grid -->
                    <div class="courses-grid">
                        @foreach($kursus as $course)
                        <div class="course-card" onclick="window.location='{{ route('admin.pelatihan.show', $course->id) }}'" style="cursor: pointer;">
                            <div class="course-thumbnail-container">
                                @php
                                    $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                @endphp
                                @if($course->thumbnail)
                                    <img src="{{ $courseThumbnailUrl }}" 
                                         alt="{{ $course->judul }}" 
                                         class="course-thumbnail"
                                         onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';">
                                @else
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori ?? 'OTHER')) }}</span>
                                @hasrole('peserta')
                                <button class="course-favorite" onclick="event.stopPropagation();">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                @endhasrole

                                @hasanyrole('admin|super admin')
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
                                @endhasanyrole
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
                        <div class="empty-state-illustration">
                            <svg viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Background Circle -->
                                <circle cx="100" cy="80" r="60" fill="#F1F5F9"/>
                                <!-- Book Stack -->
                                <rect x="60" y="55" width="80" height="12" rx="3" fill="#667eea" opacity="0.9"/>
                                <rect x="65" y="45" width="70" height="12" rx="3" fill="#818cf8" opacity="0.8"/>
                                <rect x="70" y="35" width="60" height="12" rx="3" fill="#a5b4fc" opacity="0.7"/>
                                <!-- Graduation Cap -->
                                <path d="M100 75L130 90L100 105L70 90L100 75Z" fill="#667eea"/>
                                <path d="M100 105V120" stroke="#667eea" stroke-width="3" stroke-linecap="round"/>
                                <path d="M85 95L85 110C85 115 92 120 100 120C108 120 115 115 115 110L115 95" stroke="#667eea" stroke-width="2.5" fill="none"/>
                                <circle cx="130" cy="90" r="4" fill="#667eea"/>
                                <path d="M130 90L130 115" stroke="#667eea" stroke-width="2.5" stroke-linecap="round"/>
                                <circle cx="130" cy="118" r="5" fill="#fbbf24"/>
                                <!-- Decorative Elements -->
                                <circle cx="45" cy="50" r="4" fill="#e0e7ff"/>
                                <circle cx="155" cy="45" r="6" fill="#e0e7ff"/>
                                <circle cx="160" cy="110" r="3" fill="#e0e7ff"/>
                                <circle cx="40" cy="100" r="5" fill="#e0e7ff"/>
                                <!-- Plus icons -->
                                <g opacity="0.5">
                                    <path d="M50 70H56M53 67V73" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M147 65H153M150 62V68" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <h3>Belum Ada Kursus</h3>
                        <p>Mulai tambahkan kursus baru untuk platform pembelajaran Anda</p>
                        @hasanyrole('admin|super admin')
                        <button type="button" class="empty-state-btn" onclick="openModal()">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Tambah Kursus Baru
                        </button>
                        @endhasanyrole
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Format harga ke rupiah saat input
        const hargaInput = document.getElementById('harga');
        if (hargaInput) {
            hargaInput.addEventListener('input', function (e) {
                const digits = (e.target.value || '').replace(/[^0-9]/g, '');
                if (!digits) {
                    e.target.value = '';
                    return;
                }
                const formatted = new Intl.NumberFormat('id-ID').format(parseInt(digits, 10));
                e.target.value = 'Rp ' + formatted;
            });
        }
        
        // ========================================
        // FILTER MODAL - STATE MANAGEMENT (Multi-Select)
        // ========================================
        
        // Store selected filters as arrays for multi-select
        const filterState = {
            kategori: [], // Array of {value, label}
            tipeKursus: [], // Array of {value, label}
            pengajar: [] // Array of {value, label}
        };

        // Initialize filters from URL params on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Parse URL params for initial state
            const urlParams = new URLSearchParams(window.location.search);
            
            // Parse kategori (comma-separated)
            const kategoriParam = urlParams.get('kategori');
            if (kategoriParam) {
                kategoriParam.split(',').forEach(value => {
                    const chip = document.querySelector(`#kategoriChips .filter-chip[data-value="${value}"]`);
                    if (chip) {
                        chip.classList.add('selected');
                        filterState.kategori.push({ value: value, label: chip.dataset.label });
                    }
                });
            }
            
            // Parse tipe_kursus (comma-separated)
            const tipeParam = urlParams.get('tipe_kursus');
            if (tipeParam) {
                tipeParam.split(',').forEach(value => {
                    const chip = document.querySelector(`#tipeKursusChips .filter-chip[data-value="${value}"]`);
                    if (chip) {
                        chip.classList.add('selected');
                        filterState.tipeKursus.push({ value: value, label: chip.dataset.label });
                    }
                });
            }
            
            // Parse pengajar_id (comma-separated)
            const pengajarParam = urlParams.get('pengajar_id');
            if (pengajarParam) {
                pengajarParam.split(',').forEach(value => {
                    const chip = document.querySelector(`#pengajarChips .filter-chip[data-value="${value}"]`);
                    if (chip) {
                        chip.classList.add('selected');
                        filterState.pengajar.push({ value: value, label: chip.dataset.label });
                    }
                });
            }
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        });

        // ========================================
        // FILTER MODAL - OPEN/CLOSE
        // ========================================
        
        function openFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            updateActiveFiltersDisplay();
        }

        function closeFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            hideAllDropdowns();
        }

        // Close modal on overlay click
        document.getElementById('filterModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const filterModal = document.getElementById('filterModal');
                if (filterModal.classList.contains('active')) {
                    closeFilterModal();
                }
            }
        });

        // ========================================
        // COLLAPSIBLE SECTIONS
        // ========================================
        
        function toggleSection(sectionName) {
            const content = document.getElementById(sectionName + 'Content');
            const section = content.closest('.filter-collapsible-section');
            const arrow = section.querySelector('.collapsible-arrow');
            
            content.classList.toggle('collapsed');
            arrow.classList.toggle('rotated');
        }

        // ========================================
        // FILTER CHIPS - TOGGLE SELECTION (Multi-Select)
        // ========================================
        
        function toggleChip(chip, filterType) {
            const value = chip.dataset.value;
            const label = chip.dataset.label;
            const wasSelected = chip.classList.contains('selected');
            
            // Toggle selection on clicked chip (multi-select)
            if (!wasSelected) {
                chip.classList.add('selected');
                
                // Add to filter state array
                if (filterType === 'kategori') {
                    filterState.kategori.push({ value, label });
                } else if (filterType === 'tipeKursus') {
                    filterState.tipeKursus.push({ value, label });
                } else if (filterType === 'pengajar') {
                    filterState.pengajar.push({ value, label });
                }
            } else {
                chip.classList.remove('selected');
                
                // Remove from filter state array
                if (filterType === 'kategori') {
                    filterState.kategori = filterState.kategori.filter(item => item.value !== value);
                } else if (filterType === 'tipeKursus') {
                    filterState.tipeKursus = filterState.tipeKursus.filter(item => item.value !== value);
                } else if (filterType === 'pengajar') {
                    filterState.pengajar = filterState.pengajar.filter(item => item.value !== value);
                }
            }
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        // ========================================
        // AUTOCOMPLETE DROPDOWNS
        // ========================================
        
        function hideAllDropdowns() {
            document.querySelectorAll('.autocomplete-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.autocomplete-wrapper')) {
                hideAllDropdowns();
            }
        });

        // Kategori autocomplete
        function showKategoriDropdown() {
            hideAllDropdowns();
            document.getElementById('kategoriDropdown').classList.add('show');
        }

        function handleKategoriSearch(value) {
            const dropdown = document.getElementById('kategoriDropdown');
            const items = dropdown.querySelectorAll('.autocomplete-item');
            const searchTerm = value.toLowerCase();
            
            dropdown.classList.add('show');
            
            items.forEach(item => {
                const label = item.dataset.label.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function selectKategori(value, label) {
            // Check if already selected (toggle behavior)
            const existingIndex = filterState.kategori.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                // Add to selection
                filterState.kategori.push({ value, label });
            } else {
                // Remove from selection
                filterState.kategori.splice(existingIndex, 1);
            }
            
            // Update chips selection
            document.querySelectorAll('#kategoriChips .filter-chip').forEach(chip => {
                const isSelected = filterState.kategori.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            // Update autocomplete dropdown items selection
            document.querySelectorAll('#kategoriDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.kategori.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
            // Clear search input (don't hide dropdown to allow multiple selections)
            document.getElementById('kategoriSearch').value = '';
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        // Tipe Kursus autocomplete
        function showTipeKursusDropdown() {
            hideAllDropdowns();
            document.getElementById('tipeKursusDropdown').classList.add('show');
        }

        function handleTipeKursusSearch(value) {
            const dropdown = document.getElementById('tipeKursusDropdown');
            const items = dropdown.querySelectorAll('.autocomplete-item');
            const searchTerm = value.toLowerCase();
            
            dropdown.classList.add('show');
            
            items.forEach(item => {
                const label = item.dataset.label.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function selectTipeKursus(value, label) {
            // Check if already selected (toggle behavior)
            const existingIndex = filterState.tipeKursus.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                // Add to selection
                filterState.tipeKursus.push({ value, label });
            } else {
                // Remove from selection
                filterState.tipeKursus.splice(existingIndex, 1);
            }
            
            // Update chips selection
            document.querySelectorAll('#tipeKursusChips .filter-chip').forEach(chip => {
                const isSelected = filterState.tipeKursus.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            // Update autocomplete dropdown items selection
            document.querySelectorAll('#tipeKursusDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.tipeKursus.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
            // Clear search input (don't hide dropdown to allow multiple selections)
            document.getElementById('tipeKursusSearch').value = '';
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        // Pengajar autocomplete
        function showPengajarDropdown() {
            hideAllDropdowns();
            document.getElementById('pengajarDropdown').classList.add('show');
        }

        function handlePengajarSearch(value) {
            const dropdown = document.getElementById('pengajarDropdown');
            const items = dropdown.querySelectorAll('.autocomplete-item');
            const searchTerm = value.toLowerCase();
            
            dropdown.classList.add('show');
            
            items.forEach(item => {
                const label = item.dataset.label.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function selectPengajar(value, label) {
            // Check if already selected (toggle behavior)
            const existingIndex = filterState.pengajar.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                // Add to selection
                filterState.pengajar.push({ value, label });
            } else {
                // Remove from selection
                filterState.pengajar.splice(existingIndex, 1);
            }
            
            // Update chips selection
            document.querySelectorAll('#pengajarChips .filter-chip').forEach(chip => {
                const isSelected = filterState.pengajar.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            // Update autocomplete dropdown items selection
            document.querySelectorAll('#pengajarDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.pengajar.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
            // Clear search input (don't hide dropdown to allow multiple selections)
            document.getElementById('pengajarSearch').value = '';
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        // ========================================
        // ACTIVE FILTERS DISPLAY (Multi-Select)
        // ========================================
        
        function updateActiveFiltersDisplay() {
            const section = document.getElementById('activeFiltersSection');
            const chipsContainer = document.getElementById('activeFiltersChips');
            
            const hasFilters = filterState.kategori.length > 0 || filterState.tipeKursus.length > 0 || filterState.pengajar.length > 0;
            
            if (hasFilters) {
                section.style.display = 'block';
                let html = '';
                
                // Show all selected kategori
                filterState.kategori.forEach(item => {
                    html += `
                        <span class="active-filter-chip">
                            <span class="active-filter-type">Kategori:</span>
                            ${item.label}
                            <button type="button" class="remove-filter-btn" onclick="removeFilter('kategori', '${item.value}')">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    `;
                });
                
                // Show all selected tipe kursus
                filterState.tipeKursus.forEach(item => {
                    html += `
                        <span class="active-filter-chip">
                            <span class="active-filter-type">Tipe:</span>
                            ${item.label}
                            <button type="button" class="remove-filter-btn" onclick="removeFilter('tipeKursus', '${item.value}')">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    `;
                });
                
                // Show all selected pengajar
                filterState.pengajar.forEach(item => {
                    html += `
                        <span class="active-filter-chip">
                            <span class="active-filter-type">Pengajar:</span>
                            ${item.label}
                            <button type="button" class="remove-filter-btn" onclick="removeFilter('pengajar', '${item.value}')">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    `;
                });
                
                chipsContainer.innerHTML = html;
            } else {
                section.style.display = 'none';
            }
        }

        function removeFilter(filterType, value) {
            if (filterType === 'kategori') {
                filterState.kategori = filterState.kategori.filter(item => item.value !== value);
                const chip = document.querySelector(`#kategoriChips .filter-chip[data-value="${value}"]`);
                if (chip) chip.classList.remove('selected');
                const dropdownItem = document.querySelector(`#kategoriDropdown .autocomplete-item[data-value="${value}"]`);
                if (dropdownItem) dropdownItem.classList.remove('selected');
            } else if (filterType === 'tipeKursus') {
                filterState.tipeKursus = filterState.tipeKursus.filter(item => item.value !== value);
                const chip = document.querySelector(`#tipeKursusChips .filter-chip[data-value="${value}"]`);
                if (chip) chip.classList.remove('selected');
                const dropdownItem = document.querySelector(`#tipeKursusDropdown .autocomplete-item[data-value="${value}"]`);
                if (dropdownItem) dropdownItem.classList.remove('selected');
            } else if (filterType === 'pengajar') {
                filterState.pengajar = filterState.pengajar.filter(item => item.value !== value);
                const chip = document.querySelector(`#pengajarChips .filter-chip[data-value="${value}"]`);
                if (chip) chip.classList.remove('selected');
                const dropdownItem = document.querySelector(`#pengajarDropdown .autocomplete-item[data-value="${value}"]`);
                if (dropdownItem) dropdownItem.classList.remove('selected');
            }
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        function clearAllFilters() {
            filterState.kategori = [];
            filterState.tipeKursus = [];
            filterState.pengajar = [];
            
            document.querySelectorAll('.filter-chip.selected').forEach(c => c.classList.remove('selected'));
            document.querySelectorAll('.autocomplete-item.selected').forEach(item => item.classList.remove('selected'));
            
            updateActiveFiltersDisplay();
            updateSelectedCounts();
        }

        // ========================================
        // SELECTED COUNTS (Multi-Select)
        // ========================================
        
        function updateSelectedCounts() {
            const kategoriCount = document.getElementById('kategoriCount');
            const tipeKursusCount = document.getElementById('tipeKursusCount');
            const pengajarCount = document.getElementById('pengajarCount');
            
            if (filterState.kategori.length > 0) {
                kategoriCount.textContent = filterState.kategori.length;
                kategoriCount.style.display = 'inline-flex';
            } else {
                kategoriCount.style.display = 'none';
            }
            
            if (filterState.tipeKursus.length > 0) {
                tipeKursusCount.textContent = filterState.tipeKursus.length;
                tipeKursusCount.style.display = 'inline-flex';
            } else {
                tipeKursusCount.style.display = 'none';
            }
            
            if (filterState.pengajar.length > 0) {
                pengajarCount.textContent = filterState.pengajar.length;
                pengajarCount.style.display = 'inline-flex';
            } else {
                pengajarCount.style.display = 'none';
            }
        }

        // ========================================
        // RESET & APPLY FILTERS
        // ========================================
        
        function resetFilters() {
            clearAllFilters();
            
            // Clear search inputs
            document.getElementById('kategoriSearch').value = '';
            document.getElementById('tipeKursusSearch').value = '';
            document.getElementById('pengajarSearch').value = '';
            
            // Show all dropdown items
            document.querySelectorAll('.autocomplete-item').forEach(item => {
                item.style.display = 'flex';
            });
        }

        function applyFilters() {
            const url = new URL(window.location.href);
            url.search = ''; // Clear existing params
            
            // Join multiple values with comma for multi-select
            if (filterState.kategori.length > 0) {
                url.searchParams.set('kategori', filterState.kategori.map(item => item.value).join(','));
            }
            
            if (filterState.pengajar.length > 0) {
                url.searchParams.set('pengajar_id', filterState.pengajar.map(item => item.value).join(','));
            }

            if (filterState.tipeKursus.length > 0) {
                url.searchParams.set('tipe_kursus', filterState.tipeKursus.map(item => item.value).join(','));
            }
            
            window.location.href = url.toString();
        }
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKursus');
            const courseCards = document.querySelectorAll('.course-card');
            
            if (searchInput) {
                const noMatchEl = document.getElementById('noMatchMessage');
                searchInput.addEventListener('input', function(e) {
                    const rawValue = e.target.value || '';
                    const searchTerm = rawValue.toLowerCase().trim();

                    let visibleCount = 0;
                    courseCards.forEach(card => {
                        const title = card.querySelector('.course-title');
                        if (title) {
                            const titleText = title.textContent.toLowerCase();
                            if (searchTerm === '' || titleText.includes(searchTerm)) {
                                // reset display so CSS/grid can control layout
                                card.style.display = '';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });

                    if (noMatchEl) {
                        if (visibleCount === 0 && searchTerm !== '') {
                            noMatchEl.style.display = 'block';
                            // Use textContent to avoid HTML injection
                            noMatchEl.textContent = 'Tidak dapat menemukan kursus dengan nama "' + rawValue + '"';
                        } else {
                            noMatchEl.style.display = 'none';
                        }
                    }
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
                    document.getElementById('tipe_kursus').value = data.tipe_kursus || 'online';
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    document.getElementById('pengajar_id').value = data.user_id || '{{ auth()->id() }}';
                    document.getElementById('durasi').value = data.durasi || '';
                    document.getElementById('harga').value = data.harga;
                    document.getElementById('kapasitas').value = data.kapasitas || '';
                    
                    if (data.thumbnail) {
                        // Gunakan storage path yang benar
                        const thumbnailUrl = data.thumbnail.startsWith('http') 
                            ? data.thumbnail 
                            : '{{ asset("storage") }}/' + data.thumbnail;
                        document.getElementById('previewImage').src = thumbnailUrl;
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
                    showToast('Gagal memuat data kursus', 'error');
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
                        showToast('Kursus berhasil dihapus', 'success');
                        location.reload();
                    } else {
                        showToast('Gagal menghapus kursus', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus kursus', 'error');
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

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            
            const icon = type === 'success' 
                ? '<svg class="toast-icon success" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                : '<svg class="toast-icon error" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
            
            const title = type === 'success' ? 'Berhasil!' : 'Error!';
            
            toast.innerHTML = `
                ${icon}
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        // Show toast on page load if there are messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif
    </script>
@endpush
