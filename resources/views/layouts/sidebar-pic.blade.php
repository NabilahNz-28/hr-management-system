<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard.pic') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PIC Inventory</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard.pic') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard.pic') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Inventory
    </div>

    <!-- Nav Item - Stock Opname -->
    <li class="nav-item {{ request()->routeIs('inventories.stock-opname') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventories.stock-opname') }}">
            <i class="fas fa-fw fa-clipboard-check"></i>
            <span>Stock Opname</span>
        </a>
    </li>

    <!-- Nav Item - Transfer Stock -->
    <li class="nav-item {{ request()->routeIs('inventories.transfer-stock') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventories.transfer-stock') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transfer Stock</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan
    </div>

    <!-- Nav Item - Laporan Opname -->
    <li class="nav-item {{ request()->routeIs('inventories.laporan-opname') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventories.laporan-opname') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan Opname</span>
        </a>
    </li>

    <!-- Nav Item - Laporan Transfer -->
    <li class="nav-item {{ request()->routeIs('inventories.laporan-transfer') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventories.laporan-transfer') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Laporan Transfer</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>