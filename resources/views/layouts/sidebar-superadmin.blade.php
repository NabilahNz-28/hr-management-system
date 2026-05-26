<style>
    :root {
        --sidebar-bg: #ffffff;
        --sidebar-active: #3b82f6;
        --sidebar-hover: #f8fafc;
        --sidebar-text: #334155;
        --sidebar-border: #e2e8f0;
        --sidebar-width: 280px;
        --sidebar-collapsed: 80px;
        --header-height: 70px;
        --card-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
        --sidebar-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: #f8fafc;
        color: #334155;
        line-height: 1.5;
        font-weight: 400;
    }

    /* ===== SIDEBAR STYLES ===== */
    #sidebar {
        width: var(--sidebar-width);
        background-color: var(--sidebar-bg);
        color: var(--sidebar-text);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        transition: var(--transition);
        z-index: 1000;
        border-right: 1px solid var(--sidebar-border);
        box-shadow: var(--sidebar-shadow);
    }

    #sidebar.collapsed {
        width: var(--sidebar-collapsed);
    }

    .sidebar-header {
        height: var(--header-height);
        padding: 0 24px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid var(--sidebar-border);
        gap: 12px;
        background-color: white;
    }

    .logo {
        width: 36px;
        height: 36px;
        min-width: 36px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 18px;
    }

    .brand-text {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        transition: opacity 0.2s;
        white-space: nowrap;
        overflow: hidden;
    }

    #sidebar.collapsed .brand-text {
        opacity: 0;
        width: 0;
    }

    .sidebar-menu {
        padding: 20px 0;
        height: calc(100vh - var(--header-height));
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-menu::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 4px;
    }

    .menu-section {
        padding: 0 16px;
        margin-bottom: 24px;
    }

    .section-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 12px;
        padding-left: 12px;
        transition: opacity 0.2s;
        white-space: nowrap;
    }

    #sidebar.collapsed .section-label {
        opacity: 0;
        height: 0;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #475569;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 4px;
        transition: var(--transition);
        cursor: pointer;
        border-left: 3px solid transparent;
        background-color: white;
    }

    .menu-item:hover {
        background-color: var(--sidebar-hover);
        color: #1e293b;
        transform: translateX(2px);
        text-decoration: none;
    }

    .menu-item.active {
        background-color: #eff6ff;
        color: #1e293b;
        border-left-color: var(--sidebar-active);
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
    }

    .menu-icon {
        width: 20px;
        height: 20px;
        min-width: 20px;
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: var(--transition);
    }

    .menu-item.active .menu-icon {
        color: var(--sidebar-active);
    }

    .menu-item:hover .menu-icon {
        color: #1e293b;
    }

    .menu-text {
        font-size: 14px;
        font-weight: 500;
        transition: opacity 0.2s;
        white-space: nowrap;
        overflow: hidden;
    }

    #sidebar.collapsed .menu-text {
        opacity: 0;
        width: 0;
    }

    #sidebar.collapsed .menu-icon {
        margin-right: 0;
    }

    /* Logout item warna merah */
    .menu-item.logout-item {
        color: #ef4444;
    }

    .menu-item.logout-item .menu-icon {
        color: #ef4444;
    }

    .menu-item.logout-item:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    .menu-item.logout-item:hover .menu-icon {
        color: #dc2626;
    }
</style>

<!-- Font Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header">
        <div class="logo">SA</div>
        <div class="brand-text">Superadmin </div>
    </div>

    <div class="sidebar-menu">

        <div class="menu-section">
            <div class="section-label">UTAMA</div>

            <a href="{{ route('superadmin.dashboard') }}"
               class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <div class="menu-text">Dashboard</div>
            </a>
        </div>

        <div class="menu-section">
            <div class="section-label">KARYAWAN</div>

            <a href="{{ route('superadmin.karyawan.index') }}"
               class="menu-item {{ request()->routeIs('superadmin.karyawan.index') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="menu-text">Data Karyawan</div>
            </a>

            <a href="{{ route('superadmin.karyawan.create') }}"
               class="menu-item {{ request()->routeIs('superadmin.karyawan.create') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <div class="menu-text">Insert Karyawan</div>
            </a>
        </div>

        <div class="menu-section">
            <div class="section-label">APPROVAL</div>

            <a href="{{ route('superadmin.approval.index') }}"
               class="menu-item {{ request()->routeIs('superadmin.approval.index') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <polyline points="9 15 11 17 15 11"></polyline>
                    </svg>
                </div>
                <div class="menu-text">Approval Izin & Cuti</div>
            </a>
        </div>

        <div class="menu-section">
            <div class="section-label">INVENTORY</div>

            <a href="{{ route('superadmin.inventory.index') }}"
               class="menu-item {{ request()->routeIs('superadmin.inventory.index') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div class="menu-text">Data Inventory</div>
            </a>

            <a href="{{ route('superadmin.transfer.index') }}"
               class="menu-item {{ request()->routeIs('superadmin.transfer.index') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 3 21 3 21 8"></polyline>
                        <line x1="4" y1="20" x2="21" y2="3"></line>
                        <polyline points="21 16 21 21 16 21"></polyline>
                        <line x1="15" y1="15" x2="21" y2="21"></line>
                        <line x1="4" y1="4" x2="9" y2="9"></line>
                    </svg>
                </div>
                <div class="menu-text">Transfer Stock</div>
            </a>
        </div>

        <div class="menu-section">
            <div class="section-label">PENGATURAN</div>

            <a href="{{ route('superadmin.profile') }}"
               class="menu-item {{ request()->routeIs('superadmin.profile') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="menu-text">Profile Superadmin</div>
            </a>
        </div>

        <div class="menu-section">
            <a href="#" class="menu-item logout-item"
               onclick="event.preventDefault(); confirmLogout();">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <div class="menu-text">Logout</div>
            </a>
        </div>

    </div>{{-- end sidebar-menu --}}
</div>{{-- end sidebar --}}

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        document.getElementById('logout-form').submit();
    }
}
</script>