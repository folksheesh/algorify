<aside class="sidebar" id="sidebar">
    <header class="sidebar-header">
        <a href="javascript:location.reload();" class="logo" title="Refresh halaman">
            <img src="{{ asset('template/img/icon-logo.png') }}" alt="Algorify Logo" class="logo-icon">
            <span class="logo-text">Algorify</span>
        </a>
        <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
            <svg class="toggle-icon collapse-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <svg class="toggle-icon expand-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </header>
    <nav class="sidebar-nav">
        <ul class="nav-list">
            @if(Auth::user()->hasAnyRole(['admin', 'super admin']))
                <!-- Admin Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-title="Halaman Utama">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L3 8V17H7V12H13V17H17V8L10 3Z" stroke="currentColor" stroke-width="1.5"
                                fill="none" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                @if(Auth::user()->hasRole('super admin'))
                    <li class="nav-item {{ request()->routeIs('admin.admin.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.admin.index') }}" class="nav-link" data-title="Data Admin">
                            <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="nav-text">Data Admin</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ request()->routeIs('admin.peserta.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.peserta.index') }}" class="nav-link" data-title="Data Peserta">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Data Peserta</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pengajar.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengajar.index') }}" class="nav-link" data-title="Data Pengajar">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14L21 9L12 4L3 9L12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 14L12 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 9V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 11.5V16C6 16 7.5 18 12 18C16.5 18 18 16 18 16V11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Data Pengajar</span>
                    </a>
                </li>
                <li
                    class="nav-item has-submenu {{ request()->routeIs('admin.pelatihan.*') || request()->routeIs('admin.bank-soal.*') || request()->routeIs('admin.kategori.*') ? 'open' : '' }}">
                    <a href="#" class="nav-link" data-title="Data Pelatihan"
                        onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Data Pelatihan</span>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item {{ request()->routeIs('admin.pelatihan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pelatihan.index') }}" class="nav-link" data-title="Data Kursus">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.5 2H20V22H6.5C5.83696 22 5.20107 21.7366 4.73223 21.2678C4.26339 20.7989 4 20.163 4 19.5V4.5C4 3.83696 4.26339 3.20107 4.73223 2.73223C5.20107 2.26339 5.83696 2 6.5 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="nav-text">Data Kursus</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.bank-soal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.bank-soal.index') }}" class="nav-link" data-title="Bank Soal">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5C15 5.53043 14.7893 6.03914 14.4142 6.41421C14.0391 6.78929 13.5304 7 13 7H11C10.4696 7 9.96086 6.78929 9.58579 6.41421C9.21071 6.03914 9 5.53043 9 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 16H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="nav-text">Bank Soal</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kategori.index') }}" class="nav-link" data-title="Data Kategori">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.59 13.41L13.42 20.58C13.2343 20.766 13.0137 20.9135 12.7709 21.0141C12.5281 21.1148 12.2678 21.1666 12.005 21.1666C11.7422 21.1666 11.4819 21.1148 11.2391 21.0141C10.9963 20.9135 10.7757 20.766 10.59 20.58L2 12V2H12L20.59 10.59C20.9625 10.9647 21.1716 11.4716 21.1716 12C21.1716 12.5284 20.9625 13.0353 20.59 13.41Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 7H7.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="nav-text">Data Kategori</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.transaksi.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.transaksi.index') }}" class="nav-link" data-title="Transaksi">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M1 10H23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Transaksi</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.analitik.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.analitik.index') }}" class="nav-link" data-title="Analitik">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 20V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 20V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 20V14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Analitik</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.sertifikat.index') }}" class="nav-link" data-title="Sertifikat">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="8" r="6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.477 12.89L17 22L12 19L7 22L8.523 12.89" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Sertifikat</span>
                    </a>
                </li>
            @elseif(Auth::user()->hasRole('pengajar'))
                <!-- Pengajar Menu Items -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-title="Halaman Utama">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li
                    class="nav-item has-submenu {{ request()->routeIs('pengajar.kursus.*') || request()->routeIs('admin.bank-soal.*') ? 'open' : '' }}">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="nav-text">Data Pelatihan</span>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item {{ request()->routeIs('pengajar.kursus.*') ? 'active' : '' }}">
                            <a href="{{ route('pengajar.kursus.index') }}" class="nav-link">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.5 2H20V22H6.5C5.83696 22 5.20107 21.7366 4.73223 21.2678C4.26339 20.7989 4 20.163 4 19.5V4.5C4 3.83696 4.26339 3.20107 4.73223 2.73223C5.20107 2.26339 5.83696 2 6.5 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="nav-text">Data Kursus</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.bank-soal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.bank-soal.index') }}" class="nav-link" data-title="Bank Soal">
                                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 5C9 4.46957 9.21071 3.96086 9.58579 3.58579C9.96086 3.21071 10.4696 3 11 3H13C13.5304 3 14.0391 3.21071 14.4142 3.58579C14.7893 3.96086 15 4.46957 15 5C15 5.53043 14.7893 6.03914 14.4142 6.41421C14.0391 6.78929 13.5304 7 13 7H11C10.4696 7 9.96086 6.78929 9.58579 6.41421C9.21071 6.03914 9 5.53043 9 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 16H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L3 8V17H7V12H13V17H17V8L10 3Z" stroke="currentColor" stroke-width="1.5"
                                fill="none" />
                        </svg>
                        <span class="nav-text">Halaman Utama</span>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('user.pelatihan-saya.index') || request()->routeIs('admin.pelatihan.show') || request()->routeIs('admin.video.show') || request()->routeIs('admin.materi.show') || request()->routeIs('admin.ujian.show') || request()->routeIs('user.ujian.result') ? 'active' : '' }}">
                    <a href="{{ route('user.pelatihan-saya.index') }}" class="nav-link" data-title="Pelatihan Saya">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="3" width="12" height="14" rx="1" stroke="currentColor" stroke-width="1.5"
                                fill="none" />
                            <path d="M7 7H13M7 10H13M7 13H10" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span class="nav-text">Pelatihan Saya</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('kursus.index') ? 'active' : '' }}">
                    <a href="{{ route('kursus.index') }}" class="nav-link" data-title="Jelajahi Pelatihan">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L12 8H17L13 11L14.5 16L10 13L5.5 16L7 11L3 8H8L10 3Z" stroke="currentColor"
                                stroke-width="1.5" fill="none" />
                        </svg>
                        <span class="nav-text">Jelajahi Pelatihan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.sertifikat.index') ? 'active' : '' }}">
                    <a href="{{ route('user.sertifikat.index') }}" class="nav-link" data-title="Dapatkan Sertifikat">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5"
                                fill="none" />
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
                <svg class="logout-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 14L17 10L13 6M17 10H7M7 3H5C3.89543 3 3 3.89543 3 5V15C3 16.1046 3.89543 17 5 17H7"
                        stroke="currentColor" stroke-width="1.5" fill="none" />
                </svg>
                <span class="logout-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Toggle Button - positioned at sidebar edge -->
<button class="sidebar-edge-toggle" id="sidebarEdgeToggle" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
    <svg class="edge-collapse-icon" width="16" height="16" viewBox="0 0 20 20" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
    </svg>
    <svg class="edge-expand-icon" width="16" height="16" viewBox="0 0 20 20" fill="none"
        xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
    </svg>
</button>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<style>
    /* Active menu item background color */
    .nav-item.active>.nav-link {
        background: #5D3FFF;
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
        left: 278px;
        /* sidebar width (280px) - sedikit ke kanan agar tidak nabrak menu */
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 48px;
        background: #5D3FFF;
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
    .sidebar.collapsed~.sidebar-edge-toggle {
        left: 70px;
        /* collapsed width (72px) - sedikit ke kanan agar tidak nabrak menu */
    }

    /* Toggle icon visibility based on sidebar state */
    .sidebar.collapsed~.sidebar-edge-toggle .edge-collapse-icon {
        display: none !important;
    }

    .sidebar.collapsed~.sidebar-edge-toggle .edge-expand-icon {
        display: block !important;
    }

    .sidebar:not(.collapsed)~.sidebar-edge-toggle .edge-collapse-icon {
        display: block !important;
    }

    .sidebar:not(.collapsed)~.sidebar-edge-toggle .edge-expand-icon {
        display: none !important;
    }

    .sidebar-toggle-btn:hover {
        background: #5D3FFF;
        border-color: #5D3FFF;
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
        width: var(--sidebar-collapsed-width, 72px) !important;
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
    .sidebar.collapsed .nav-item.has-submenu>.nav-link::after {
        display: none;
    }

    /* Submenu in collapsed state - show as tooltip/popup */
    .sidebar.collapsed .submenu {
        display: none !important;
    }

    /* Adjust main content when sidebar is collapsed */
    .sidebar.collapsed~.main-content,
    body:has(.sidebar.collapsed) .main-content {
        margin-left: var(--sidebar-collapsed-width, 72px) !important;
        width: calc(100% - var(--sidebar-collapsed-width, 72px)) !important;
        max-width: calc(100vw - var(--sidebar-collapsed-width, 72px));
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

        .sidebar.collapsed~.main-content,
        body:has(.sidebar.collapsed) .main-content {
            margin-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .sidebar.collapsed .nav-link::after,
        .sidebar.collapsed .logout-button::after {
            display: none !important;
        }
    }
</style>

<script>
    function notifyLayoutChanged() {
        window.dispatchEvent(new Event('resize'));
        window.dispatchEvent(new CustomEvent('layout:changed'));
    }

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

        // Trigger a layout recalculation for responsive UI (charts, grids, etc.)
        requestAnimationFrame(() => requestAnimationFrame(notifyLayoutChanged));
        const onTransitionEnd = (e) => {
            if (e.target !== sidebar) return;
            if (!['width', 'max-width', 'flex-basis', 'transform'].includes(e.propertyName)) return;
            sidebar.removeEventListener('transitionend', onTransitionEnd);
            notifyLayoutChanged();
        };
        sidebar.addEventListener('transitionend', onTransitionEnd);
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
    document.addEventListener('DOMContentLoaded', function () {
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

            // Ensure layout consumers (like charts) start in the correct size
            setTimeout(notifyLayoutChanged, 0);
        }
    });

    // Close sidebar when clicking a nav link on mobile
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
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
    window.addEventListener('resize', function () {
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