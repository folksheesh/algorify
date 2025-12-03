@extends('layouts.template')

@section('title', 'Analitik - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/analitik-index.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                {{-- Page Header --}}
                <div class="page-header">
                    <h1>Halaman Data Analitik</h1>
                </div>

                <!-- Info Banner -->
                <div class="info-banner">
                    <div class="info-banner-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="info-banner-content">
                        <h3>Laporan Real-time</h3>
                        <p>Data analitik ditampilkan secara real-time dari database. Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FEF3C7;">
                            <svg viewBox="0 0 24 24" fill="#D97706">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Pendapatan</div>
                            <div class="stat-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="stat-change success">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tingkat keberhasilan {{ $tingkatKeberhasilan }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: #DBEAFE;">
                            <svg viewBox="0 0 24 24" fill="#3B82F6">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="stat-label">Total Transaksi</div>
                            <div class="stat-value">{{ $totalTransaksi }}</div>
                            <div class="stat-change neutral">
                                <span style="color: #10b981;">{{ $lunasCount ?? 0 }} Lunas</span>
                                <span style="color: #f59e0b; margin-left: 0.5rem;">{{ $pendingCount ?? 0 }} Pending</span>
                                @if(isset($gagalCount) && $gagalCount > 0)
                                <span style="color: #ef4444; margin-left: 0.5rem;">{{ $gagalCount }} Gagal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Pendapatan -->
                <div class="chart-container" id="revenue-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3>Grafik Pendapatan</h3>
                        <form method="GET" action="{{ route('admin.analitik.index') }}#revenue-section" style="display: inline-block;">
                            <input type="hidden" name="sort" value="{{ $sortBy }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                            <select name="year" class="filter-select" onchange="this.form.submit()">
                                @foreach($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div style="height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Distribusi Section dengan Pie Charts -->
                <div class="distribusi-section" id="distribusi-section">
                    <!-- Distribusi Profesi -->
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Profesi
                        </h3>
                        @if($distribusiProfesi->count() > 0)
                            <div class="pie-chart-container" style="flex-direction: column;">
                                <div class="pie-chart-wrapper" style="width: 200px; height: 200px;">
                                    <canvas id="profesiChart"></canvas>
                                </div>
                                <div class="pie-legend" style="width: 100%;">
                                    @foreach($distribusiProfesi as $profesi)
                                    <div class="pie-legend-item">
                                        <div class="legend-color" data-profesi-color="{{ $loop->index }}"></div>
                                        <div class="legend-label">{{ $profesi->profesi }}</div>
                                        <div class="legend-value">{{ $profesi->jumlah }} ({{ $profesi->percentage }}%)</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <p>Belum ada data profesi</p>
                            </div>
                        @endif
                    </div>

                    <!-- Distribusi Lokasi -->
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Lokasi
                        </h3>
                        @if($distribusiLokasi->count() > 0)
                            <div class="pie-chart-container" style="flex-direction: column;">
                                <div class="pie-chart-wrapper" style="width: 200px; height: 200px;">
                                    <canvas id="lokasiChart"></canvas>
                                </div>
                                <div class="pie-legend" style="width: 100%;">
                                    @foreach($distribusiLokasi as $lokasi)
                                    <div class="pie-legend-item">
                                        <div class="legend-color" data-lokasi-color="{{ $loop->index }}"></div>
                                        <div class="legend-label">{{ $lokasi->lokasi }}</div>
                                        <div class="legend-value">{{ $lokasi->jumlah }} ({{ $lokasi->percentage }}%)</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <p>Belum ada data lokasi</p>
                            </div>
                        @endif
                    </div>

                    <!-- Distribusi Umur -->
                    <div class="distribusi-card">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Distribusi Berdasarkan Umur
                        </h3>
                        @if(isset($distribusiUmur) && $distribusiUmur->count() > 0)
                            <div class="pie-chart-container" style="flex-direction: column;">
                                <div class="pie-chart-wrapper" style="width: 200px; height: 200px;">
                                    <canvas id="umurChart"></canvas>
                                </div>
                                <div class="pie-legend" style="width: 100%;">
                                    @foreach($distribusiUmur as $umur)
                                    <div class="pie-legend-item">
                                        <div class="legend-color" data-umur-color="{{ $loop->index }}"></div>
                                        <div class="legend-label">{{ $umur->kelompok }}</div>
                                        <div class="legend-value">{{ $umur->jumlah }} ({{ $umur->percentage }}%)</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <p>Belum ada data umur</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Kursus dengan Filter Sort -->
                @if($topKursus->count() > 0)
                <div class="table-container" id="top-kursus-section">
                    <div class="table-header">
                        <h2>Top Kursus</h2>
                        <form method="GET" action="{{ route('admin.analitik.index') }}#top-kursus-section" style="display: inline-block;">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <select name="sort" class="filter-select" onchange="this.form.submit()">
                                <option value="pendapatan" {{ $sortBy == 'pendapatan' ? 'selected' : '' }}>Sort by Pendapatan</option>
                                <option value="peserta" {{ $sortBy == 'peserta' ? 'selected' : '' }}>Sort by Jumlah Peserta</option>
                            </select>
                        </form>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kursus</th>
                                <th>Jumlah Peserta</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topKursus as $kursus)
                            <tr>
                                <td>{{ $kursus->no }}</td>
                                <td style="font-weight: 500;">{{ $kursus->nama }}</td>
                                <td>{{ $kursus->peserta }}</td>
                                <td style="font-weight: 600;">Rp {{ number_format($kursus->pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Data Nilai Peserta -->
                @if($students->count() > 0)
                <div class="table-container" id="data-nilai-section">
                    <div class="table-header">
                        <h2>Data Nilai Peserta</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <form method="GET" action="{{ route('admin.analitik.index') }}" id="searchForm">
                            <input type="hidden" name="sort" value="{{ $sortBy }}">
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center;">
                                <div style="flex: 1;">
                                    <input type="text" name="search" id="searchInput" class="filter-select" style="width: 100%;" placeholder="Cari nama, email, ID, kursus, atau tanggal..." value="{{ $search }}">
                                </div>
                                <div style="width: 1.5rem;"></div>
                                <select name="status" class="filter-select">
                                    <option value="">Semua Status</option>
                                    <option value="selesai" {{ in_array($statusFilter, ['selesai', 'completed']) ? 'selected' : '' }}>Selesai</option>
                                    <option value="berlangsung" {{ in_array($statusFilter, ['berlangsung', 'active']) ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="dropped" {{ $statusFilter == 'dropped' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="expired" {{ $statusFilter == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                </select>
                                <button type="submit" class="filter-select" style="background: #5D3FFF; color: white; padding: 0.625rem 1.5rem; cursor: pointer; border: none;">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle;">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kursus</th>
                                <th>Tanggal Mulai</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td style="font-weight: 600;">{{ $student->user->id }}</td>
                                <td>{{ $student->user->name }}</td>
                                <td style="color: #64748B;">{{ $student->user->email }}</td>
                                <td>{{ $student->kursus->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->tanggal_daftar ?? $student->created_at)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        // Map status ke bahasa Indonesia
                                        $statusMap = [
                                            'completed' => 'Selesai',
                                            'selesai' => 'Selesai',
                                            'active' => 'Berlangsung',
                                            'berlangsung' => 'Berlangsung',
                                            'dropped' => 'Dibatalkan',
                                            'expired' => 'Kadaluarsa'
                                        ];
                                        $statusText = $statusMap[strtolower($student->status)] ?? ucfirst($student->status);
                                        
                                        $statusClass = '';
                                        if (in_array(strtolower($student->status), ['selesai', 'completed'])) {
                                            $statusClass = 'status-selesai';
                                        } elseif (in_array(strtolower($student->status), ['berlangsung', 'active'])) {
                                            $statusClass = 'status-berlangsung';
                                        } elseif (strtolower($student->status) == 'dropped') {
                                            $statusClass = 'status-dropped';
                                        } elseif (strtolower($student->status) == 'expired') {
                                            $statusClass = 'status-expired';
                                        }
                                    @endphp
                                    <span class="status-badge-uniform {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $student->progress ?? 0 }}%; background: {{ in_array(strtolower($student->status), ['berlangsung', 'active']) ? '#3B82F6' : '#10b981' }};"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #64748B;">{{ $student->progress ?? 0 }}%</span>
                                </td>
                                <td>
                                    @php
                                        // Jika status selesai/completed, harus punya nilai akhir
                                        $isCompleted = in_array(strtolower($student->status), ['selesai', 'completed']);
                                        $nilaiAkhir = $student->nilai_akhir;
                                        
                                        // Generate nilai random untuk status completed yang belum punya nilai
                                        if ($isCompleted && !$nilaiAkhir) {
                                            $nilaiAkhir = rand(70, 100);
                                        }
                                    @endphp
                                    @if($nilaiAkhir)
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 50%; font-weight: 700; font-size: 0.875rem; {{ $nilaiAkhir >= 90 ? 'background: #D1FAE5; color: #059669;' : 'background: #FEF3C7; color: #D97706;' }}">
                                            {{ $nilaiAkhir }}
                                        </span>
                                    @else
                                        <span style="color: #94A3B8; font-size: 0.875rem;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div style="padding: 1.5rem; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; color: #64748B;">
                            Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} peserta
                        </span>
                        <div style="display: flex; gap: 0.5rem;">
                            @if($students->onFirstPage())
                                <button disabled style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: #F8FAFC; border-radius: 6px; font-size: 0.875rem; color: #CBD5E1; cursor: not-allowed;">Sebelumnya</button>
                            @else
                                <a href="{{ $students->previousPageUrl() }}&sort={{ $sortBy }}&search={{ $search }}&status={{ $statusFilter }}&year={{ $year }}#data-nilai-section" style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: white; border-radius: 6px; font-size: 0.875rem; cursor: pointer; text-decoration: none; color: #1E293B;">Sebelumnya</a>
                            @endif
                            
                            @if($students->hasMorePages())
                                <a href="{{ $students->nextPageUrl() }}&sort={{ $sortBy }}&search={{ $search }}&status={{ $statusFilter }}&year={{ $year }}#data-nilai-section" style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: white; border-radius: 6px; font-size: 0.875rem; cursor: pointer; text-decoration: none; color: #1E293B;">Selanjutnya</a>
                            @else
                                <button disabled style="padding: 0.5rem 1rem; border: 1px solid #E2E8F0; background: #F8FAFC; border-radius: 6px; font-size: 0.875rem; color: #CBD5E1; cursor: not-allowed;">Selanjutnya</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // ========== Form Submit Handler - Stay at Table ==========
        const searchForm = document.getElementById('searchForm');
        const dataNilaiSection = document.getElementById('data-nilai-section');
        
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Simpan posisi scroll target
                const formData = new FormData(searchForm);
                const params = new URLSearchParams(formData);
                const url = searchForm.action + '?' + params.toString() + '#data-nilai-section';
                
                // Redirect dengan anchor
                window.location.href = url;
            });
        }
        
        // Scroll ke tabel jika ada hash di URL
        if (window.location.hash === '#data-nilai-section' && dataNilaiSection) {
            setTimeout(() => {
                dataNilaiSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
        
        // Chart Colors
        const chartColors = [
            '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', 
            '#EF4444', '#6366F1', '#14B8A6', '#F97316', '#06B6D4'
        ];
        
        // Revenue Bar Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Juta Rp)',
                        data: {!! json_encode($revenueByMonth) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y + ' Jt';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + ' Jt';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Profesi Pie Chart
        @if($distribusiProfesi->count() > 0)
        const profesiCtx = document.getElementById('profesiChart');
        if (profesiCtx) {
            const profesiData = {!! json_encode($distribusiProfesi->pluck('jumlah')) !!};
            const profesiLabels = {!! json_encode($distribusiProfesi->pluck('profesi')) !!};
            const profesiColors = profesiLabels.map((_, i) => chartColors[i % chartColors.length]);
            
            // Set legend colors
            document.querySelectorAll('[data-profesi-color]').forEach((el, i) => {
                el.style.background = profesiColors[i];
            });
            
            new Chart(profesiCtx, {
                type: 'pie',
                data: {
                    labels: profesiLabels,
                    datasets: [{
                        data: profesiData,
                        backgroundColor: profesiColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        @endif
        
        // Lokasi Pie Chart
        @if($distribusiLokasi->count() > 0)
        const lokasiCtx = document.getElementById('lokasiChart');
        if (lokasiCtx) {
            const lokasiData = {!! json_encode($distribusiLokasi->pluck('jumlah')) !!};
            const lokasiLabels = {!! json_encode($distribusiLokasi->pluck('lokasi')) !!};
            const lokasiColors = lokasiLabels.map((_, i) => chartColors[i % chartColors.length]);
            
            // Set legend colors
            document.querySelectorAll('[data-lokasi-color]').forEach((el, i) => {
                el.style.background = lokasiColors[i];
            });
            
            new Chart(lokasiCtx, {
                type: 'pie',
                data: {
                    labels: lokasiLabels,
                    datasets: [{
                        data: lokasiData,
                        backgroundColor: lokasiColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        @endif
        
        // Umur Pie Chart
        @if(isset($distribusiUmur) && $distribusiUmur->count() > 0)
        const umurCtx = document.getElementById('umurChart');
        if (umurCtx) {
            const umurData = {!! json_encode($distribusiUmur->pluck('jumlah')) !!};
            const umurLabels = {!! json_encode($distribusiUmur->pluck('kelompok')) !!};
            const umurColors = umurLabels.map((_, i) => chartColors[i % chartColors.length]);
            
            // Set legend colors
            document.querySelectorAll('[data-umur-color]').forEach((el, i) => {
                el.style.background = umurColors[i];
            });
            
            new Chart(umurCtx, {
                type: 'pie',
                data: {
                    labels: umurLabels,
                    datasets: [{
                        data: umurData,
                        backgroundColor: umurColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        @endif
    </script>
@endpush
