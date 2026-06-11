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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div id="sidebar">
    <div class="sidebar-header">
        <div class="brand-text">Dashboard PIC </div>
    </div>

    <div class="sidebar-menu">

    <div class="menu-section">
        <div class="section-label">DASHBOARD</div>

        <a href="{{ route('dashboard.pic') }}"
           class="menu-item {{ request()->routeIs('dashboard.pic') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="bi bi-grid"></i>
            </div>
            <div class="menu-text">Dashboard</div>
        </a>
    </div>

    <div class="menu-section">
        <div class="section-label">INVENTORY</div>

        <a href="{{ route('inventory.stock-opname') }}"
           class="menu-item {{ request()->routeIs('inventory.stock-opname') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="menu-text">Stock Opname</div>
        </a>

        <a href="{{ route('inventory.transfer-stock') }}"
           class="menu-item {{ request()->routeIs('inventory.transfer-stock') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="menu-text">Transfer Stock</div>
        </a>
    </div>

    <div class="menu-section">
        <div class="section-label">LAPORAN</div>

        <a href="{{ route('inventory.laporan-opname') }}"
           class="menu-item {{ request()->routeIs('inventory.laporan-opname') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="menu-text">Laporan Opname</div>
        </a>

        <a href="{{ route('inventory.laporan-transfer') }}"
           class="menu-item {{ request()->routeIs('inventory.laporan-transfer') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="menu-text">Laporan Transfer</div>
        </a>
    </div>

    <div class="menu-section">
    <!-- Ubah onclick menjadi showLogoutModal() -->
    <a href="#" class="menu-item logout-item"
       onclick="event.preventDefault(); showLogoutModal();">
        <div class="menu-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <div class="menu-text">Logout</div>
    </a>
</div>

</div>
</div>

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