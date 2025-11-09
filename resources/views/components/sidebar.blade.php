<aside class="sidebar">
    <header class="sidebar-header">
        <div class="logo">
            <svg class="logo-icon" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="12" height="12" fill="#5D3FFF" />
                <rect x="14" width="18" height="12" fill="#5D3FFF" />
                <rect y="14" width="12" height="18" fill="#5D3FFF" />
            </svg>
            <span class="logo-text">Algorify</span>
        </div>
    </header>
    <div class="user-profile">
        <div class="user-avatar">
            <svg class="avatar-icon" width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="30" fill="#F1F5F9" />
                <circle cx="30" cy="24" r="10" fill="#1E293B" />
                <path d="M10 50C10 40 18 34 30 34C42 34 50 40 50 50" fill="#1E293B" />
            </svg>
        </div>
        <div class="user-greeting">
            <h2 class="greeting-title">Selamat Pagi {{ Auth::user()->name }}</h2>
            <p class="greeting-subtitle">Lanjutkan Perjalanan Anda<br />Dan Capai Target Anda</p>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav-list">
            @if(Auth::user()->hasAnyRole(['admin', 'super admin']))
                <!-- Admin Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M3 8H17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.peserta.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.peserta.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Peserta</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pengajar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengajar.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Pengajar</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pelatihan.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.pelatihan.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Pelatihan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.transaksi.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.transaksi.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 10h18M3 10a2 2 0 01-2-2V6a2 2 0 012-2h18a2 2 0 012 2v2a2 2 0 01-2 2M3 10a2 2 0 00-2 2v2a2 2 0 002 2h18a2 2 0 002-2v-2a2 2 0 00-2-2" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Transaksi</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.analitik.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.analitik.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Analitik</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.sertifikat.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M8 4V2M12 4V2M4 8H16" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Sertifikat</span>
                    </a>
                </li>
            @else
                <!-- Regular User Menu Items -->
                <li class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L3 8V17H7V12H13V17H17V8L10 3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Pengaturan Akun</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M3 8H17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.pelatihan-saya.index') ? 'active' : '' }}">
                    <a href="{{ route('user.pelatihan-saya.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="3" width="12" height="14" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M7 7H13M7 10H13M7 13H10" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Pelatihan Saya</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('kursus.index') ? 'active' : '' }}">
                    <a href="{{ route('kursus.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L12 8H17L13 11L14.5 16L10 13L5.5 16L7 11L3 8H8L10 3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Jelajahi Pelatihan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('user.sertifikat.index') }}" class="nav-link">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M8 4V2M12 4V2M4 8H16" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Dapatkan Sertifikat</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                <svg class="logout-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 14L17 10L13 6M17 10H7M7 3H5C3.89543 3 3 3.89543 3 5V15C3 16.1046 3.89543 17 5 17H7" stroke="currentColor" stroke-width="1.5" fill="none" />
                </svg>
                <span class="logout-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>
