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
            <li class="nav-item">
                <a href="#" class="nav-link">
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
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                        <path d="M8 4V2M12 4V2M4 8H16" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <span class="nav-text">Dapatkan Sertifikat</span>
                </a>
            </li>
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
