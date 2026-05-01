<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PIC Inventory</title>
    
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
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
            overflow-x: hidden;
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
        }
        
        #sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar-menu {
            padding: 20px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
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
        }
        
        #sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        /* ===== MAIN CONTENT ===== */
        #main-content {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }
        
        #main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        .topbar {
            height: var(--header-height);
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .toggle-btn:hover {
            background-color: #f1f5f9;
            color: #334155;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .notification-btn:hover {
            background-color: #f1f5f9;
            color: #334155;
        }
        
        .badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* ===== DASHBOARD CONTENT ===== */
        .dashboard-content {
            padding: 24px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }
        
        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid #e2e8f0;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .stat-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        
        .stat-title {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .icon-opname {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .icon-transfer {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        .icon-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .icon-inventory {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .stat-change {
            font-size: 13px;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .stat-change.warning {
            color: #f59e0b;
        }
        
        /* Page Content */
        .page-content {
            display: none;
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            margin-top: 24px;
            min-height: 400px;
        }
        
        .page-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .content-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .content-description {
            color: #64748b;
            margin-bottom: 24px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-black {
            background: #1e293b;
            color: white;
            border: none;
        }
        
        .btn-black:hover {
            background: #334155;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .data-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #334155;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .data-table td {
            font-size: 14px;
            color: #475569;
        }
        
        /* Stock Opname Table - PERBAIKAN */
        .category-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: nowrap; /* ← GANTI DARI wrap KE nowrap */
    }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .action-buttons {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-shrink: 0;      /* ← TAMBAHIN INI */
        white-space: nowrap; /* ← TAMBAHIN INI */
    }

    .btn-black {
        white-space: nowrap; /* ← TAMBAHIN INI */
    }
        
        .category-btn {
            padding: 8px 16px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
            color: #334155;
            text-decoration: none;
        }

        .category-btn:hover {
            background-color: #e2e8f0;
        }

        .category-btn.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        
        /* Form Row */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        
        /* Alert Box */
        .alert-box {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            color: #92400e;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 1050;
            }
            
            #sidebar.mobile-open {
                left: 0;
            }
            
            #main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1040;
                background: #3b82f6;
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 8px;
                font-size: 20px;
                cursor: pointer;
            }
            
            .topbar {
                padding: 0 16px;
            }
            
            .dashboard-content {
                padding: 16px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .toggle-btn {
                display: none;
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <div class="logo">IP</div>
            <div class="brand-text">Inventory PIC</div>
        </div>
        
        <div class="sidebar-menu">
            <!-- DASHBOARD SECTION -->
            <div class="menu-section">
                <div class="section-label">DASHBOARD</div>
                
                <div class="menu-item active" data-page="dashboard-home">
                    <div class="menu-icon">
                        <i class="bi bi-grid"></i>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </div>
            </div>
            
            <!-- INVENTORY SECTION -->
            <div class="menu-section">
                <div class="section-label">INVENTORY</div>
                
                <div class="menu-item" data-page="stock-opname">
                    <div class="menu-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="menu-text">Stock Opname</div>
                </div>
                
                <div class="menu-item" data-page="transfer-stock">
                    <div class="menu-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div class="menu-text">Transfer Stock</div>
                </div>
            </div>
            
            <!-- REPORTS SECTION -->
            <div class="menu-section">
                <div class="section-label">LAPORAN</div>
                
                <div class="menu-item" data-page="laporan-opname">
                    <div class="menu-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="menu-text">Laporan Opname</div>
                </div>
                
                <div class="menu-item" data-page="laporan-transfer">
                    <div class="menu-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="menu-text">Laporan Transfer</div>
                </div>
            </div>
            
            <!-- LOGOUT SECTION -->
            <div class="menu-section">
                <div class="menu-item" data-page="logout">
                    <div class="menu-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div class="menu-text">Logout</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="toggle-btn" id="toggle-sidebar">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="user-menu">
                <button class="notification-btn">
                    <i class="bi bi-bell"></i>
                    <div class="badge"></div>
                </button>
                
                <div class="user-avatar">
                    no add yt
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Dashboard Home -->
            <div id="dashboard-home" class="page-content active">
                <h1 class="page-title">Dashboard PIC Inventory</h1>
                <p class="page-subtitle">Ringkasan aktivitas inventory bulan ini</p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Transaksi Opname Bulan Ini</div>
                            <div class="stat-icon icon-opname">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                        </div>
                        <div class="stat-value"> no add yet</div>
                        <div class="stat-change positive">+12% dari bulan lalu</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Transfer Stock Bulan Ini</div>
                            <div class="stat-icon icon-transfer">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                        <div class="stat-value">no add yet</div>
                        <div class="stat-change positive">+8% dari bulan lalu</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Stok Menipis</div>
                            <div class="stat-icon icon-pending">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-value">no add yet</div>
                        <div class="stat-change negative">Perlu perhatian</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Barang</div>
                            <div class="stat-icon icon-inventory">
                                <i class="bi bi-box"></i>
                            </div>
                        </div>
                        <div class="stat-value">no add yet</div>
                        <div class="stat-change positive">+5 barang baru</div>
                    </div>
                </div>
                
                <div class="page-content">
                    <h3 class="content-title">Aktivitas Terbaru</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Aktivitas</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                                <tr>
                                    <td>{{ isset($aktivitas->tanggal) ? \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') : '-' }}</td>
                                    <td>{{ $aktivitas->jenis ?? '-' }}</td>
                                    <td>{{ $aktivitas->nama_barang ?? '-' }}</td>
                                    <td>
                                       
                                    </td>
                                    <td>
                                       
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-center">Belum ada aktivitas</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Stock Opname -->
            <div id="stock-opname" class="page-content">
                <h1 class="page-title">Stock Opname</h1>
                <p class="page-subtitle">Kelola stock opname inventory</p>
                
                <div class="content-description">
                    <p>Pilih kategori untuk melihat barang yang tersedia:</p>
                </div>
                
                    <div class="category-filter">

                    <!-- Tombol kategori (kiri) -->
                    <div class="category-buttons">
                        <button class="category-btn active" data-category="all">Semua Kategori</button>
                        <button class="category-btn" data-category="eco">Eco</button>
                        <button class="category-btn" data-category="fragile">Fragile</button>
                        <button class="category-btn" data-category="plastic">Plastic</button>
                        <button class="category-btn" data-category="thermal">Thermal</button>
                        <button class="category-btn" data-category="carton">Carton</button>
                    </div>

                    <!-- Tombol aksi di kanan: Tambahkan Barang + Input Opname -->
                    <div class="action-buttons">
                        <button class="btn btn-black" id="add-item-btn">
                            <i class="bi bi-plus-circle me-2"></i> Tambahkan Barang
                        </button>
                        <button class="btn btn-black" id="show-opname-form">
                            <i class="bi bi-plus-circle me-2"></i> Input Opname
                        </button>
                    </div>
                </div>

                <div class="stock-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok </th>
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
        

            <!-- Transfer Stock -->
            <div id="transfer-stock" class="page-content">
                <h1 class="page-title">Transfer Stock</h1>
                <p class="page-subtitle">Transfer stock antar gudang</p>
                
                <div class="content-description">
                    <p>Isi form berikut untuk melakukan transfer stock dari gudang utama ke gudang tujuan:</p>
                </div>
                
                <div style="max-width: 800px; margin: 0 auto;">
                    <form id="transfer-form">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="transfer-date">Hari Tanggal</label>
                                <input type="date" class="form-control" id="transfer-date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="destination-warehouse">Gudang Tujuan</label>
                                <input type="text" class="form-control" id="destination-warehouse" placeholder="Contoh: Gudang Cabang Bandung" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="transfer-item">Nama Barang</label>
                            <select class="form-select" id="transfer-item" required>
                                <option value="">Pilih Barang</option>
                                <!-- Options akan diisi oleh JavaScript -->
                            </select>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="transfer-qty">Jumlah</label>
                                <input type="number" class="form-control" id="transfer-qty" placeholder="0" min="1" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="transfer-unit">Satuan</label>
                                <select class="form-select" id="transfer-unit" required>
                                    <option value="pcs">Pcs</option>
                                    <option value="carton">Carton</option>
                                    <option value="box">Box</option>
                                    <option value="pack">Pack</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="transfer-notes">Catatan (Opsional)</label>
                            <textarea class="form-control" id="transfer-notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                        
                        <div style="margin-top: 25px;">
                            
                            
                            <button type="submit" class="btn btn-black">
                                <i class="bi bi-send me-2"></i> Submit Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Laporan Opname -->
            <div id="laporan-opname" class="page-content">
                <h1 class="page-title">Laporan Opname</h1>
                <p class="page-subtitle">Laporan lengkap stock opname</p>
                
                <div class="content-description">
                    <p>Berikut adalah laporan stock opname periode bulan ini:</p>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok </th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- Data statis untuk contoh -->
                        <tr>
                            <td>15 Nov 2023</td>
                            <td>Box Eco 250ml</td>
                            <td><span class="category-btn" style="padding: 4px 8px; font-size: 12px;">Eco</span></td>
                            <td>500 pcs</td>
                        </tr>
                        <tr>
                            <td>13 Nov 2023</td>
                            <td>Lid Cup Thermal</td>
                            <td><span class="category-btn" style="padding: 4px 8px; font-size: 12px;">Thermal</span></td>
                            <td>800 pcs</td>
                            
                        </tr>
                        <tr>
                            <td>10 Nov 2023</td>
                            <td>Paper Bowl 500ml</td>
                            <td><span class="category-btn" style="padding: 4px 8px; font-size: 12px;">Eco</span></td>
                            <td>300 pcs</td>
                            
                        </tr>
                        <tr>
                            <td>08 Nov 2023</td>
                            <td>Gelas Plastik 12oz</td>
                            <td><span class="category-btn" style="padding: 4px 8px; font-size: 12px;">Plastic</span></td>
                            <td>1000 pcs</td>
                           
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Laporan Transfer -->
            <div id="laporan-transfer" class="page-content">
                <h1 class="page-title">Laporan Transfer</h1>
                <p class="page-subtitle">Laporan lengkap transfer stock antar gudang</p>
                
                <div class="content-description">
                    <p>Berikut adalah laporan transfer stock periode bulan ini:</p>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Dari Gudang</th>
                            <th>Ke Gudang</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data statis untuk contoh -->
                        <tr>
                            <td>14 Nov 2023</td>
                            <td>Gelas Plastik 12oz</td>
                            <td>Gudang Utama</td>
                            <td>Gudang Cabang Bandung</td>
                            <td>200 pcs</td>
                            <td>Permintaan cabang</td>
                        </tr>
                        <tr>
                            <td>12 Nov 2023</td>
                            <td>Paper Bowl 500ml</td>
                            <td>Gudang Utama</td>
                            <td>Gudang Cabang Surabaya</td>
                            <td>150 pcs</td>
                            <td>Stok cadangan</td>
                        </tr>
                        <tr>
                            <td>08 Nov 2023</td>
                            <td>Box Eco 250ml</td>
                            <td>Gudang Utama</td>
                            <td>Gudang Cabang Jakarta</td>
                            <td>100 pcs</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>05 Nov 2023</td>
                            <td>Lid Cup Thermal</td>
                            <td>Gudang Utama</td>
                            <td>Gudang Cabang Semarang</td>
                            <td>80 pcs</td>
                            <td>Persediaan akhir bulan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data barang inventory (simulasi)
        const inventoryItems = [
            { id: 1, name: "Box Eco 250ml", category: "eco", currentStock: 620 },
            { id: 2, name: "Paper Bowl 500ml", category: "eco", currentStock: 320  },
            { id: 3, name: "Gelas Plastik 12oz", category: "plastic", currentStock: 99},
            { id: 4, name: "Gelas Plastik 16oz", category: "plastic", currentStock: 79},
            { id: 5, name: "Lid Cup Thermal", category: "thermal", currentStock: 850},
            { id: 6, name: "Cup Thermal 8oz", category: "thermal", currentStock: 600},
            { id: 7, name: "Mug Keramik", category: "fragile", currentStock: 120},
            { id: 8, name: "Gelas Kaca 350ml", category: "fragile", currentStock: 90},
            { id: 9, name: "Box Carton Besar", category: "carton", currentStock: 200},
            { id: 10, name: "Box Carton Kecil", category: "carton", currentStock: 350}
        ];

        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuItems = document.querySelectorAll('.menu-item');
        const pageContents = document.querySelectorAll('.page-content');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const addItemBtn = document.getElementById('add-item-btn');
        const addItemForm = document.getElementById('add-item-form');
        const cancelFormBtn = document.getElementById('cancel-form');
        const itemForm = document.getElementById('item-form');
        const stockTableBody = document.getElementById('stock-table-body');
        const transferForm = document.getElementById('transfer-form');
        const transferItemSelect = document.getElementById('transfer-item');
        const destinationWarehouse = document.getElementById('destination-warehouse');
        const destPreview = document.getElementById('dest-preview');
        
        // Toggle Sidebar
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
        
        // Mobile Menu Toggle
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.add('mobile-open');
            sidebarOverlay.classList.add('active');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });
        
        // Switch Pages
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const pageId = this.getAttribute('data-page');
                
                if (pageId === 'logout') {
                    if (confirm('Apakah Anda yakin ingin logout?')) {
                        window.location.href = "{{ route('logout') }}";
                    }
                    return;
                }
                
                // Update active menu item
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected page
                pageContents.forEach(page => {
                    page.classList.remove('active');
                });
                
                document.getElementById(pageId).classList.add('active');
                
                // Update page title and subtitle
                updatePageTitle(pageId);
                
                // Close mobile sidebar if open
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
            });
        });
        
        // Update page title based on active page
        function updatePageTitle(pageId) {
            const titles = {
                'dashboard-home': { title: 'Dashboard PIC Inventory', subtitle: 'Ringkasan aktivitas inventory' },
                'stock-opname': { title: 'Stock Opname', subtitle: 'Kelola stock opname inventory' },
                'transfer-stock': { title: 'Transfer Stock', subtitle: 'Transfer stock antar gudang' },
                'laporan-opname': { title: 'Laporan Opname', subtitle: 'Laporan lengkap stock opname' },
                'laporan-transfer': { title: 'Laporan Transfer', subtitle: 'Laporan lengkap transfer stock' }
            };
            
            const pageTitle = document.querySelector('.page-title');
            const pageSubtitle = document.querySelector('.page-subtitle');
            
            if (titles[pageId]) {
                pageTitle.textContent = titles[pageId].title;
                pageSubtitle.textContent = titles[pageId].subtitle;
            }
        }
        
        // Filter Category in Stock Opname
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                
                // Update active button
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter and display items
                displayStockItems(category);
            });
        });
        
        // Function to display stock items based on category
        function displayStockItems(category = 'all') {
            stockTableBody.innerHTML = '';
            
            let filteredItems = inventoryItems;
            
            if (category !== 'all') {
                filteredItems = inventoryItems.filter(item => item.category === category);
            }
            
            filteredItems.forEach(item => {
                const statusClass = item.difference === 0 ? 'status-present' : 
                                  item.difference > 0 ? 'status-wfh' : 'status-absent';
                
                const statusText = item.difference === 0 ? 'Sesuai' : 
                                 item.difference > 0 ? 'Lebih' : 'Kurang';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td><span class="category-btn" style="padding: 4px 8px; font-size: 12px;">${item.category}</span></td>
                    <td>${item.currentStock} pcs</td>
                    <td>${item.physicalStock} pcs</td>
                    <td>${item.difference > 0 ? '+' : ''}${item.difference} pcs</td>
                `;
                stockTableBody.appendChild(row);
            });
        }
        
        // Show/Hide Add Item Form - PERBAIKAN
        addItemBtn.addEventListener('click', function() {
            addItemForm.style.display = 'block';
            window.scrollTo({ top: addItemForm.offsetTop - 100, behavior: 'smooth' });
        });

        cancelFormBtn.addEventListener('click', function() {
            addItemForm.style.display = 'none';
            itemForm.reset();
        });
        
        // Handle Add Item Form Submission
        itemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const itemName = document.getElementById('item-name').value;
            const itemCategory = document.getElementById('item-category').value;
            const itemPcs = parseInt(document.getElementById('item-pcs').value) || 0;
            const itemCarton = parseInt(document.getElementById('item-carton').value) || 0;
            const itemNotes = document.getElementById('item-notes').value;
            
            // Simulate adding item to inventory
            const totalPcs = itemPcs + (itemCarton * 24); // Assuming 24 pcs per carton
            
            // Create new item object
            const newItem = {
                id: inventoryItems.length + 1,
                name: itemName,
                category: itemCategory,
                currentStock: totalPcs,
                
            };
            
            // Add to inventory array
            inventoryItems.push(newItem);
            
            // Refresh table display
            const activeCategory = document.querySelector('.category-btn.active').getAttribute('data-category');
            displayStockItems(activeCategory);
            
            // Update total items count
            document.querySelectorAll('.stat-value')[3].textContent = inventoryItems.length;
            
            // Reset form and hide
            itemForm.reset();
            addItemForm.style.display = 'none';
            addItemBtn.style.display = 'block';
            
            // Show success message
            alert(`Barang "${itemName}" berhasil ditambahkan dengan total ${totalPcs} pcs.`);
        });
        
        // Populate transfer item select
        function populateTransferItems() {
            transferItemSelect.innerHTML = '<option value="">Pilih Barang</option>';
            
            inventoryItems.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = `${item.name} (${item.currentStock} pcs tersedia)`;
                transferItemSelect.appendChild(option);
            });
        }
        
        // Update destination preview
        destinationWarehouse.addEventListener('input', function() {
            destPreview.textContent = this.value || '[Gudang Tujuan]';
        });
        
        // Handle Transfer Form Submission
        transferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const transferDate = document.getElementById('transfer-date').value;
            const destWarehouse = document.getElementById('destination-warehouse').value;
            const selectedItemId = parseInt(document.getElementById('transfer-item').value);
            const transferQty = parseInt(document.getElementById('transfer-qty').value);
            const transferUnit = document.getElementById('transfer-unit').value;
            const transferNotes = document.getElementById('transfer-notes').value;
            
            if (!selectedItemId) {
                alert('Silakan pilih barang yang akan ditransfer.');
                return;
            }
            
            // Find selected item
            const selectedItem = inventoryItems.find(item => item.id === selectedItemId);
            
            if (!selectedItem) {
                alert('Barang tidak ditemukan.');
                return;
            }
            
            // Check if stock is sufficient
            if (transferQty > selectedItem.currentStock) {
                alert(`Stok tidak mencukupi. Stok tersedia: ${selectedItem.currentStock} pcs.`);
                return;
            }
            
            // Convert to pcs if needed
            let qtyInPcs = transferQty;
            if (transferUnit === 'carton') {
                qtyInPcs = transferQty * 24; // Assuming 24 pcs per carton
            } else if (transferUnit === 'box') {
                qtyInPcs = transferQty * 12; // Assuming 12 pcs per box
            } else if (transferUnit === 'pack') {
                qtyInPcs = transferQty * 6; // Assuming 6 pcs per pack
            }
            
           
            
            // Update transfer count
            const transferCountElement = document.querySelectorAll('.stat-value')[1];
            let transferCount = parseInt(transferCountElement.textContent);
            transferCountElement.textContent = transferCount + 1;
            
            // Reset form
            transferForm.reset();
            document.getElementById('transfer-date').value = new Date().toISOString().split('T')[0];
            destPreview.textContent = '[Gudang Tujuan]';
            
            // Show success message
            const dateObj = new Date(transferDate);
            const formattedDate = dateObj.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            alert(`Transfer berhasil!\n\nTanggal: ${formattedDate}\nBarang: ${selectedItem.name}\nJumlah: ${transferQty} ${transferUnit} (${qtyInPcs} pcs)\nDari: Gudang Utama\nKe: ${destWarehouse}\n\nCatatan: ${transferNotes || '-'}`);
        });
        
        // Initialize page
        function init() {
            // Set today's date as default for transfer date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('transfer-date').value = today;
            
            // Display initial stock items
            displayStockItems('all');
            
            // Populate transfer items
            populateTransferItems();
            
            // Set initial destination preview
            destPreview.textContent = '[Gudang Tujuan]';
            
            // Check if we're on a specific page from URL hash
            const hash = window.location.hash.substring(1);
            if (hash && document.getElementById(hash)) {
                const menuItem = document.querySelector(`.menu-item[data-page="${hash}"]`);
                if (menuItem) {
                    menuItem.click();
                }
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>