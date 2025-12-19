@extends('layouts.template')

@section('title', 'Algorify - Jelajahi Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kursus/index.css') }}">

@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="pelatihan-container">
        <div class="dashboard-container with-topbar">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="pelatihan-content">
                    <div class="pelatihan-header">
                        <h1>Jelajahi Pelatihan</h1>
                    </div>

                    <div class="search-filter-section">
                        <div class="search-filter-wrapper">
                            <div class="search-box">
                                <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    id="searchKursus"
                                    placeholder="Cari pelatihan berdasarkan judul..."
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

                    <!-- Filter Modal -->
                    <div class="filter-modal-overlay" id="filterModal">
                        <div class="filter-modal">
                            <!-- Modal Header -->
                            <div class="filter-modal-header">
                                <div class="filter-modal-title-section">
                                    <h2 class="filter-modal-title">Filter Kursus</h2>
                                    <p class="filter-modal-subtitle">Pilih kriteria untuk menyaring kursus</p>
                                </div>
                                <button type="button" class="filter-modal-close" onclick="closeFilterModal()">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="filter-modal-body">
                                <!-- Active Filters -->
                                <div class="active-filters-section" id="activeFiltersSection" style="display: none;">
                                    <div class="active-filters-header">
                                        <span class="active-filters-label">Filter Aktif</span>
                                        <button type="button" class="clear-all-btn" onclick="clearAllFilters()">Hapus Semua</button>
                                    </div>
                                    <div class="active-filters-chips" id="activeFiltersChips"></div>
                                </div>

                                <div class="filter-sections">
                                    <!-- Kategori Section -->
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
                                            <!-- Autocomplete Search -->
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

                                    <!-- Tipe Kursus Section -->
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
                                                <div class="autocomplete-dropdown" id="tipeKursusDropdown">
                                                    <div class="autocomplete-item" data-value="online" data-label="Online" onclick="selectTipeKursus('online', 'Online')">Online</div>
                                                    <div class="autocomplete-item" data-value="offline" data-label="Offline" onclick="selectTipeKursus('offline', 'Offline')">Offline</div>
                                                    <div class="autocomplete-item" data-value="hybrid" data-label="Hybrid" onclick="selectTipeKursus('hybrid', 'Hybrid')">Hybrid</div>
                                                </div>
                                            </div>
                                            
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

                                    <!-- Pengajar Section -->
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
                            </div>

                            <!-- Modal Footer -->
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

                    @if($kursus->count() > 0)
                        <div class="courses-grid">
                            @foreach($kursus as $course)
                            <div class="course-card">
                                <div class="course-thumbnail">
                                    @php
                                        $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                    @endphp
                                    @if($courseThumbnailUrl)
                                        <img src="{{ $courseThumbnailUrl }}" alt="{{ $course->judul }}" />
                                    @endif
                                    <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori)) }}</span>
                                </div>
                                <div class="course-content">
                                    <h3 class="course-title">{{ $course->judul }}</h3>
                                    <p class="course-description">{{ $course->deskripsi_singkat }}</p>
                                    <a href="{{ route('kursus.show', $course->id) }}" class="view-detail-btn">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="pagination-info">
                            Menampilkan {{ $kursus->firstItem() }} - {{ $kursus->lastItem() }} dari {{ $kursus->total() }} pelatihan
                        </div>
                        <div class="pagination-wrapper">
                            {{ $kursus->links('vendor.pagination.custom') }}
                        </div>
                    @else
                        <div class="no-courses">
                            <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="60" cy="60" r="50" stroke="currentColor" stroke-width="2"/>
                                <path d="M40 55h40M40 65h40M40 75h25" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <h3 style="margin-bottom: 0.5rem; color: #374151;">Tidak ada pelatihan ditemukan</h3>
                            <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

@push('scripts')
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // ========================================
        // FILTER MODAL - STATE MANAGEMENT (Multi-Select)
        // ========================================
        
        const filterState = {
            kategori: [],
            tipeKursus: [],
            pengajar: []
        };

        // Initialize filters from URL params on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Parse kategori
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
            
            // Parse tipe_kursus
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
            
            // Parse pengajar_id
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

        document.getElementById('filterModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeFilterModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const filterModal = document.getElementById('filterModal');
                if (filterModal.classList.contains('active')) closeFilterModal();
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
            
            if (!wasSelected) {
                chip.classList.add('selected');
                if (filterType === 'kategori') {
                    filterState.kategori.push({ value, label });
                } else if (filterType === 'tipeKursus') {
                    filterState.tipeKursus.push({ value, label });
                } else if (filterType === 'pengajar') {
                    filterState.pengajar.push({ value, label });
                }
            } else {
                chip.classList.remove('selected');
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
            const existingIndex = filterState.kategori.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                filterState.kategori.push({ value, label });
            } else {
                filterState.kategori.splice(existingIndex, 1);
            }
            
            document.querySelectorAll('#kategoriChips .filter-chip').forEach(chip => {
                const isSelected = filterState.kategori.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            document.querySelectorAll('#kategoriDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.kategori.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
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
            const existingIndex = filterState.tipeKursus.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                filterState.tipeKursus.push({ value, label });
            } else {
                filterState.tipeKursus.splice(existingIndex, 1);
            }
            
            document.querySelectorAll('#tipeKursusChips .filter-chip').forEach(chip => {
                const isSelected = filterState.tipeKursus.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            document.querySelectorAll('#tipeKursusDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.tipeKursus.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
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
            const existingIndex = filterState.pengajar.findIndex(item => item.value === value);
            
            if (existingIndex === -1) {
                filterState.pengajar.push({ value, label });
            } else {
                filterState.pengajar.splice(existingIndex, 1);
            }
            
            document.querySelectorAll('#pengajarChips .filter-chip').forEach(chip => {
                const isSelected = filterState.pengajar.some(item => item.value === chip.dataset.value);
                chip.classList.toggle('selected', isSelected);
            });
            
            document.querySelectorAll('#pengajarDropdown .autocomplete-item').forEach(item => {
                const isSelected = filterState.pengajar.some(sel => sel.value === item.dataset.value);
                item.classList.toggle('selected', isSelected);
            });
            
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
            
            document.getElementById('kategoriSearch').value = '';
            document.getElementById('tipeKursusSearch').value = '';
            document.getElementById('pengajarSearch').value = '';
            
            document.querySelectorAll('.autocomplete-item').forEach(item => {
                item.style.display = 'flex';
            });
        }

        function applyFilters() {
            const url = new URL(window.location.href);
            url.search = '';
            
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
        
        // ========================================
        // SEARCH FUNCTIONALITY
        // ========================================
        
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
        });
    </script>
@endpush
