{{-- Topbar untuk Pengajar - Mirip dengan Peserta --}}
@php
    $displayName = display_user_name();
    $avatarInitial = strtoupper(substr($displayName, 0, 1));
@endphp
<nav class="topbar-pengajar">
    <div class="topbar-left">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <img src="{{ asset('template/img/icon-logo.png') }}" alt="Algorify Logo" class="topbar-logo">
            <span class="topbar-logo-text">Algorify</span>
        </a>
    </div>

    <div class="topbar-center">
        <form action="{{ route('admin.pelatihan.index') }}" method="GET" class="topbar-search">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
            </svg>
            <input type="search" name="search" class="topbar-search-input" placeholder="Cari kursus..."
                value="{{ request('search') }}">
        </form>
    </div>

    <div class="topbar-right">
        <div class="topbar-user-menu">
            <button class="topbar-user-btn" onclick="toggleUserDropdown()">
                @if (Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="{{ $displayName }}"
                        class="topbar-avatar">
                @else
                    <div class="topbar-avatar-placeholder">
                        {{ $avatarInitial }}
                    </div>
                @endif
                <span class="topbar-username">{{ $displayName }}</span>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <div class="topbar-dropdown" id="userDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.5"
                            fill="none" />
                        <path d="M2 14C2 11.2386 4.68629 9 8 9C11.3137 9 14 11.2386 14 14" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>Profil Saya</span>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2H12C13.1046 2 14 2.89543 14 4V12C14 13.1046 13.1046 14 12 14H10M6 11L3 8M3 8L6 5M3 8H11"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
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
       TOPBAR PENGAJAR - Navbar untuk Pengajar (Mirip Peserta)
       ============================================ */
    .topbar-pengajar {
        position: fixed;
        top: 0;
        left: 280px;
        /* Start after sidebar */
        right: 0;
        height: 72px;
        background: #FFFFFF;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 1rem 0 1rem;
        z-index: 1000;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Adjust topbar when sidebar is collapsed */
    .sidebar.collapsed~.topbar-pengajar,
    body:has(.sidebar.collapsed) .topbar-pengajar {
        left: 72px;
    }

    /* Left Section - Hidden since logo is in sidebar */
    .topbar-pengajar .topbar-left {
        display: none;
        align-items: center;
        gap: 1rem;
    }

    .topbar-pengajar .hamburger-btn {
        display: none;
        width: 44px;
        height: 44px;
        border: none;
        background: transparent;
        color: #64748B;
        cursor: pointer;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-pengajar .hamburger-btn:hover {
        background: #F1F5F9;
        color: #1E293B;
    }

    .topbar-pengajar .topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .topbar-pengajar .topbar-logo {
        width: 38px;
        height: 38px;
        object-fit: contain;
    }

    .topbar-pengajar .topbar-logo-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3A6DFF;
        letter-spacing: -0.5px;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Center Section - Search (centered) */
    .topbar-pengajar .topbar-center {
        flex: 1;
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
    }

    .topbar-pengajar .topbar-search {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .topbar-pengajar .topbar-search .search-icon {
        position: absolute;
        left: 18px;
        color: #94A3B8;
        pointer-events: none;
    }

    .topbar-pengajar .topbar-search-input {
        width: 100%;
        min-width: 400px;
        padding: 14px 20px 14px 50px;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        font-size: 0.9375rem;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #1E293B;
        background: #F8FAFC;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-pengajar .topbar-search-input::placeholder {
        color: #94A3B8;
    }

    .topbar-pengajar .topbar-search-input:focus {
        outline: none;
        border-color: #3A6DFF;
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
        transform: scale(1.02);
    }

    /* Right Section Content - Profile aligned with hero banner edge */
    .topbar-pengajar .topbar-right {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: absolute;
        right: 48px;
        /* Same as main-content padding-right */
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
        border: 1px solid #E2E8F0;
        border-radius: 50px;
        background: #FFFFFF;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #64748B;
    }

    .topbar-pengajar .topbar-user-btn:hover {
        border-color: #CBD5E1;
        background: #F8FAFC;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .topbar-pengajar .topbar-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #E2E8F0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-pengajar .topbar-user-btn:hover .topbar-avatar {
        transform: scale(1.05);
    }

    .topbar-pengajar .topbar-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9375rem;
        font-weight: 600;
        border: 2px solid #E2E8F0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-pengajar .topbar-user-btn:hover .topbar-avatar-placeholder {
        transform: scale(1.05);
    }

    .topbar-pengajar .topbar-username {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1E293B;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: transform 0.2s ease;
    }

    .topbar-pengajar .topbar-user-btn:hover .topbar-username {
        transform: translateX(2px);
    }

    /* Dropdown */
    .topbar-pengajar .topbar-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 200px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) scale(0.95);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1001;
    }

    .topbar-pengajar .topbar-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
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
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
    }

    .topbar-pengajar .dropdown-item:first-child {
        border-radius: 12px 12px 0 0;
    }

    .topbar-pengajar .dropdown-item:last-child {
        border-radius: 0 0 12px 12px;
    }

    .topbar-pengajar .dropdown-item:hover {
        background: #F8FAFC;
        color: #1E293B;
        padding-left: 1.25rem;
    }

    .topbar-pengajar .dropdown-item:hover svg {
        transform: scale(1.1);
    }

    .topbar-pengajar .dropdown-item svg {
        transition: transform 0.2s ease;
    }

    .topbar-pengajar .dropdown-item.logout-btn:hover {
        background: #FEF2F2;
        color: #DC2626;
        padding-left: 1.25rem;
    }

    .topbar-pengajar .dropdown-divider {
        height: 1px;
        background: #E2E8F0;
        margin: 0.25rem 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .topbar-pengajar {
            left: 0;
            padding: 0 1.5rem;
            justify-content: space-between;
        }

        .topbar-pengajar .topbar-left {
            display: flex;
        }

        .topbar-pengajar .hamburger-btn {
            display: flex;
        }

        .topbar-pengajar .topbar-center {
            margin: 0 1rem;
        }

        .topbar-pengajar .topbar-right {
            position: relative;
            right: auto;
        }

        .topbar-pengajar .topbar-username {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .topbar-pengajar {
            height: 64px;
            padding: 0 1rem;
        }

        .topbar-pengajar .topbar-center {
            display: none;
        }

        .topbar-pengajar .topbar-brand .topbar-logo-text {
            display: none;
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
