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
        <div class="logo">AK</div>
        <div class="brand-text">Absensi Karyawan</div>
    </div>

    <div class="sidebar-menu">

        {{-- ===== DASHBOARD ===== --}}
        <div class="menu-section">
            <div class="section-label">DASHBOARD</div>

            <a href="{{ route('dashboard.absensi') }}"
               class="menu-item {{ request()->routeIs('dashboard.absensi') ? 'active' : '' }}">
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

        {{-- ===== ABSENSI ===== --}}
        <div class="menu-section">
            <div class="section-label">ABSENSI</div>

            <a href="{{ route('absensi.masuk') }}"
               class="menu-item {{ request()->routeIs('absensi.masuk') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="menu-text">Absensi Masuk</div>
            </a>

            <a href="{{ route('absensi.pulang') }}"
               class="menu-item {{ request()->routeIs('absensi.pulang') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
                <div class="menu-text">Absensi Pulang</div>
            </a>

            <a href="{{ route('absensi.pengajuan-izin') }}"
               class="menu-item {{ request()->routeIs('absensi.pengajuan-izin') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="menu-text">Pengajuan Izin</div>
            </a>

            <a href="{{ route('absensi.cuti') }}"
               class="menu-item {{ request()->routeIs('absensi.cuti') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </div>
                <div class="menu-text">Pengajuan Cuti</div>
            </a>
        </div>

        {{-- ===== MONITORING ===== --}}
        {{-- Route rekap belum ada, pakai '#' dulu. Nanti ganti dengan route() setelah dibuat --}}
        <div class="menu-section">
            <div class="section-label">MONITORING</div>

            <a href="#"
               class="menu-item {{ request()->routeIs('rekap.harian') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="menu-text">Rekap Harian</div>
            </a>

            <a href="#"
               class="menu-item {{ request()->routeIs('rekap.bulanan') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10H3"></path>
                        <path d="M21 6H3"></path>
                        <path d="M21 14H3"></path>
                        <path d="M21 18H3"></path>
                    </svg>
                </div>
                <div class="menu-text">Rekap Bulanan</div>
            </a>
        </div>

        {{-- ===== LAPORAN ===== --}}
        <div class="menu-section">
            <div class="section-label">LAPORAN</div>

            {{-- Route belum ada, pakai '#' dulu --}}
            <a href="#"
               class="menu-item {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="menu-text">Laporan Absensi</div>
            </a>

            {{-- Route belum ada, pakai '#' dulu --}}
            <a href="#"
               class="menu-item {{ request()->routeIs('laporan.keterlambatan') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="menu-text">Laporan Keterlambatan</div>
            </a>

            <a href="{{ route('laporan.cuti') }}"
               class="menu-item {{ request()->routeIs('laporan.cuti') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <div class="menu-text">Laporan Cuti & Izin</div>
            </a>
        </div>

        {{-- ===== PENGATURAN ===== --}}
        {{-- Route pengaturan belum ada, pakai '#' dulu --}}
        <div class="menu-section">
            <div class="section-label">PENGATURAN</div>

            <a href="#"
               class="menu-item {{ request()->routeIs('pengaturan.lokasi') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="menu-text">Lokasi Kantor</div>
            </a>

            <a href="#"
               class="menu-item {{ request()->routeIs('pengaturan.jam-kerja') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="menu-text">Jam Kerja</div>
            </a>

            <a href="#"
               class="menu-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="menu-text">Profile</div>
            </a>
        </div>

        {{-- ===== LOGOUT ===== --}}
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

{{-- Form Logout (hidden) --}}
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