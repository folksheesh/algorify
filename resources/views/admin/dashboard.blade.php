@extends('layouts.template')

@section('title', 'Algorify - Admin Dashboard')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .stat-info p {
            font-size: 2rem;
            font-weight: 700;
            color: #1E293B;
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
        @media (max-width: 1024px) {
            .stat-cards-grid {
                grid-template-columns: 1fr;
            }
            .charts-grid {
                grid-template-columns: 1fr;
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
            <div class="page-header" style="margin: 0 2rem;">
                <h1>Dashboard Admin</h1>
            </div>
            
            <div style="padding: 0 2rem 2rem;">
                <!-- Admin Header Banner -->
                <div class="admin-header">
                    <h1>Halo, Admin!</h1>
                    <p>"Selamat datang di halaman Admin. Kelola peserta, pengajar, course, dan transaksi dengan mudah untuk mendukung jalannya pelatihan TIK."</p>
                </div>

                <!-- Stat Cards -->
                <div class="stat-cards-grid">
                    <a href="{{ route('admin.peserta.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
                            <h3 class="chart-title">Transaksi</h3>
                            <select class="chart-dropdown" id="transaksiFilter">
                                <option value="current_month">Bulan Ini</option>
                                <option value="last_month">Bulan Lalu</option>
                                <option value="all">Keseluruhan</option>
                            </select>
                        </div>
                        <div class="chart-content" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="transaksiChart"></canvas>
                        </div>
                    </div>

                    <!-- Pertumbuhan Peserta Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Pertumbuhan Peserta</h3>
                            <select class="chart-dropdown" id="pertumbuhanFilter">
                                <option value="this_year">Tahun Ini</option>
                                <option value="all">Keseluruhan</option>
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

        // Function to load transaksi data
        async function loadTransaksiData(filter = 'current_month') {
            try {
                const response = await fetch(`{{ route('admin.dashboard.transaksi-data') }}?filter=${filter}`);
                const data = await response.json();
                
                // Destroy existing chart if it exists
                if (transaksiChart) {
                    transaksiChart.destroy();
                }
                
                // Create new chart with fetched data
                transaksiChart = new Chart(transaksiCtx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels.length > 0 ? data.labels : ['Tidak ada data'],
                        datasets: [{
                            data: data.values.length > 0 ? data.values : [1],
                            backgroundColor: data.values.length > 0 ? [
                                '#667eea',
                                '#764ba2',
                                '#f093fb',
                                '#4facfe',
                                '#00f2fe'
                            ] : ['#E2E8F0'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 13
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading transaksi data:', error);
            }
        }

        // Pertumbuhan Peserta Line Chart (Monthly)
        const pertumbuhanCtx = document.getElementById('pertumbuhanChart').getContext('2d');
        let pertumbuhanChart = null;

        // Function to load pertumbuhan data
        async function loadPertumbuhanData(filter = 'this_year') {
            try {
                const response = await fetch(`{{ route('admin.dashboard.pertumbuhan-data') }}?filter=${filter}`);
                const data = await response.json();
                
                console.log('Pertumbuhan data:', data); // Debug
                
                // Destroy existing chart if it exists
                if (pertumbuhanChart) {
                    pertumbuhanChart.destroy();
                }
                
                // Ensure we have data to display
                const labels = data.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const values = data.values || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                
                // Create new chart with fetched data
                pertumbuhanChart = new Chart(pertumbuhanCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peserta Baru',
                            data: values,
                            backgroundColor: '#667eea',
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
                                display: true,
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
                            backgroundColor: '#667eea',
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

        // Load initial data
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
