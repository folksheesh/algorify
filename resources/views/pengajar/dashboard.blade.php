@extends('layouts.template')

@section('title', 'Dashboard Pengajar - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
      <link rel="stylesheet" href="{{ asset('css/pengajar/dashboard.css') }}">

@endpush

@section('content')
    {{-- Topbar Pengajar --}}
    @include('components.topbar-pengajar')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header dashboard-page-header">
                <h1>Dashboard</h1>
            </div>
            
            <div class="dashboard-content-wrapper">
                <!-- Admin Header Banner -->
                <div class="admin-header">
                    <h1>Selamat datang kembali!</h1>
                    <p>Berikut ringkasan aktivitas Anda. Kelola kursus dan pantau perkembangan siswa dengan mudah.</p>
                </div>

                <!-- Stat Cards (Kursus & Siswa) -->
                <div class="stat-cards-grid">
                    <a href="{{ route('admin.pelatihan.index') }}" style="text-decoration: none;">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #5D3FFF 0%, #5D3FFF 100%);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" fill="white"/>
                                    <path d="M8 10H16M8 14H12" stroke="#5D3FFF" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="stat-info">
                                <h3>Total Kursus</h3>
                                <p>{{ $totalKursus }}</p>
                            </div>
                        </div>
                    </a>
                    <div class="stat-card-modern">
                        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="9" cy="7" r="4" stroke="white" stroke-width="1.5"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Total Siswa</h3>
                            <p>{{ $totalSiswa }}</p>
                        </div>
                    </div>
                </div>
                <!-- END Stat Cards -->
            </div>
        </main>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection
