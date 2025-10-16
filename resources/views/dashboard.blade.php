@extends('layouts.template')

@section('title', 'Dashboard Peserta - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <script src="{{ asset('template/assets/static/js/initTheme.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
@endpush

@section('content')
    <div class="dash-wrapper">
        <!-- Sidebar -->
        <aside class="dash-sidebar">
            <div class="dash-brand">
                <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height:28px">
            </div>
            <div class="dash-section-title">Menu</div>
            <ul class="dash-menu">
                <li><a class="dash-link active" href="#"><i class="bi bi-house"></i> <span>Halaman Utama</span></a></li>
                <li><a class="dash-link" href="#"><i class="bi bi-play-circle"></i> <span>Pelatihan Saya</span></a></li>
                <li><a class="dash-link" href="#"><i class="bi bi-compass"></i> <span>Jelajahi Pelatihan</span></a></li>
                <li><a class="dash-link" href="#"><i class="bi bi-award"></i> <span>Dapatkan Sertifikat</span></a></li>
            </ul>
        </aside>

        <!-- Main -->
        <main class="dash-main">
            <div class="dash-content">
                <div class="dash-topbar">
                    <div class="dash-search">
                        <input type="text" placeholder="Apa yang ingin anda pelajari?">
                        <i class="bi bi-search"></i>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>

                <section class="dash-hero">
                    <div>
                        <h3>Tingkatkan Skill-mu<br>Bareng Pelatihan Profesional</h3>
                        <p>Berlangganan pelatihan lainnya untuk pengetahuan yang lebih luas.</p>
                        <div class="dash-cats">
                            <div class="dash-cat"><div class="muted">2/8 ditonton</div><div class="title">Software Dev</div></div>
                            <div class="dash-cat"><div class="muted">2/8 Ditonton</div><div class="title">Frontend</div></div>
                            <div class="dash-cat"><div class="muted">2/8 Ditonton</div><div class="title">BackEnd</div></div>
                        </div>
                    </div>
                    <div class="dash-hero-illus"></div>
                </section>

                <section class="dash-section">
                    <h5>Lanjutkan Menonton</h5>
                    <div class="course-grid mt-3">
                        @foreach (range(1,3) as $i)
                        <article class="course-card">
                            <div class="course-thumb">
                                <img src="{{ asset('template/assets/compiled/jpg/' . ($i+1) . '.jpg') }}" alt="Course">
                            </div>
                            <div class="course-body">
                                <span class="badge-track">FRONTEND</span>
                                <h6 class="course-title">Peran & Tugas Frontend Developer</h6>
                                <div class="course-progress"><div class="bar" style="width: {{ 30 + $i * 10 }}%"></div></div>
                                <div class="course-meta">
                                    <div class="avatar"><img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}" alt="mentor"></div>
                                    <div>
                                        <div style="font-weight:700; font-size:12px">Prashant Kumar Singh</div>
                                        <div style="font-size:12px; color:#64748B">Software Developer</div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
@endpush
