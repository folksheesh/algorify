<aside class="sidebar" id="sidebar">
    <header class="sidebar-header">
        <a href="javascript:location.reload();" class="logo" title="Refresh halaman">
            <img src="{{ asset('template/img/icon-logo.png') }}" alt="Algorify Logo" class="logo-icon">
            <span class="logo-text">Algorify</span>
        </a>
        <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
            <svg class="toggle-icon collapse-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg class="toggle-icon expand-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </header>
    <nav class="sidebar-nav">
        <ul class="nav-list">
            @if(Auth::user()->hasAnyRole(['admin', 'super admin']))
                <!-- Admin Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-title="Halaman Utama">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M3 8H17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                @if(Auth::user()->hasRole('super admin'))
                <li class="nav-item {{ request()->routeIs('admin.admin.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.admin.index') }}" class="nav-link" data-title="Data Admin">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Admin</span>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ request()->routeIs('admin.peserta.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.peserta.index') }}" class="nav-link" data-title="Data Peserta">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Peserta</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pengajar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengajar.index') }}" class="nav-link" data-title="Data Pengajar">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Data Pengajar</span>
                    </a>
                </li>
                <li class="nav-item has-submenu {{ request()->routeIs('admin.pelatihan.*') || request()->routeIs('admin.bank-soal.*') || request()->routeIs('admin.kategori.*') ? 'open' : '' }}">
                    <a href="#" class="nav-link" data-title="Data Pelatihan" onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M8 10H16M8 14H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span class="nav-text">Data Pelatihan</span>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item {{ request()->routeIs('admin.pelatihan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pelatihan.index') }}" class="nav-link" data-title="Data Kursus">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <path d="M8 10H16M8 14H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span class="nav-text">Data Kursus</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.bank-soal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.bank-soal.index') }}" class="nav-link" data-title="Bank Soal">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span class="nav-text">Bank Soal</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kategori.index') }}" class="nav-link" data-title="Data Kategori">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span class="nav-text">Data Kategori</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.transaksi.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.transaksi.index') }}" class="nav-link" data-title="Transaksi">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 10h18M3 10a2 2 0 01-2-2V6a2 2 0 012-2h18a2 2 0 012 2v2a2 2 0 01-2 2M3 10a2 2 0 00-2 2v2a2 2 0 002 2h18a2 2 0 002-2v-2a2 2 0 00-2-2" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Transaksi</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.analitik.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.analitik.index') }}" class="nav-link" data-title="Analitik">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Analitik</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.sertifikat.index') }}" class="nav-link" data-title="Sertifikat">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M8 4V2M12 4V2M4 8H16" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Sertifikat</span>
                    </a>
                </li>
            @elseif(Auth::user()->hasRole('pengajar'))
                <!-- Pengajar Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-title="Halaman Utama">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M3 8H17" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li class="nav-item has-submenu {{ request()->routeIs('admin.pelatihan.*') || request()->routeIs('admin.bank-soal.*') ? 'open' : '' }}">
                    <a href="#" class="nav-link" data-title="Data Pelatihan" onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M8 10H16M8 14H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span class="nav-text">Data Pelatihan</span>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item {{ request()->routeIs('admin.pelatihan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pelatihan.index') }}" class="nav-link" data-title="Data Kursus">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <path d="M8 10H16M8 14H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span class="nav-text">Data Kursus</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.bank-soal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.bank-soal.index') }}" class="nav-link" data-title="Bank Soal">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span class="nav-text">Bank Soal</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @else
                <!-- Regular User Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-title="Halaman Utama">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L3 8V17H7V12H13V17H17V8L10 3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.pelatihan-saya.index') || request()->routeIs('admin.pelatihan.show') || request()->routeIs('admin.video.show') || request()->routeIs('admin.materi.show') || request()->routeIs('admin.ujian.show') || request()->routeIs('user.ujian.result') ? 'active' : '' }}">
                    <a href="{{ route('user.pelatihan-saya.index') }}" class="nav-link" data-title="Pelatihan Saya">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="3" width="12" height="14" rx="1" stroke="currentColor" stroke-width="1.5" fill="none" />
                            <path d="M7 7H13M7 10H13M7 13H10" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Pelatihan Saya</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('kursus.index') ? 'active' : '' }}">
                    <a href="{{ route('kursus.index') }}" class="nav-link" data-title="Jelajahi Pelatihan">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L12 8H17L13 11L14.5 16L10 13L5.5 16L7 11L3 8H8L10 3Z" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Jelajahi Pelatihan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('user.sertifikat.index') }}" class="nav-link" data-title="Dapatkan Sertifikat">
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
            <button class="logout-button" type="submit" data-title="Keluar">
                <svg class="logout-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 14L17 10L13 6M17 10H7M7 3H5C3.89543 3 3 3.89543 3 5V15C3 16.1046 3.89543 17 5 17H7" stroke="currentColor" stroke-width="1.5" fill="none" />
                </svg>
                <span class="logout-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Toggle Button - positioned at sidebar edge -->
<button class="sidebar-edge-toggle" id="sidebarEdgeToggle" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
    <svg class="edge-collapse-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <svg class="edge-expand-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<style>
/* Active menu item background color */
.nav-item.active > .nav-link {
    background: #3A6DFF;
    color: #fff;
    font-weight: 600;
    border-radius: 12px;
}
/* ============================================
   SIDEBAR COLLAPSIBLE - Gmail Style
   ============================================ */

/* Hide original toggle button in header */
.sidebar-toggle-btn {
    display: none !important;
}

/* Edge Toggle Button - positioned at sidebar edge */
.sidebar-edge-toggle {
    position: fixed;
    left: 268px; /* sidebar width (280px) - half button width */
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 48px;
    background: #3A6DFF;
    color: white;
    border: none;
    border-radius: 0 8px 8px 0;
    cursor: pointer;
    z-index: 1001;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 2px 0 8px rgba(58, 109, 255, 0.3);
    transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.2s, transform 0.2s;
}

.sidebar-edge-toggle:hover {
    background: #2952CC;
    transform: translateY(-50%) scale(1.05);
}

.sidebar-edge-toggle:active {
    transform: translateY(-50%) scale(0.95);
}

/* Move toggle button when sidebar is collapsed */
.sidebar.collapsed ~ .sidebar-edge-toggle {
    left: 60px; /* collapsed width (72px) - half button width */
}

/* Toggle icon visibility based on sidebar state */
.sidebar.collapsed ~ .sidebar-edge-toggle .edge-collapse-icon {
    display: none !important;
}

.sidebar.collapsed ~ .sidebar-edge-toggle .edge-expand-icon {
    display: block !important;
}

.sidebar:not(.collapsed) ~ .sidebar-edge-toggle .edge-collapse-icon {
    display: block !important;
}

.sidebar:not(.collapsed) ~ .sidebar-edge-toggle .edge-expand-icon {
    display: none !important;
}

.sidebar-toggle-btn:hover {
    background: #3A6DFF;
    border-color: #3A6DFF;
    color: #FFFFFF;
}

.sidebar-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 16px !important;
    margin-bottom: 8px !important;
    position: relative;
}

/* Transition for sidebar */
.sidebar {
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Transition for sidebar elements */
.sidebar .logo-text,
.sidebar .nav-text,
.sidebar .logout-text {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.sidebar .nav-link,
.sidebar .logout-button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar .nav-icon {
    transition: transform 0.2s ease;
}

.sidebar .logo {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: inherit;
}

.sidebar .logo:hover {
    opacity: 0.8;
    transform: scale(1.02);
}

.sidebar .sidebar-header {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar .sidebar-nav {
    transition: padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar .sidebar-footer {
    transition: padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Collapsed State Styles */
.sidebar.collapsed {
    width: 72px !important;
    overflow: visible !important;
}

/* Hide scrollbar when collapsed */
.sidebar.collapsed .sidebar-nav {
    overflow: hidden !important;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.sidebar.collapsed .sidebar-nav::-webkit-scrollbar {
    display: none;
}

/* Fade out text with animation */
.sidebar.collapsed .logo-text,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .logout-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
    white-space: nowrap;
    transform: translateX(-10px);
}

/* Scale up icons slightly when collapsed */
.sidebar.collapsed .nav-icon {
    transform: scale(1.1);
}

.sidebar.collapsed .sidebar-header {
    justify-content: center !important;
    padding: 12px 8px !important;
    flex-direction: column;
    gap: 12px;
    align-items: center !important;
}

.sidebar.collapsed .sidebar-header .logo {
    margin-right: 0;
}

.sidebar.collapsed .logo {
    justify-content: center;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 12px !important;
}

.sidebar.collapsed .nav-icon {
    margin: 0;
}

.sidebar.collapsed .logout-button {
    justify-content: center;
    padding: 12px !important;
}

.sidebar.collapsed .sidebar-nav {
    padding: 0 8px;
}

.sidebar.collapsed .sidebar-footer {
    padding: 12px 8px;
}

/* Hide submenu arrow in collapsed state */
.sidebar.collapsed .nav-item.has-submenu > .nav-link::after {
    display: none;
}

/* Submenu in collapsed state - show as tooltip/popup */
.sidebar.collapsed .submenu {
    display: none !important;
}

/* Adjust main content when sidebar is collapsed */
.sidebar.collapsed ~ .main-content,
body:has(.sidebar.collapsed) .main-content {
    margin-left: 72px !important;
}

/* Toggle icon visibility */
.sidebar.collapsed .collapse-icon {
    display: none !important;
}

.sidebar.collapsed .expand-icon {
    display: block !important;
}

/* Tooltip for collapsed sidebar */
.sidebar.collapsed .nav-item {
    position: relative;
}

.sidebar.collapsed .nav-link::after,
.sidebar.collapsed .logout-button::after {
    content: attr(data-title);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%) translateX(-5px);
    background: #1E293B;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-left: 12px;
    z-index: 1000;
    pointer-events: none;
}

.sidebar.collapsed .nav-link:hover::after,
.sidebar.collapsed .logout-button:hover::after {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(0);
}

/* Logo icon only in collapsed */
.sidebar.collapsed .logo-icon {
    width: 36px;
    height: 36px;
}

/* Sidebar footer position relative for tooltip */
.sidebar.collapsed .sidebar-footer {
    position: relative;
}

.sidebar.collapsed .sidebar-footer form {
    position: relative;
}

/* Mobile styles */
@media (max-width: 992px) {
    .sidebar-toggle-btn,
    .sidebar-edge-toggle {
        display: none !important;
    }
    
    .sidebar.collapsed {
        width: 280px !important;
        transform: translateX(-100%);
    }
    
    .sidebar.collapsed.active {
        transform: translateX(0);
    }
    
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .logout-text {
        display: block !important;
    }
    
    .sidebar.collapsed .nav-link {
        justify-content: flex-start;
        padding: 12px 16px !important;
    }
    
    .sidebar.collapsed .logout-button {
        justify-content: flex-start;
        padding: 12px 16px !important;
    }
    
    .sidebar.collapsed ~ .main-content,
    body:has(.sidebar.collapsed) .main-content {
        margin-left: 0 !important;
    }
    
    .sidebar.collapsed .nav-link::after,
    .sidebar.collapsed .logout-button::after {
        display: none !important;
    }
}
</style>

<script>
// Toggle sidebar collapse/expand
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const collapseIcon = sidebar.querySelector('.collapse-icon');
    const expandIcon = sidebar.querySelector('.expand-icon');
    
    sidebar.classList.toggle('collapsed');
    
    // Save state to localStorage
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
    
    // Toggle icons
    if (isCollapsed) {
        collapseIcon.style.display = 'none';
        expandIcon.style.display = 'block';
    } else {
        collapseIcon.style.display = 'block';
        expandIcon.style.display = 'none';
    }
}

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    sidebar.classList.toggle('active');
    if (overlay) overlay.classList.toggle('active');
    
    if (sidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Restore sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Only apply collapsed state on desktop
    if (isCollapsed && window.innerWidth > 992) {
        sidebar.classList.add('collapsed');
        const collapseIcon = sidebar.querySelector('.collapse-icon');
        const expandIcon = sidebar.querySelector('.expand-icon');
        if (collapseIcon) collapseIcon.style.display = 'none';
        if (expandIcon) expandIcon.style.display = 'block';
    }
});

// Close sidebar when clicking a nav link on mobile
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Don't close for submenu toggles
        if (this.parentElement.classList.contains('has-submenu') && this.getAttribute('href') === '#') {
            return;
        }
        
        if (window.innerWidth <= 992) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 992) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
});
</script>
