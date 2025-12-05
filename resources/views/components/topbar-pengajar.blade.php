{{-- Topbar untuk Pengajar --}}
<nav class="topbar-pengajar">
    <div class="topbar-left">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <img src="{{ asset('template/img/icon-logo.png') }}" alt="Algorify Logo" class="topbar-logo">
            <span class="topbar-logo-text">Algorify</span>
            <span class="topbar-badge">Pengajar</span>
        </a>
    </div>
    
    <div class="topbar-center">
        <form action="{{ route('admin.pelatihan.index') }}" method="GET" class="topbar-search">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
            </svg>
            <input type="search" name="search" class="topbar-search-input" placeholder="Cari kursus..." value="{{ request('search') }}">
        </form>
    </div>
    
    <div class="topbar-right">
        <div class="topbar-user-menu">
            <button class="topbar-user-btn" onclick="toggleUserDropdown()">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="{{ Auth::user()->name }}" class="topbar-avatar">
                @else
                    <div class="topbar-avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="topbar-user-info">
                    <span class="topbar-username">{{ Auth::user()->name }}</span>
                    <span class="topbar-role">Pengajar</span>
                </div>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="topbar-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <span class="dropdown-header-name">{{ Auth::user()->name }}</span>
                    <span class="dropdown-header-email">{{ Auth::user()->email }}</span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path d="M2 14C2 11.2386 4.68629 9 8 9C11.3137 9 14 11.2386 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>Profil Saya</span>
                </a>
                <a href="{{ route('dashboard') }}" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8L8 3L13 8V13C13 13.5523 12.5523 14 12 14H4C3.44772 14 3 13.5523 3 13V8Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                    <span>Halaman Peserta</span>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2H12C13.1046 2 14 2.89543 14 4V12C14 13.1046 13.1046 14 12 14H10M6 11L3 8M3 8L6 5M3 8H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    /* ============================================
       TOPBAR PENGAJAR - Navbar untuk Pengajar
       ============================================ */
    .topbar-pengajar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 64px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* Left Section */
    .topbar-pengajar .topbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .topbar-pengajar .hamburger-btn {
        display: none;
        width: 40px;
        height: 40px;
        border: none;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        cursor: pointer;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .topbar-pengajar .hamburger-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .topbar-pengajar .topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .topbar-pengajar .topbar-logo {
        width: 32px;
        height: 32px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }
    
    .topbar-pengajar .topbar-logo-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        letter-spacing: -0.5px;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .topbar-pengajar .topbar-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Right Section */
    
    /* Center Section - Search */
    .topbar-pengajar .topbar-center {
        flex: 1;
        max-width: 400px;
        margin: 0 2rem;
    }
    
    .topbar-pengajar .topbar-search {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .topbar-pengajar .topbar-search .search-icon {
        position: absolute;
        left: 14px;
        color: rgba(255, 255, 255, 0.6);
        pointer-events: none;
    }
    
    .topbar-pengajar .topbar-search-input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: white;
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.2s;
    }
    
    .topbar-pengajar .topbar-search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .topbar-pengajar .topbar-search-input:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
    }
    
    /* Right Section Content */
    .topbar-pengajar .topbar-right {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    /* Navigation Links */
    .topbar-pengajar .topbar-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .topbar-pengajar .topbar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .topbar-pengajar .topbar-nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }
    
    .topbar-pengajar .topbar-nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .topbar-pengajar .topbar-nav-link svg {
        flex-shrink: 0;
    }
    
    /* User Menu */
    .topbar-pengajar .topbar-user-menu {
        position: relative;
    }
    
    .topbar-pengajar .topbar-user-btn {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.375rem 0.75rem 0.375rem 0.375rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.1);
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }
    
    .topbar-pengajar .topbar-user-btn:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .topbar-pengajar .topbar-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .topbar-pengajar .topbar-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .topbar-pengajar .topbar-user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .topbar-pengajar .topbar-username {
        font-size: 0.8125rem;
        font-weight: 600;
        color: white;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.2;
    }
    
    .topbar-pengajar .topbar-role {
        font-size: 0.6875rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.2;
    }
    
    /* Dropdown */
    .topbar-pengajar .topbar-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 220px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s;
        z-index: 1001;
    }
    
    .topbar-pengajar .topbar-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .topbar-pengajar .dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid #E2E8F0;
    }
    
    .topbar-pengajar .dropdown-header-name {
        display: block;
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 0.125rem;
    }
    
    .topbar-pengajar .dropdown-header-email {
        display: block;
        font-size: 0.8125rem;
        color: #64748B;
    }
    
    .topbar-pengajar .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #64748B;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
    }
    
    .topbar-pengajar .dropdown-item:hover {
        background: #F8FAFC;
        color: #1E293B;
    }
    
    .topbar-pengajar .dropdown-item.logout-btn:hover {
        background: #FEF2F2;
        color: #DC2626;
    }
    
    .topbar-pengajar .dropdown-divider {
        height: 1px;
        background: #E2E8F0;
        margin: 0.25rem 0;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .topbar-pengajar .topbar-nav-link span {
            display: none;
        }
        
        .topbar-pengajar .topbar-nav-link {
            padding: 0.5rem;
        }
        
        .topbar-pengajar .topbar-user-info {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .topbar-pengajar .hamburger-btn {
            display: flex;
        }
        
        .topbar-pengajar .topbar-brand {
            display: none;
        }
        
        .topbar-pengajar .topbar-center {
            display: none;
        }
        
        .topbar-pengajar .topbar-nav {
            display: none;
        }
        
        .topbar-pengajar {
            padding: 0 1rem;
        }
    }
</style>

<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('show');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.querySelector('.topbar-user-menu');
        const dropdown = document.getElementById('userDropdown');
        
        if (userMenu && !userMenu.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
        
        if (overlay) {
            overlay.classList.toggle('active');
        }
    }
</script>
