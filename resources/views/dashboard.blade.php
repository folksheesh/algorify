@extends('layouts.template')

@section('title', 'Algorify - Dashboard Pelatihan')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        /* Topbar Layout Adjustment */
        .dashboard-container.with-topbar {
            padding-top: 72px;
        }
        
        .dashboard-container.with-topbar .main-content {
            padding-top: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .dashboard-container.with-topbar .main-content {
                margin-left: 0;
            }
        }
        
        .course-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .course-image {
            transition: transform 0.3s ease;
        }
        .course-card:hover .course-image {
            transform: scale(1.05);
        }
        .bookmark-button {
            transition: all 0.3s ease;
        }
        .bookmark-button:hover {
            background: #3A6DFF;
            color: white;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .course-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            background: #EEF2FF;
            color: #3A6DFF;
            border-radius: 1rem;
            font-weight: 600;
        }
        
        /* Responsive adjustments untuk inline styles */
        @media (max-width: 768px) {
            .course-card:hover {
                transform: translateY(-4px);
            }
            
            .course-description-text {
                font-size: 0.8rem !important;
                -webkit-line-clamp: 2;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .course-price-footer {
                flex-direction: column !important;
                gap: 8px !important;
                align-items: flex-start !important;
            }
            
            .course-price-footer span:last-child {
                align-self: flex-end;
            }
        }
        
        @media (max-width: 480px) {
            .course-card:hover {
                transform: translateY(-2px);
            }
            
            .author-avatar {
                width: 28px !important;
                height: 28px !important;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')
    
    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <section class="hero" style="background: linear-gradient(135deg, #dfe9ff 0%, #4b74ff 100%); min-height: 50vh; display: flex; align-items: center; padding: 60px 80px; position: relative; margin: 20px 5% 32px 5%; border-radius: 24px; overflow: hidden;">
                <div class="hero-container" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; width: 100%; position: relative; z-index: 1;">
                    <div class="hero-left" style="color: #3a3fb5;">
                        <h1 style="font-size: 46px; line-height: 1.15; font-weight: 800; margin-bottom: 16px; color: #3a3fb5;">
                            Tingkatkan Skill-mu<br>Bareng Pelatihan Profesional
                        </h1>
                        <p style="font-size: 15px; opacity: 0.9; margin-bottom: 22px; line-height: 1.5; max-width: 440px; color: #2c2f6d;">
                            Berlangganan pelatihan lainnya untuk pengetahuan yang lebih luas.
                        </p>
                        <a href="{{ route('kursus.index') }}" style="display: inline-block; background: #3A6DFF; color: #fff; padding: 12px 22px; border-radius: 999px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 10px 24px rgba(58,109,255,0.35); transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 14px 26px rgba(58,109,255,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 24px rgba(58,109,255,0.35)'">
                            Daftar Sekarang!
                        </a>
                    </div>

                    <div style="position: relative; height: 360px; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('template/img/icon hero banner.jpg') }}" alt="Hero Banner" style="position: relative; width: 100%; height: 100%; object-fit: contain; animation: float-hero 6s ease-in-out infinite; z-index: 1;">
                    </div>
                </div>
            </section>
            
            <style>
                @keyframes float-hero {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-15px); }
                }
                
                @keyframes float-animation {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-20px); }
                }
                
                @keyframes pulse-glow {
                    0%, 100% { opacity: 0.6; }
                    50% { opacity: 1; }
                }
                
                @media (max-width: 992px) {
                    .hero-container {
                        grid-template-columns: 1fr !important;
                        gap: 32px !important;
                    }
                    
                    .hero-left h1 {
                        font-size: 40px !important;
                    }
                    
                    .hero-left {
                        text-align: center;
                    }
                    
                    .hero-left > div {
                        justify-content: center !important;
                    }
                }
                
                /* Hero Illustration Styles */
                .hero-illustration {
                    position: relative;
                    width: 100%;
                    max-width: 450px;
                    height: 350px;
                }
                
                .hero-main-illustration {
                    position: relative;
                    width: 100%;
                    border-radius: 20px;
                    z-index: 20;
                    object-fit: cover;
                    height: 320px;
                }
                
                .hero-icon-small {
                    position: absolute;
                    width: 120px;
                    height: 120px;
                    border-radius: 20px;
                    z-index: 30;
                    object-fit: cover;
                }
                
                .icon-top {
                    top: -30px;
                    right: -20px;
                }
                
                .icon-bottom {
                    bottom: -30px;
                    left: 20px;
                }
                
                @media (max-width: 900px) {
                    .hero-container {
                        grid-template-columns: 1fr !important;
                    }
                    
                    .hero-illustration {
                        margin-top: 30px;
                    }
                }
                
                @media (max-width: 600px) {
                    .hero-illustration {
                        max-width: 100%;
                        transform: scale(0.7);
                        transform-origin: center;
                    }
                    
                    .icon-top,
                    .icon-bottom {
                        display: none;
                    }
                }
                
                @media (max-width: 1024px) {
                    .hero-grid {
                        grid-template-columns: 1fr !important;
                        gap: 32px !important;
                    }
                    
                    .hero-banner-section {
                        padding: 40px 30px !important;
                    }
                    
                    .hero-content h1 {
                        font-size: 36px !important;
                    }
                }
                
                @media (max-width: 768px) {
                    .hero-banner-section {
                        padding: 30px 20px !important;
                    }
                    
                    .hero-content h1 {
                        font-size: 28px !important;
                    }
                    
                    .hero-content p {
                        font-size: 16px !important;
                    }
                    
                    .hero-illustration {
                        height: 350px !important;
                    }
                }
            </style>
            
            <section class="courses-section" style="margin-bottom: 2rem;">
            
            <style>
                .hero-banner-custom {
                    background: linear-gradient(180deg, #E0E7FF 0%, #3A6DFF 100%);
                    border-radius: 24px;
                    padding: 60px 80px;
                    margin-bottom: 32px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    position: relative;
                    overflow: hidden;
                    min-height: 420px;
                }
                
                .hero-bg-decoration {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                    overflow: hidden;
                    pointer-events: none;
                }
                
                .bg-gear {
                    position: absolute;
                    width: 120px;
                    height: 120px;
                    border: 15px solid rgba(99, 102, 241, 0.15);
                    border-radius: 50%;
                }
                
                .bg-gear::before {
                    content: '';
                    position: absolute;
                    width: 40px;
                    height: 40px;
                    background: rgba(99, 102, 241, 0.15);
                    top: -20px;
                    left: 50%;
                    transform: translateX(-50%);
                }
                
                .gear-1 {
                    top: 20px;
                    left: 20px;
                }
                
                .gear-2 {
                    top: 40px;
                    left: 140px;
                    width: 80px;
                    height: 80px;
                }
                
                .bg-circle {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                }
                
                .circle-1 {
                    width: 200px;
                    height: 200px;
                    top: -50px;
                    left: -50px;
                }
                
                .circle-2 {
                    width: 150px;
                    height: 150px;
                    bottom: -30px;
                    left: 100px;
                }
                
                .hero-left {
                    flex: 1;
                    z-index: 2;
                    max-width: 500px;
                }
                
                .hero-title-new {
                    font-size: 52px;
                    font-weight: 800;
                    color: #3B42F6;
                    margin: 0 0 20px 0;
                    line-height: 1.1;
                    letter-spacing: -1px;
                }
                
                .hero-desc-new {
                    font-size: 16px;
                    color: #1E293B;
                    margin: 0 0 32px 0;
                    line-height: 1.6;
                }
                
                .hero-btn {
                    background: #5B5FF9;
                    color: #fff;
                    border: none;
                    padding: 14px 32px;
                    border-radius: 50px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 16px rgba(91, 95, 249, 0.4);
                }
                
                .hero-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 24px rgba(91, 95, 249, 0.5);
                    background: #4A4EE8;
                }
                
                .hero-right {
                    flex: 1;
                    display: flex;
                    justify-content: flex-end;
                    position: relative;
                    max-width: 600px;
                }
                
                .illustration-dots {
                    position: absolute;
                    width: 80px;
                    height: 50px;
                    background-image: radial-gradient(circle, #3B42F6 2px, transparent 2px);
                    background-size: 10px 10px;
                    z-index: 1;
                }
                
                .dots-top {
                    top: 20px;
                    right: 60px;
                }
                
                .dots-bottom {
                    bottom: 40px;
                    left: 40px;
                }
                
                .hero-scene {
                    position: relative;
                    width: 550px;
                    height: 380px;
                }
                
                /* Person on top fishing */
                .person-top {
                    position: absolute;
                    top: -10px;
                    right: 80px;
                    z-index: 5;
                }
                
                .person-top .person-head {
                    width: 28px;
                    height: 28px;
                    background: #1E293B;
                    border-radius: 50%;
                    margin-bottom: 5px;
                }
                
                .person-top .person-body {
                    width: 50px;
                    height: 60px;
                    background: #5B5FF9;
                    border-radius: 25px 25px 10px 10px;
                    position: relative;
                }
                
                .fishing-rod {
                    position: absolute;
                    width: 2px;
                    height: 120px;
                    background: #64748B;
                    top: 30px;
                    right: -5px;
                    transform: rotate(25deg);
                    transform-origin: top;
                }
                
                .fishing-rod::after {
                    content: '';
                    position: absolute;
                    width: 12px;
                    height: 12px;
                    background: #94A3B8;
                    border-radius: 50%;
                    bottom: -6px;
                    right: -5px;
                }
                
                /* Main browser window */
                .main-browser {
                    position: absolute;
                    width: 450px;
                    height: 280px;
                    background: #fff;
                    border-radius: 16px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    overflow: hidden;
                    z-index: 3;
                }
                
                .browser-top {
                    background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
                    padding: 12px 16px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    border-bottom: 1px solid #E2E8F0;
                }
                
                .browser-controls .dot {
                    width: 10px;
                    height: 10px;
                    background: #CBD5E1;
                    border-radius: 50%;
                    display: inline-block;
                }
                
                .browser-searchbar {
                    flex: 1;
                    height: 32px;
                    background: #fff;
                    border-radius: 16px;
                    border: 1px solid #E2E8F0;
                }
                
                .browser-menu {
                    display: flex;
                    flex-direction: column;
                    gap: 3px;
                }
                
                .browser-menu span {
                    width: 24px;
                    height: 3px;
                    background: #5B5FF9;
                    border-radius: 2px;
                }
                
                .browser-body {
                    display: flex;
                    height: calc(100% - 56px);
                }
                
                .browser-sidebar {
                    width: 80px;
                    background: #F8FAFC;
                    padding: 16px 12px;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }
                
                .sidebar-item {
                    width: 100%;
                    height: 50px;
                    background: #E2E8F0;
                    border-radius: 8px;
                }
                
                .sidebar-item.active {
                    background: #5B5FF9;
                }
                
                .browser-main {
                    flex: 1;
                    padding: 20px;
                }
                
                .content-checks {
                    display: flex;
                    gap: 12px;
                    margin-bottom: 16px;
                }
                
                .check-box {
                    width: 28px;
                    height: 28px;
                    background: #5B5FF9;
                    border-radius: 6px;
                    position: relative;
                }
                
                .check-box::after {
                    content: '✓';
                    color: #fff;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 14px;
                    font-weight: bold;
                }
                
                .content-bars {
                    margin-bottom: 16px;
                }
                
                .bar-item {
                    height: 16px;
                    background: linear-gradient(90deg, #3A6DFF 0%, #3A6DFF 100%);
                    border-radius: 8px;
                    width: 75%;
                    margin-bottom: 8px;
                }
                
                .content-image {
                    width: 100%;
                    height: 100px;
                    background: linear-gradient(135deg, #5B5FF9 0%, #8B92F7 100%);
                    border-radius: 12px;
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }
                
                .image-icon {
                    width: 50px;
                    height: 50px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                }
                
                .image-shape {
                    position: absolute;
                    bottom: 10px;
                    right: 20px;
                    width: 0;
                    height: 0;
                    border-left: 30px solid transparent;
                    border-right: 30px solid transparent;
                    border-bottom: 50px solid rgba(255, 255, 255, 0.4);
                }
                
                /* People on sides */
                .person-left, .person-right {
                    position: absolute;
                    z-index: 4;
                }
                
                .person-left {
                    left: -20px;
                    top: 40%;
                }
                
                .person-right {
                    right: -20px;
                    bottom: 20%;
                }
                
                .person-left .person-head,
                .person-right .person-head {
                    width: 32px;
                    height: 32px;
                    background: #1E293B;
                    border-radius: 50%;
                    margin: 0 auto 6px;
                }
                
                .person-left .person-body,
                .person-right .person-body {
                    width: 60px;
                    height: 80px;
                    background: #1E293B;
                    border-radius: 30px 30px 10px 10px;
                }
                
                /* Plants */
                .plant {
                    position: absolute;
                    z-index: 2;
                }
                
                .plant-left {
                    left: 50px;
                    bottom: 10px;
                }
                
                .plant-right {
                    right: 50px;
                    bottom: 10px;
                }
                
                .plant .leaf {
                    width: 35px;
                    height: 50px;
                    background: #4F77E8;
                    border-radius: 50% 50% 0 0;
                    position: absolute;
                    bottom: 20px;
                }
                
                .plant .leaf:first-child {
                    left: 0;
                }
                
                .plant .leaf:nth-child(2) {
                    right: 0;
                    transform: scaleX(-1);
                }
                
                .plant .pot {
                    width: 50px;
                    height: 25px;
                    background: #F97316;
                    border-radius: 0 0 25px 25px;
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    transform: translateX(-50%);
                }
                
                /* Code terminal */
                .code-terminal {
                    position: absolute;
                    width: 160px;
                    height: 90px;
                    background: #5B5FF9;
                    border-radius: 12px;
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 5;
                    box-shadow: 0 8px 24px rgba(91, 95, 249, 0.4);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 12px;
                    padding: 16px;
                }
                
                .terminal-icon {
                    width: 40px;
                    height: 40px;
                    background: #1E293B;
                    border-radius: 8px;
                }
                
                .terminal-lines {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }
                
                .terminal-lines::before,
                .terminal-lines::after {
                    content: '';
                    height: 4px;
                    background: rgba(255, 255, 255, 0.8);
                    border-radius: 2px;
                }
                
                .terminal-lines::before {
                    width: 100%;
                }
                
                .terminal-lines::after {
                    width: 70%;
                }
                
                /* Shadow */
                .scene-shadow {
                    position: absolute;
                    bottom: -10px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 80%;
                    height: 30px;
                    background: radial-gradient(ellipse, rgba(0, 0, 0, 0.15) 0%, transparent 70%);
                    z-index: 1;
                }
                
                @media (max-width: 1200px) {
                    .hero-banner-custom {
                        padding: 50px 40px;
                    }
                    
                    .hero-title-new {
                        font-size: 42px;
                    }
                    
                    .hero-scene {
                        width: 450px;
                        height: 320px;
                    }
                    
                    .main-browser {
                        width: 380px;
                        height: 240px;
                    }
                }
                
                @media (max-width: 1024px) {
                    .hero-banner-custom {
                        flex-direction: column;
                        padding: 40px 30px;
                        text-align: center;
                    }
                    
                    .hero-left {
                        max-width: 100%;
                        margin-bottom: 40px;
                    }
                    
                    .hero-title-new {
                        font-size: 36px;
                    }
                    
                    .hero-right {
                        justify-content: center;
                        max-width: 100%;
                    }
                    
                    .hero-scene {
                        width: 400px;
                        height: 280px;
                    }
                    
                    .main-browser {
                        width: 340px;
                        height: 220px;
                    }
                }
                
                @media (max-width: 768px) {
                    .hero-banner-custom {
                        padding: 30px 20px;
                    }
                    
                    .hero-title-new {
                        font-size: 28px;
                    }
                    
                    .hero-desc-new {
                        font-size: 14px;
                    }
                    
                    .hero-scene {
                        width: 320px;
                        height: 240px;
                    }
                    
                    .main-browser {
                        width: 280px;
                        height: 180px;
                    }
                    
                    .person-top,
                    .person-left,
                    .person-right {
                        display: none;
                    }
                    
                    .plant {
                        transform: scale(0.8);
                    }
                }
            </style>
            
            <section class="courses-section" style="margin-bottom: 2rem;">
                <header class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <h2 class="section-title" style="margin: 0;">Lanjutkan Belajar</h2>
                        <a href="{{ route('user.pelatihan-saya.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                            Lihat Semua 
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                                <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                    <div class="carousel-nav" style="display: flex; gap: 0.5rem;">
                        <button onclick="scrollCarousel(-1)" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid #E2E8F0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                                <path d="M13 16L7 10L13 4" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button onclick="scrollCarousel(1)" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid #E2E8F0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                                <path d="M7 4L13 10L7 16" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </header>
                <div id="continueCarousel" class="continue-carousel-wrapper">
                    @forelse($enrollments as $enrollment)
                        @if($enrollment->kursus)
                        <a href="{{ route('kursus.show', $enrollment->kursus_id) }}" class="continue-card">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                                <div style="background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%); color: #fff; width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 6L17 12L9 18V6Z" fill="currentColor" />
                                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h3 style="font-size: 1rem; color: #1E293B; margin: 0; font-weight: 600; line-height: 1.4;">{{ Str::limit($enrollment->kursus->judul, 40) }}</h3>
                                    <p style="font-size: 0.8125rem; color: #64748B; margin: 0.25rem 0 0 0;">{{ $enrollment->kursus->kategori ?? 'Pelatihan' }}</p>
                                </div>
                            </div>
                            <div style="margin-top: auto;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.8125rem; color: #64748B;">Progress</span>
                                    <span style="font-size: 0.875rem; color: #3A6DFF; font-weight: 600;">{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                                <div style="width: 100%; height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $enrollment->progress ?? 0 }}%; height: 100%; background: linear-gradient(90deg, #3A6DFF 0%, #3A6DFF 100%); border-radius: 4px; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        </a>
                        @endif
                    @empty
                    <div style="grid-column: 1 / -1; background: #fff; border-radius: 16px; padding: 3rem; text-align: center; border: 1px solid #E2E8F0;">
                        <div style="width: 64px; height: 64px; background: #EEF2FF; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <p style="font-size: 0.9375rem; color: #64748B; margin: 0 0 0.75rem 0;">Belum ada pelatihan yang diikuti</p>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                            Jelajahi Pelatihan →
                        </a>
                    </div>
                    @endforelse
                    {{-- Spacer untuk card terakhir tidak terpotong --}}
                    <div style="flex-shrink: 0; width: 1px; height: 1px;"></div>
                </div>
            </section>
            
            <style>
                .continue-carousel-wrapper {
                    display: flex;
                    gap: 1.5rem;
                    overflow-x: auto;
                    scroll-behavior: smooth;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    padding: 0.5rem 0;
                    margin-right: -2rem;
                    padding-right: 2rem;
                }
                .continue-carousel-wrapper::-webkit-scrollbar { display: none; }
                .continue-carousel-wrapper .continue-card {
                    flex: 0 0 calc((100% - 3rem) / 3);
                    min-width: 280px;
                    max-width: 380px;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    display: flex;
                    flex-direction: column;
                    padding: 1.5rem;
                    background: #fff;
                    border-radius: 16px;
                    border: 1px solid #E2E8F0;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                }
                .continue-card:hover { 
                    transform: translateY(-4px); 
                    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.15); 
                    border-color: #6366F1;
                }
                .carousel-nav button:hover { border-color: #6366F1; background: #EEF2FF; }
                .carousel-nav button:hover svg path { stroke: #6366F1; }
                @media (max-width: 1024px) {
                    .continue-carousel-wrapper .continue-card { flex: 0 0 calc((100% - 1.5rem) / 2); min-width: 260px; }
                }
                @media (max-width: 640px) {
                    .continue-carousel-wrapper .continue-card { flex: 0 0 85%; min-width: unset; max-width: unset; }
                }
                
                /* Fix hero banner overflow */
                .hero-banner {
                    overflow: visible !important;
                }
                .main-content {
                    overflow-x: hidden;
                }
            </style>
            
            <script>
                function scrollCarousel(direction) {
                    const carousel = document.getElementById('continueCarousel');
                    const cardWidth = carousel.querySelector('.continue-card')?.offsetWidth || 300;
                    const gap = 24;
                    carousel.scrollBy({ left: direction * (cardWidth + gap), behavior: 'smooth' });
                }
            </script>

            <section class="courses-section">
                <header class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <h2 class="section-title" style="margin: 0;">Rekomendasi Pelatihan</h2>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                            Lihat Semua 
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                                <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </header>
                <div class="courses-grid">
                    @forelse($recommendedCourses as $kursus)
                    <article class="course-card">
                        <div class="course-thumbnail">
                            @if($kursus->gambar)
                                <img src="{{ asset('storage/' . $kursus->gambar) }}" alt="{{ $kursus->judul }}" class="course-image" />
                            @else
                                <img src="{{ asset('template/assets/compiled/jpg/' . (($loop->index % 3) + 2) . '.jpg') }}" alt="{{ $kursus->judul }}" class="course-image" />
                            @endif
                            <a href="{{ route('kursus.show', $kursus->id) }}" class="bookmark-button" aria-label="Lihat detail">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <div class="course-content">
                            <span class="course-badge">{{ strtoupper($kursus->kategori ?? 'PELATIHAN') }}</span>
                            <h3 class="course-title">{{ Str::limit($kursus->judul, 50) }}</h3>
                            <p class="course-description-text" style="font-size: 0.875rem; color: #64748B; margin: 0.5rem 0; line-height: 1.5;">
                                {{ Str::limit($kursus->deskripsi_singkat ?? $kursus->deskripsi, 80) }}
                            </p>
                            @if($kursus->pengajar)
                            <div class="course-author">
                                @if($kursus->pengajar->profile_photo)
                                    <img src="{{ asset('storage/' . $kursus->pengajar->profile_photo) }}" alt="{{ $kursus->pengajar->name }}" class="author-avatar" />
                                @else
                                    <div class="author-avatar" style="background: #6366F1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ substr($kursus->pengajar->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="author-info">
                                    <p class="author-name">{{ $kursus->pengajar->name }}</p>
                                    <p class="author-role">Pengajar</p>
                                </div>
                            </div>
                            @endif
                            <div class="course-price-footer" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.875rem; color: #64748B;">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    {{ $kursus->enrollments_count ?? 0 }} Peserta
                                </span>
                                <span style="font-size: 1rem; font-weight: 700; color: #6366F1;">
                                    Rp {{ number_format($kursus->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #64748B;">
                        <p style="font-size: 1.125rem; margin-bottom: 1rem;">Belum ada pelatihan tersedia</p>
                        <a href="{{ route('kursus.index') }}" style="color: #6366F1; text-decoration: none; font-weight: 600;">
                            Jelajahi Pelatihan →
                        </a>
                    </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

@push('scripts')
    <script>
        // Force light theme
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
