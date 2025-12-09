@extends('layouts.template')

@section('title', 'Algorify - Admin Dashboards')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #5D3FFF 0%, #5D3FFF 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            margin-top: 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .admin-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .admin-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.6;
        }
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card-modern {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .stat-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon-wrapper svg {
            width: 36px;
            height: 36px;
        }
        .stat-info h3 {
            font-size: 0.875rem;
            color: #64748B;
            margin: 0 0 0.5rem 0;
            font-weight: 500;
            line-height: 1;
        }
        .stat-info p {
            font-size: 2rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
            line-height: 1;
        }
        /* Modern Pie Chart Styles */
        .pie-chart-container {
            position: relative;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pie-legend-modern {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .legend-item-modern {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: #F8FAFC;
            border-radius: 8px;
            transition: all 0.2s;
            gap: 1rem;
        }
        .legend-item-modern:hover {
            background: #F1F5F9;
            transform: translateX(4px);
        }
        .legend-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #475569;
            font-weight: 500;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            flex-shrink: 0;
        }
        .legend-percentage {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1E293B;
        }
        /* Empty State */
        .empty-state-modern {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            text-align: center;
            height: 280px;
        }
        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .empty-state-icon svg {
            width: 40px;
            height: 40px;
            color: #94A3B8;
        }
        .empty-state-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .empty-state-description {
            font-size: 0.875rem;
            color: #94A3B8;
            max-width: 300px;
        }
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1E293B;
        }
        .chart-dropdown {
            padding: 0.5rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #64748B;
            background: white;
            cursor: pointer;
        }
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }
        .stat-card-modern {
            cursor: pointer;
        }
        
        /* Pie Chart Side by Side Layout */
        .pie-chart-wrapper {
            display: flex;
            align-items: center;
            gap: 2rem;
            justify-content: space-between;
        }
        .pie-chart-container {
            flex: 0 0 200px;
            height: 200px;
        }
        .pie-legend-modern {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        /* Responsive Design */
        @media (max-width: 1280px) {
            .stat-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 1024px) {
            .stat-cards-grid {
                grid-template-columns: 1fr;
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .admin-header {
                padding: 2rem 1.5rem;
            }
            .admin-header h1 {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 768px) {
            .pie-chart-wrapper {
                flex-direction: column;
                gap: 1.5rem;
            }
            .pie-chart-container {
                flex: 0 0 240px;
                height: 240px;
                width: 100%;
            }
            .chart-card {
                padding: 1.25rem;
            }
            .stat-card-modern {
                padding: 1.25rem;
            }
            .admin-header {
                padding: 1.5rem 1rem;
                margin-top: 1rem;
            }
            .admin-header h1 {
                font-size: 1.5rem;
            }
            .admin-header p {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 640px) {
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .chart-dropdown {
                width: 100%;
            }
            .stat-icon-wrapper {
                width: 60px;
                height: 60px;
            }
            .stat-icon-wrapper svg {
                width: 30px;
                height: 30px;
            }
            .stat-info p {
                font-size: 1.75rem;
            }
            .page-header {
                padding: 1rem;
            }
            .page-header h1 {
                font-size: 1.25rem;
            }
        }
        
        /* Responsive container padding */
        .dashboard-content-wrapper {
            padding: 0 2rem 2rem;
        }
        .dashboard-page-header {
            margin: 0 2rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-content-wrapper {
                padding: 0 1rem 1rem;
            }
            .dashboard-page-header {
                margin: 0 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .dashboard-content-wrapper {
                padding: 0 0.75rem 0.75rem;
            }
            .dashboard-page-header {
                margin: 0 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <header class="main-header">
            </header>
            
            <!-- Page Header -->
            <div class="page-header dashboard-page-header">
                <h1>Dashboard Admin</h1>
            </div>
            
            <div class="dashboard-content-wrapper">
                <!-- Admin Header Banner -->
                <div class="admin-header">
                    <h1>Halo, Admin!</h1>
                    <p>"Selamat datang di halaman Admin. Kelola peserta, pengajar, course, dan transaksi dengan mudah untuk mendukung jalannya pelatihan TIK."</p>
                </div>

                <!-- Stat Cards -->
                <div class="stat-cards-grid">
                    <a href="{{ route('admin.peserta.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #5D3FFF 0%, #5D3FFF 100%);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="white"/>
                                    <path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <h3>Total Peserta</h3>
                                <p>{{ $totalPeserta }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.pengajar.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="stat-info">
                                <h3>Total Pengajar</h3>
                                <p>{{ $totalPengajar }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.pelatihan.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" fill="white"/>
                                    <path d="M8 10H16M8 14H12" stroke="#00f2fe" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <h3>Total Kursus</h3>
                                <p>{{ $totalKursus }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Charts Grid -->
                <div class="charts-grid">
                    <!-- Transaksi Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Metode Transaksi</h3>
                            <select class="chart-dropdown" id="transaksiFilter">
                                <option value="all">Semua Waktu</option>
                                <option value="7_hari">7 Hari Terakhir</option>
                                <option value="bulan_ini">Bulan Ini</option>
                                <option value="bulan_lalu">Bulan Lalu</option>
                                <option value="tahun_ini">Tahun Ini</option>
                            </select>
                        </div>
                        <div id="transaksiChartContainer">
                            <div class="pie-chart-wrapper">
                                <div class="pie-chart-container">
                                    <canvas id="transaksiChart"></canvas>
                                </div>
                                <div id="transaksisLegend" class="pie-legend-modern"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pertumbuhan Peserta Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Pertumbuhan Peserta</h3>
                            <select class="chart-dropdown" id="pertumbuhanFilter">
                                <!-- Years will be populated dynamically -->
                            </select>
                        </div>
                        <div class="chart-content" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="pertumbuhanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Transaksi Donut Chart
        const transaksiCtx = document.getElementById('transaksiChart').getContext('2d');
        let transaksiChart = null;

        // Function to load transaksi data with modern styling
        async function loadTransaksiData(filter = 'all') {
            try {
                const response = await fetch(`{{ route('admin.dashboard.transaksi-data') }}?filter=${filter}`);
                const data = await response.json();
                
                const container = document.getElementById('transaksiChartContainer');
                const legendContainer = document.getElementById('transaksisLegend');
                
                // Check if no data
                if (!data.values || data.values.length === 0 || data.values.every(v => v === 0)) {
                    // Show empty state
                    if (transaksiChart) transaksiChart.destroy();
                    container.innerHTML = `
                        <div class="empty-state-modern">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="empty-state-title">Belum Ada Transaksi</div>
                            <p class="empty-state-description">Data transaksi akan muncul di sini setelah ada pembayaran yang berhasil</p>
                        </div>
                    `;
                    return;
                }
                
                // Reset container
                container.innerHTML = `
                    <div class="pie-chart-wrapper">
                        <div class="pie-chart-container">
                            <canvas id="transaksiChart"></canvas>
                        </div>
                        <div id="transaksisLegend" class="pie-legend-modern"></div>
                    </div>
                `;
                
                const newCtx = document.getElementById('transaksiChart').getContext('2d');
                
                // Map payment method names
                const methodNames = {
                    'bank_transfer': 'Transfer Bank',
                    'e_wallet': 'E-Wallet',
                    'credit_card': 'Kartu Kredit',
                    'qris': 'Qris',
                    'mini_market': 'Mini Market',
                    'kartu_debit': 'Kartu Debit'
                };
                
                const properLabels = data.labels.map(label => methodNames[label] || label);
                const total = data.values.reduce((sum, val) => sum + val, 0);
                const percentages = data.values.map(val => ((val / total) * 100).toFixed(1));
                
                // Warna untuk setiap metode pembayaran
                const colorMap = {
                    'Transfer Bank': '#5D3FFF',
                    'E-Wallet': '#f093fb',
                    'Kartu Kredit': '#4facfe',
                    'Qris': '#10B981',
                    'Mini Market': '#F59E0B',
                    'Kartu Debit': '#7C3AED'
                };
                const colors = properLabels.map(label => colorMap[label] || '#94A3B8');
                
                // Destroy existing chart
                if (transaksiChart) transaksiChart.destroy();
                
                // Create modern pie chart
                transaksiChart = new Chart(newCtx, {
                    type: 'pie',
                    data: {
                        labels: properLabels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: colors,
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                padding: 12,
                                titleFont: { size: 13, weight: '600' },
                                bodyFont: { size: 12 },
                                borderColor: '#E2E8F0',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed;
                                        const percentage = percentages[context.dataIndex];
                                        return ` ${value} transaksi (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Create custom modern legend
                const newLegendContainer = document.getElementById('transaksisLegend');
                newLegendContainer.innerHTML = properLabels.map((label, i) => `
                    <div class="legend-item-modern">
                        <div class="legend-label">
                            <div class="legend-color" style="background-color: ${colors[i]}"></div>
                            <span>${label}</span>
                        </div>
                        <div class="legend-percentage">${percentages[i]}%</div>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Error loading transaksi data:', error);
                const container = document.getElementById('transaksiChartContainer');
                container.innerHTML = `
                    <div class="empty-state-modern">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="empty-state-title">Terjadi Kesalahan</div>
                        <p class="empty-state-description">Gagal memuat data transaksi. Silakan refresh halaman.</p>
                    </div>
                `;
            }
        }

        // Pertumbuhan Peserta Line Chart (Monthly)
        let pertumbuhanChart = null;

        // Populate year dropdown dynamically (scalable, no future years)
        function populateYearDropdown() {
            const dropdown = document.getElementById('pertumbuhanFilter');
            const currentYear = new Date().getFullYear();
            const startYear = 2024; // Start from 2024
            
            dropdown.innerHTML = '<option value="all">Semua Tahun</option>';
            
            // Start from current year down to start year (no future years)
            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                dropdown.appendChild(option);
            }
        }
        
        // Function to load pertumbuhan data
        async function loadPertumbuhanData(year = new Date().getFullYear()) {
            try {
                const response = await fetch(`{{ route('admin.dashboard.pertumbuhan-data') }}?year=${year}`);
                const data = await response.json();
                
                const labels = data.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const values = data.values || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                
                // Check if all values are zero
                const hasData = values.some(v => v > 0);
                
                // Destroy existing chart if it exists
                if (pertumbuhanChart) {
                    pertumbuhanChart.destroy();
                    pertumbuhanChart = null;
                }
                
                if (!hasData) {
                    // Show empty state
                    const canvas = document.getElementById('pertumbuhanChart');
                    if (canvas && canvas.parentElement) {
                        const container = canvas.parentElement;
                        container.innerHTML = `
                            <div class="empty-state-modern">
                                <div class="empty-state-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="empty-state-title">Belum Ada Peserta</div>
                                <p class="empty-state-description">Belum ada peserta yang mendaftar ${year === 'all' ? 'di sistem ini' : 'pada tahun ' + year}</p>
                            </div>
                        `;
                    }
                    return;
                }
                
                // Reset container if showing empty state
                let canvas = document.getElementById('pertumbuhanChart');
                if (!canvas) {
                    const container = document.querySelector('.chart-content');
                    if (container) {
                        container.innerHTML = '<canvas id="pertumbuhanChart"></canvas>';
                        canvas = document.getElementById('pertumbuhanChart');
                    }
                }
                
                // Get fresh context from canvas
                if (!canvas) return;
                const pertumbuhanCtx = canvas.getContext('2d');
                
                // Dynamic Y-axis calculation
                const maxValue = Math.max(...values, 0);
                let yAxisMax, stepSize;
                
                if (maxValue <= 10) {
                    // For small values (0-10), use max 10 with step 2
                    yAxisMax = 10;
                    stepSize = 2;
                } else if (maxValue <= 30) {
                    // For medium values (11-30), use max 30 with step 5
                    yAxisMax = 30;
                    stepSize = 5;
                } else if (maxValue <= 50) {
                    // For higher values (31-50), use max 50 with step 10
                    yAxisMax = 50;
                    stepSize = 10;
                } else if (maxValue <= 100) {
                    // For values 51-100, use max 100 with step 20
                    yAxisMax = 100;
                    stepSize = 20;
                } else {
                    // For very high values (>100), round up to nearest 50 with step 25
                    yAxisMax = Math.ceil(maxValue / 50) * 50;
                    stepSize = Math.ceil(yAxisMax / 200) * 25;
                }
                
                // Create new chart with fetched data
                pertumbuhanChart = new Chart(pertumbuhanCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peserta Baru',
                            data: values,
                            backgroundColor: '#5D3FFF',
                            borderRadius: 8,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: yAxisMax,
                                ticks: {
                                    stepSize: stepSize,
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12
                                    },
                                    boxWidth: 8,
                                    boxHeight: 8
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y + ' peserta';
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading pertumbuhan data:', error);
                // Create empty chart on error
                if (pertumbuhanChart) {
                    pertumbuhanChart.destroy();
                }
                pertumbuhanChart = new Chart(pertumbuhanCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Peserta Baru',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            backgroundColor: '#5D3FFF',
                            borderRadius: 8,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 50,
                                ticks: {
                                    stepSize: 10,
                                    font: { size: 12 }
                                },
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 12 } }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: { size: 12 },
                                    boxWidth: 8,
                                    boxHeight: 8
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize
        populateYearDropdown();
        loadTransaksiData();
        loadPertumbuhanData();

        // Filter handlers
        document.getElementById('pertumbuhanFilter').addEventListener('change', function(e) {
            loadPertumbuhanData(e.target.value);
        });

        document.getElementById('transaksiFilter').addEventListener('change', function(e) {
            loadTransaksiData(e.target.value);
        });
    </script>
@endpush
