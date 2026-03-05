<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi Karyawan</title>
    
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
        
        .icon-present {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .icon-late {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .icon-absent {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .icon-online {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }
        
        .form-control:focus {
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
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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
        
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        /* Webcam and Map Styles */
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            background-color: #1e293b;
        }
        
        #webcam {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .capture-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .capture-btn:hover {
            transform: translateX(-50%) scale(1.05);
        }
        
        .captured-photo {
            width: 100%;
            max-width: 320px;
            border-radius: 8px;
            border: 3px solid #3b82f6;
        }
        
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .map-placeholder {
            width: 100%;
            height: 100%;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }
        
        .location-info {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        
        .gps-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        
        .gps-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .gps-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -280px;
            }
            
            #sidebar.mobile-open {
                margin-left: 0;
            }
            
            #main-content {
                margin-left: 0;
            }
            
            .topbar {
                padding: 0 16px;
            }
            
            .dashboard-content {
                padding: 16px;
            }
            
            .camera-container {
                max-width: 100%;
            }
        }


    </style>
</head>

<script>
// SOLUSI GRATIS - OpenStreetMap
const officeLocation = [-6.058908, 106.653040];
let userMarker = null;

// Init map sederhana
function initSimpleMap() {
    const map = L.map('mapPlaceholder').setView(officeLocation, 17);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    // Marker kantor
    L.marker(officeLocation)
        .addTo(map)
        .bindPopup('Kantor')
        .openPopup();
    
    // Circle radius 100m
    L.circle(officeLocation, {
        radius: 100,
        color: 'blue',
        fillOpacity: 0.2
    }).addTo(map);
    
    return map;
}

// Cek lokasi user
function cekLokasiUser() {
    navigator.geolocation.getCurrentPosition((pos) => {
        const userLat = pos.coords.latitude;
        const userLng = pos.coords.longitude;
        
        // Hitung jarak
        const jarak = hitungJarak(userLat, userLng, officeLocation[0], officeLocation[1]);
        
        // Update UI
        document.getElementById('jarakInfo').innerHTML = 
            `Jarak: <b>${jarak}m</b> dari kantor`;
        
        // Jika > 100m, disable submit
        document.getElementById('submitBtn').disabled = jarak > 100;
    });
}

// Hitung jarak sederhana
function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + 
              Math.cos(lat1 * Math.PI / 180) * 
              Math.cos(lat2 * Math.PI / 180) * 
              Math.sin(dLon/2)**2;
    return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

// Jalankan saat halaman load
window.onload = function() {
    initSimpleMap();
    cekLokasiUser();
};
</script>

<!-- Load Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <div class="logo">AK</div>
            <div class="brand-text">Absensi Karyawan</div>
        </div>
        
        <div class="sidebar-menu">
            <!-- DASHBOARD SECTION -->
            <div class="menu-section">
                <div class="section-label">DASHBOARD</div>
                
                <div class="menu-item active" data-page="dashboard-home">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </div>
            </div>
            
            <!-- ABSENSI SECTION -->
            <div class="menu-section">
                <div class="section-label">ABSENSI</div>
                
                <div class="menu-item" data-page="absensi-masuk">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="menu-text">Absensi Masuk</div>
                </div>
                
                <div class="menu-item" data-page="absensi-pulang">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <div class="menu-text">Absensi Pulang</div>
                </div>
                
                <div class="menu-item" data-page="absensi-izin">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="menu-text">Pengajuan Izin</div>
                </div>
                
                <div class="menu-item" data-page="absensi-cuti">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                    </div>
                    <div class="menu-text">Pengajuan Cuti</div>
                </div>
            </div>
            
            <!-- MONITORING SECTION -->
            <div class="menu-section">
                <div class="section-label">MONITORING</div>
                
                <div class="menu-item" data-page="rekap-harian">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="menu-text">Rekap Harian</div>
                </div>
                
                <div class="menu-item" data-page="rekap-bulanan">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10H3"></path>
                            <path d="M21 6H3"></path>
                            <path d="M21 14H3"></path>
                            <path d="M21 18H3"></path>
                        </svg>
                    </div>
                    <div class="menu-text">Rekap Bulanan</div>
                </div>
                
                
            </div>
            
            <!-- LAPORAN SECTION -->
            <div class="menu-section">
                <div class="section-label">LAPORAN</div>
                
                <div class="menu-item" data-page="laporan-absensi">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="menu-text">Laporan Absensi</div>
                </div>
                
                <div class="menu-item" data-page="laporan-keterlambatan">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="menu-text">Laporan Keterlambatan</div>
                </div>
                
                <div class="menu-item" data-page="laporan-cuti">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="menu-text">Laporan Cuti & Izin</div>
                </div>
            </div>
            
            <!-- SETTINGS SECTION -->
            <div class="menu-section">
                <div class="section-label">PENGATURAN</div>
                
                <div class="menu-item" data-page="lokasi-kantor">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="menu-text">Lokasi Kantor</div>
                </div>
                
                <div class="menu-item" data-page="jam-kerja">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="menu-text">Jam Kerja</div>
                </div>
                
                <div class="menu-item" data-page="profile">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div class="menu-text">Profile</div>
                </div>
            </div>
            
            <!-- LOGOUT -->
            <div class="menu-section">
                <div class="menu-item" data-page="logout">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
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
            <button class="toggle-btn" id="sidebarToggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            
            <div class="user-menu">
                <button class="notification-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="badge"></span>
                </button>
                
                <div class="user-avatar">AK</div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <h1 class="page-title" id="mainPageTitle">Dashboard Absensi</h1>
            <p class="page-subtitle" id="mainPageSubtitle">Selamat datang! Sistem absensi dengan GPS dan face recognition</p>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Hadir Bulan Ini</div>
                        <div class="stat-icon icon-present">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">20 Hari</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Terlambat Bulan Ini</div>
                        <div class="stat-icon icon-late">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">0 Hari </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Libur Bulan Ini</div>
                        <div class="stat-icon icon-absent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">5 Hari</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Izin Bulan Ini</div>
                        <div class="stat-icon icon-online">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="4"></circle>
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">0 Hari</div>
                </div>
            </div>
            
            <!-- Page Content -->
            <div id="pageContent">
                <!-- Dashboard Home -->
                <div class="page-content active" id="dashboard-home">
                    <div class="content-title">Dashboard Absensi</div>
                    <p class="content-description">Ringkasan absensi karyawan hari ini</p>
                    
                    <div class="alert-box alert-success">
                        <div style="font-size: 24px;">✅</div>
                        <div>
                            <div style="font-weight: 500;">Absensi Masuk Anda sudah tercatat hari ini!</div>
                            <div style="font-size: 13px;">Waktu absensi: 08:15 | Lokasi: Kantor Pusat</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 20px;">
                        <div>
                            <div class="content-title" style="font-size: 16px;">Aktivitas Absensi Terkini</div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Lokasi</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ahmad Wijaya</td>
                                        <td>07:45</td>
                                        <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                        <td>Kantor Pusat</td>
                                        <td><button class="btn btn-primary btn-sm" onclick="showPhoto('ahmad')">Lihat</button></td>
                                    </tr>
                                    <tr>
                                        <td>Siti Rahma</td>
                                        <td>08:05</td>
                                        <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                        <td>Kantor Pusat</td>
                                        <td><button class="btn btn-primary btn-sm" onclick="showPhoto('siti')">Lihat</button></td>
                                    </tr>
                                    <tr>
                                        <td>Budi Santoso</td>
                                        <td>08:31</td>
                                        <td><span class="status-badge status-late">Terlambat</span></td>
                                        <td>Kantor Cabang</td>
                                        <td><button class="btn btn-primary btn-sm" onclick="showPhoto('budi')">Lihat</button></td>
                                    </tr>
                                    <tr>
                                        <td>Dewi Anggraini</td>
                                        <td>07:50</td>
                                        <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                        <td>Kantor Pusat</td>
                                        <td><button class="btn btn-primary btn-sm" onclick="showPhoto('dewi')">Lihat</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div>
                            <div class="content-title" style="font-size: 16px;">Statistik Minggu Ini</div>
                            <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                                <div style="margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Senin</span>
                                        <span style="font-weight: 600;">95%</span>
                                    </div>
                                    <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: 95%; height: 100%; background-color: #10b981;"></div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Selasa</span>
                                        <span style="font-weight: 600;">92%</span>
                                    </div>
                                    <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: 92%; height: 100%; background-color: #10b981;"></div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Rabu</span>
                                        <span style="font-weight: 600;">88%</span>
                                    </div>
                                    <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: 88%; height: 100%; background-color: #f59e0b;"></div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Kamis</span>
                                        <span style="font-weight: 600;">90%</span>
                                    </div>
                                    <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: 90%; height: 100%; background-color: #10b981;"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Jumat</span>
                                        <span style="font-weight: 600;">85%</span>
                                    </div>
                                    <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: 85%; height: 100%; background-color: #f59e0b;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 
                <!-- Absensi Masuk -->
<div class="page-content" id="absensi-masuk">
    <div class="content-title">Absensi Masuk</div>
    <p class="content-description">Lakukan absensi masuk dengan foto wajah dan GPS</p>
    
    <div class="alert-box alert-info">
        <div style="font-size: 24px;">📍</div>
        <div>
            <div style="font-weight: 500;">Jam kerja: 08:00 - 17:00 WIB</div>
            <div style="font-size: 13px;">Absensi masuk maksimal pukul 08:15. Setelah itu dianggap terlambat.</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
        <div>
            <div class="content-title" style="font-size: 16px;">Foto Wajah</div>
            <div class="camera-container">
                <div id="webcamContainer">
                    <video id="webcam" autoplay playsinline></video>
                    <!-- Status Kamera -->
                    <div id="cameraStatus" style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b7280;">
                        Menunggu kamera...
                    </div>
                </div>
                <button class="capture-btn" id="captureBtn" onclick="ambilFoto('masuk')">
                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                </button>
            </div>
            
            <div id="photoPreview" style="display: none; margin-top: 16px;">
                <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                <img id="capturedPhoto" class="captured-photo" alt="Captured Photo">
                <!-- Timer Display -->
                <div id="timerDisplay" style="margin-top: 10px; padding: 8px; background: #f3f4f6; border-radius: 4px; text-align: center; font-weight: bold; color: #3b82f6;">
                    Timer: 0 detik
                </div>
                <div style="margin-top: 12px;">
                    <button class="btn btn-success" id="submitBtnMasuk" onclick="submitAbsensi('masuk')" disabled>Submit Absensi Masuk</button>
                    <button class="btn btn-danger" onclick="retakePhoto('masuk')">Ambil Ulang</button>
                </div>
            </div>
        </div>
        
        <div>
            <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
            <div class="map-container">
                <div class="map-placeholder" id="mapPlaceholder">
                    <div style="text-align: center; padding: 50px 0;">
                        <div style="font-size: 48px;">📍</div>
                        <div style="font-weight: 500; margin-top: 8px;">Memuat peta...</div>
                        <button onclick="refreshGPS('masuk')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            🔄 Muat Ulang Peta
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="gps-status" id="gpsStatusMasuk">
                <div>⏳</div>
                <div>
                    <div style="font-weight: 500;">Memuat GPS...</div>
                    <div style="font-size: 12px;">Harap tunggu</div>
                </div>
            </div>
            
            <div class="location-info">
                <div style="font-weight: 500; margin-bottom: 8px;">Detail Lokasi:</div>
                <div style="font-size: 14px;">
                    <div>📍 <span id="locationAddressMasuk">Mendeteksi alamat...</span></div>
                    <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoordsMasuk">-6.058908, 106.653040</span></div>
                    <div style="margin-top: 8px;">📍 Jarak dari kantor: <span id="locationDistanceMasuk">0 m</span></div>
                </div>
                <button onclick="refreshGPS('masuk')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; width: 100%;">
                    🔄 Refresh Lokasi GPS
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Absensi Pulang -->
<div class="page-content" id="absensi-pulang">
    <div class="content-title">Absensi Pulang</div>
    <p class="content-description">Lakukan absensi pulang dengan foto wajah dan GPS</p>
    
    <div class="alert-box alert-success">
        <div style="font-size: 24px;">✅</div>
        <div>
            <div style="font-weight: 500;">Absensi masuk Anda hari ini sudah tercatat</div>
            <div style="font-size: 13px;">Waktu masuk: <span id="waktuMasuk">08:15</span> | Anda dapat absen pulang mulai pukul 16:30</div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
        <div>
            <div class="content-title" style="font-size: 16px;">Foto Wajah</div>
            <div class="camera-container">
                <div id="webcamContainerPulang">
                    <video id="webcamPulang" autoplay playsinline></video>
                    <!-- Status Kamera -->
                    <div id="cameraStatusPulang" style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b7280;">
                        Menunggu kamera...
                    </div>
                </div>
                <button class="capture-btn" id="captureBtnPulang" onclick="ambilFoto('pulang')">
                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                </button>
            </div>
            
            <div id="photoPreviewPulang" style="display: none; margin-top: 16px;">
                <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                <img id="capturedPhotoPulang" class="captured-photo" alt="Captured Photo">
                <!-- Timer Display -->
                <div id="timerDisplayPulang" style="margin-top: 10px; padding: 8px; background: #f3f4f6; border-radius: 4px; text-align: center; font-weight: bold; color: #3b82f6;">
                    Timer: 0 detik
                </div>
                <div style="margin-top: 12px;">
                    <button class="btn btn-success" id="submitBtnPulang" onclick="submitAbsensi('pulang')" disabled>Submit Absensi Pulang</button>
                    <button class="btn btn-danger" onclick="retakePhoto('pulang')">Ambil Ulang</button>
                </div>
            </div>
        </div>
        
        <div>
            <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
            <div class="map-container">
                <div class="map-placeholder" id="mapPlaceholderPulang">
                    <div style="text-align: center; padding: 50px 0;">
                        <div style="font-size: 48px;">📍</div>
                        <div style="font-weight: 500; margin-top: 8px;">Memuat peta...</div>
                        <button onclick="refreshGPS('pulang')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            🔄 Muat Ulang Peta
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="gps-status" id="gpsStatusPulang">
                <div>⏳</div>
                <div>
                    <div style="font-weight: 500;">Memuat GPS...</div>
                    <div style="font-size: 12px;">Harap tunggu</div>
                </div>
            </div>
            
            <div class="location-info">
                <div style="font-weight: 500; margin-bottom: 8px;">Detail Lokasi:</div>
                <div style="font-size: 14px;">
                    <div>📍 <span id="locationAddressPulang">Mendeteksi alamat...</span></div>
                    <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoordsPulang">-6.058908, 106.653040</span></div>
                    <div style="margin-top: 8px;">📍 Jarak dari kantor: <span id="locationDistancePulang">0 m</span></div>
                </div>
                <button onclick="refreshGPS('pulang')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; width: 100%;">
                    🔄 Refresh Lokasi GPS
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript yang sudah di-adjust -->
<script>
// ===================== KONFIGURASI =====================
const KANTOR = {
    lat: -6.058908,
    lng: 106.653040,
    nama: "Kawasan Multi Guna Estate"
};
const RADIUS_MAX = 100; // meter
const TIMEOUT = 30;     // detik

// ===================== VARIABEL =====================
let kameraMasuk = null;
let kameraPulang = null;
let lokasiMasuk = null;
let lokasiPulang = null;
let petaMasuk = null;
let petaPulang = null;
let timerMasuk = null;
let timerPulang = null;
let hitungDetikMasuk = 0;
let hitungDetikPulang = 0;
let fotoDiambilMasuk = false;
let fotoDiambilPulang = false;

// ===================== INISIALISASI =====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistem Absensi dimulai...');
    
    // Setup kamera
    startKamera('masuk');
    startKamera('pulang');
    
    // Setup peta GRATIS
    loadLeafletJS();
    
    // Auto refresh GPS
    setInterval(() => {
        if (document.getElementById('absensi-masuk').style.display !== 'none') {
            updateGPS('masuk');
        }
        if (document.getElementById('absensi-pulang').style.display !== 'none') {
            updateGPS('pulang');
        }
    }, 30000);
});

// ===================== KAMERA =====================
async function startKamera(tipe) {
    const videoId = tipe === 'masuk' ? 'webcam' : 'webcamPulang';
    const statusId = tipe === 'masuk' ? 'cameraStatus' : 'cameraStatusPulang';
    const video = document.getElementById(videoId);
    const status = document.getElementById(statusId);
    
    if (!video) return;
    
    try {
        // Update status
        if (status) status.textContent = 'Mengakses kamera...';
        
        // Stop kamera sebelumnya
        if (tipe === 'masuk' && kameraMasuk) {
            kameraMasuk.getTracks().forEach(track => track.stop());
        }
        if (tipe === 'pulang' && kameraPulang) {
            kameraPulang.getTracks().forEach(track => track.stop());
        }
        
        // Start kamera baru
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 480 }
            } 
        });
        
        video.srcObject = stream;
        
        // Simpan stream
        if (tipe === 'masuk') {
            kameraMasuk = stream;
        } else {
            kameraPulang = stream;
        }
        
        // Update status
        if (status) {
            status.textContent = '✅ Kamera siap';
            status.style.color = '#10b981';
        }
        
    } catch (error) {
        console.error('Gagal mengakses kamera:', error);
        if (status) {
            status.textContent = '❌ Gagal mengakses kamera';
            status.style.color = '#ef4444';
        }
        alert('Gagal mengakses kamera. Pastikan izin diberikan.');
    }
}

// ===================== AMBIL FOTO =====================
function ambilFoto(tipe) {
    const video = tipe === 'masuk' ? document.getElementById('webcam') : document.getElementById('webcamPulang');
    
    if (!video || !video.srcObject) {
        alert('Kamera belum siap!');
        return;
    }
    
    // Buat canvas
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    
    // Mirror effect (seperti selfie)
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Deteksi wajah sederhana
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const wajahTerdeteksi = cekWajah(imageData);
    
    if (!wajahTerdeteksi) {
        alert('Wajah tidak terdeteksi! Pastikan wajah terlihat jelas di kamera.');
        return;
    }
    
    // Set flag foto diambil
    if (tipe === 'masuk') {
        fotoDiambilMasuk = true;
    } else {
        fotoDiambilPulang = true;
    }
    
    // Tambah watermark
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
    ctx.fillRect(0, canvas.height - 40, canvas.width, 40);
    ctx.fillStyle = 'white';
    ctx.font = '14px Arial';
    
    const now = new Date();
    const waktu = now.toLocaleTimeString('id-ID');
    const tanggal = now.toLocaleDateString('id-ID');
    ctx.fillText(`Absensi ${tipe} - ${tanggal} ${waktu}`, 10, canvas.height - 20);
    
    // Tampilkan preview
    const previewId = tipe === 'masuk' ? 'photoPreview' : 'photoPreviewPulang';
    const photoId = tipe === 'masuk' ? 'capturedPhoto' : 'capturedPhotoPulang';
    
    document.getElementById(previewId).style.display = 'block';
    document.getElementById(photoId).src = canvas.toDataURL('image/jpeg', 0.9);
    
    // Mulai timer
    mulaiTimer(tipe);
    
    // Cek validasi submit
    cekValidasiSubmit(tipe);
}

function cekWajah(imageData) {
    // Deteksi sederhana: cek apakah gambar memiliki kontras
    const data = imageData.data;
    let kontras = 0;
    
    for (let i = 0; i < data.length; i += 100) { // Sample setiap 100 pixel
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        
        // Jika ada perbedaan warna yang signifikan
        if (Math.abs(r - g) > 30 || Math.abs(r - b) > 30) {
            kontras++;
        }
    }
    
    return kontras > 50; // Threshold sederhana
}

// ===================== TIMER =====================
function mulaiTimer(tipe) {
    // Hentikan timer sebelumnya
    if (tipe === 'masuk' && timerMasuk) clearInterval(timerMasuk);
    if (tipe === 'pulang' && timerPulang) clearInterval(timerPulang);
    
    // Reset hitungan
    if (tipe === 'masuk') hitungDetikMasuk = 0;
    else hitungDetikPulang = 0;
    
    const timerId = tipe === 'masuk' ? 'timerDisplay' : 'timerDisplayPulang';
    const timerElement = document.getElementById(timerId);
    
    // Update timer
    if (tipe === 'masuk') {
        timerMasuk = setInterval(() => {
            hitungDetikMasuk++;
            timerElement.textContent = `Timer: ${hitungDetikMasuk} detik`;
            
            if (hitungDetikMasuk >= TIMEOUT) {
                clearInterval(timerMasuk);
                alert('⏰ Waktu habis! Silakan ambil foto ulang.');
                retakePhoto(tipe);
            }
        }, 1000);
    } else {
        timerPulang = setInterval(() => {
            hitungDetikPulang++;
            timerElement.textContent = `Timer: ${hitungDetikPulang} detik`;
            
            if (hitungDetikPulang >= TIMEOUT) {
                clearInterval(timerPulang);
                alert('⏰ Waktu habis! Silakan ambil foto ulang.');
                retakePhoto(tipe);
            }
        }, 1000);
    }
}

// ===================== PETA GRATIS =====================
function loadLeafletJS() {
    // Load CSS
    const css = document.createElement('link');
    css.rel = 'stylesheet';
    css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(css);
    
    // Load JS
    const js = document.createElement('script');
    js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    js.onload = initPeta;
    document.head.appendChild(js);
}

function initPeta() {
    // Peta untuk masuk
    petaMasuk = L.map('mapPlaceholder').setView([KANTOR.lat, KANTOR.lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(petaMasuk);
    
    // Marker kantor
    L.marker([KANTOR.lat, KANTOR.lng])
        .addTo(petaMasuk)
        .bindPopup(`<b>${KANTOR.nama}</b><br>Radius absensi: ${RADIUS_MAX}m`)
        .openPopup();
    
    // Circle radius
    L.circle([KANTOR.lat, KANTOR.lng], {
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        radius: RADIUS_MAX
    }).addTo(petaMasuk);
    
    // Peta untuk pulang
    petaPulang = L.map('mapPlaceholderPulang').setView([KANTOR.lat, KANTOR.lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(petaPulang);
    
    L.marker([KANTOR.lat, KANTOR.lng])
        .addTo(petaPulang)
        .bindPopup(`<b>${KANTOR.nama}</b>`);
    
    L.circle([KANTOR.lat, KANTOR.lng], {
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        radius: RADIUS_MAX
    }).addTo(petaPulang);
    
    // Update GPS setelah peta siap
    updateGPS('masuk');
    updateGPS('pulang');
}

// ===================== GPS & LOKASI =====================
function updateGPS(tipe) {
    if (!navigator.geolocation) {
        updateGPSStatus(tipe, false, 'Browser tidak support GPS');
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        (pos) => successGPS(pos, tipe),
        (err) => errorGPS(err, tipe),
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function refreshGPS(tipe) {
    const statusId = tipe === 'masuk' ? 'gpsStatusMasuk' : 'gpsStatusPulang';
    const status = document.getElementById(statusId);
    
    if (status) {
        status.innerHTML = `
            <div>🔄</div>
            <div>
                <div style="font-weight: 500;">Memuat ulang...</div>
                <div style="font-size: 12px;">Harap tunggu</div>
            </div>
        `;
        status.style.background = '#fef3c7';
    }
    
    updateGPS(tipe);
}

function successGPS(pos, tipe) {
    const lat = pos.coords.latitude;
    const lng = pos.coords.longitude;
    
    // Simpan lokasi
    if (tipe === 'masuk') {
        lokasiMasuk = { lat, lng };
    } else {
        lokasiPulang = { lat, lng };
    }
    
    // Update peta
    updatePeta(tipe, lat, lng);
    
    // Update info lokasi
    updateInfoLokasi(tipe, lat, lng);
    
    // Update status GPS
    updateGPSStatus(tipe, true, 'Lokasi terdeteksi');
    
    // Cek validasi submit
    cekValidasiSubmit(tipe);
}

function errorGPS(err, tipe) {
    console.error('GPS Error:', err.message);
    updateGPSStatus(tipe, false, err.message);
    
    // Fallback ke koordinat kantor
    if (tipe === 'masuk') {
        lokasiMasuk = { lat: KANTOR.lat, lng: KANTOR.lng };
    } else {
        lokasiPulang = { lat: KANTOR.lat, lng: KANTOR.lng };
    }
    
    cekValidasiSubmit(tipe);
}

function updatePeta(tipe, lat, lng) {
    const peta = tipe === 'masuk' ? petaMasuk : petaPulang;
    if (!peta) return;
    
    // Hapus marker user sebelumnya
    peta.eachLayer((layer) => {
        if (layer instanceof L.Marker && layer.options.title === 'Lokasi Anda') {
            peta.removeLayer(layer);
        }
    });
    
    // Tambah marker baru
    L.marker([lat, lng], {
        title: 'Lokasi Anda',
        icon: L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41]
        })
    }).addTo(peta).bindPopup(`<b>📍 Lokasi Anda</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);
}

function updateInfoLokasi(tipe, lat, lng) {
    // Hitung jarak
    const jarak = hitungJarak(lat, lng, KANTOR.lat, KANTOR.lng);
    
    // Update elemen
    const coordsId = tipe === 'masuk' ? 'locationCoordsMasuk' : 'locationCoordsPulang';
    const distanceId = tipe === 'masuk' ? 'locationDistanceMasuk' : 'locationDistancePulang';
    const addressId = tipe === 'masuk' ? 'locationAddressMasuk' : 'locationAddressPulang';
    
    document.getElementById(coordsId).textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    if (jarak <= RADIUS_MAX) {
        document.getElementById(distanceId).innerHTML = `<span style="color:#10b981;">${jarak} m (Dalam radius)</span>`;
    } else {
        document.getElementById(distanceId).innerHTML = `<span style="color:#ef4444;">${jarak} m (Di luar radius)</span>`;
    }
    
    // Alamat sederhana
    document.getElementById(addressId).textContent = `Koordinat: ${lat.toFixed(4)}°, ${lng.toFixed(4)}°`;
}

function updateGPSStatus(tipe, sukses, pesan) {
    const statusId = tipe === 'masuk' ? 'gpsStatusMasuk' : 'gpsStatusPulang';
    const status = document.getElementById(statusId);
    
    if (!status) return;
    
    if (sukses) {
        status.innerHTML = `
            <div>✅</div>
            <div>
                <div style="font-weight: 500;">GPS Aktif</div>
                <div style="font-size: 12px;">${pesan}</div>
            </div>
        `;
        status.style.background = '#f0fdf4';
        status.style.borderColor = '#bbf7d0';
    } else {
        status.innerHTML = `
            <div>❌</div>
            <div>
                <div style="font-weight: 500;">GPS Error</div>
                <div style="font-size: 12px;">${pesan}</div>
            </div>
        `;
        status.style.background = '#fee2e2';
        status.style.borderColor = '#fecaca';
    }
}

function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Radius bumi dalam meter
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return Math.round(R * c);
}

// ===================== VALIDASI & SUBMIT =====================
function cekValidasiSubmit(tipe) {
    const lokasi = tipe === 'masuk' ? lokasiMasuk : lokasiPulang;
    const fotoDiambil = tipe === 'masuk' ? fotoDiambilMasuk : fotoDiambilPulang;
    const submitBtn = document.getElementById(tipe === 'masuk' ? 'submitBtnMasuk' : 'submitBtnPulang');
    
    if (!submitBtn) return;
    
    if (!lokasi || !fotoDiambil) {
        submitBtn.disabled = true;
        submitBtn.title = 'Belum memenuhi syarat';
        return;
    }
    
    const jarak = hitungJarak(lokasi.lat, lokasi.lng, KANTOR.lat, KANTOR.lng);
    const dalamRadius = jarak <= RADIUS_MAX;
    
    if (dalamRadius && fotoDiambil) {
        submitBtn.disabled = false;
        submitBtn.title = 'Kirim absensi';
    } else {
        submitBtn.disabled = true;
        if (!dalamRadius) {
            submitBtn.title = `Anda ${jarak}m dari kantor (maks: ${RADIUS_MAX}m)`;
        } else {
            submitBtn.title = 'Belum mengambil foto';
        }
    }
}

async function submitAbsensi(tipe) {
    const lokasi = tipe === 'masuk' ? lokasiMasuk : lokasiPulang;
    const fotoDiambil = tipe === 'masuk' ? fotoDiambilMasuk : fotoDiambilPulang;
    
    if (!lokasi || !fotoDiambil) {
        alert('Data belum lengkap!');
        return;
    }
    
    // Hitung jarak
    const jarak = hitungJarak(lokasi.lat, lokasi.lng, KANTOR.lat, KANTOR.lng);
    
    if (jarak > RADIUS_MAX) {
        alert(`❌ Anda ${jarak}m dari kantor. Maksimal ${RADIUS_MAX}m.`);
        return;
    }
    
    // Konfirmasi
    const konfirm = confirm(
        `Konfirmasi Absensi ${tipe.toUpperCase()}?\n\n` +
        `📍 Jarak: ${jarak} m dari kantor\n` +
        `⏰ Waktu: ${new Date().toLocaleTimeString('id-ID')}\n` +
        `📅 Tanggal: ${new Date().toLocaleDateString('id-ID')}`
    );
    
    if (!konfirm) return;
    
    // Tampilkan loading
    const submitBtn = document.getElementById(tipe === 'masuk' ? 'submitBtnMasuk' : 'submitBtnPulang');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Menyimpan...';
    submitBtn.disabled = true;
    
    try {
        // Simpan data
        await simpanData(tipe, lokasi);
        
        // Hentikan timer
        if (tipe === 'masuk') clearInterval(timerMasuk);
        else clearInterval(timerPulang);
        
        // Reset form
        retakePhoto(tipe);
        
        // Tampilkan sukses
        alert(`✅ Absensi ${tipe} berhasil!`);
        
        // Update UI jika masuk
        if (tipe === 'masuk') {
            document.getElementById('waktuMasuk').textContent = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Gagal menyimpan. Coba lagi.');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

async function simpanData(tipe, lokasi) {
    // Simulasi API call
    return new Promise((resolve) => {
        setTimeout(() => {
            // Simpan ke localStorage
            const data = {
                tipe: tipe,
                waktu: new Date().toISOString(),
                latitude: lokasi.lat,
                longitude: lokasi.lng,
                jarak: hitungJarak(lokasi.lat, lokasi.lng, KANTOR.lat, KANTOR.lng)
            };
            
            const key = `absensi_${tipe}_${Date.now()}`;
            localStorage.setItem(key, JSON.stringify(data));
            
            console.log('Data disimpan:', data);
            resolve(true);
        }, 1500);
    });
}

// ===================== FUNGSI BANTU =====================
function retakePhoto(tipe) {
    if (tipe === 'masuk') {
        fotoDiambilMasuk = false;
        clearInterval(timerMasuk);
        document.getElementById('photoPreview').style.display = 'none';
    } else {
        fotoDiambilPulang = false;
        clearInterval(timerPulang);
        document.getElementById('photoPreviewPulang').style.display = 'none';
    }
}

// Export fungsi untuk onclick
window.retakePhoto = () => retakePhoto('masuk');
window.retakePhotoPulang = () => retakePhoto('pulang');
window.submitAttendance = submitAbsensi; // Untuk compatibility
</script>

<!-- CSS Tambahan -->
<style>
.camera-container {
    position: relative;
    width: 100%;
    height: 300px;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

#webcam, #webcamPulang {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1); /* Mirror effect */
}

.capture-btn {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    border: 4px solid #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.captured-photo {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 8px;
    border: 2px solid #10b981;
    background: #000;
}

.map-container {
    height: 300px;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.gps-status {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    margin: 15px 0;
    border: 1px solid #e5e7eb;
}

.location-info {
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-top: 15px;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    margin-right: 10px;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.alert-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
}
</style>
                
                <!-- Pengajuan Izin -->
                <div class="page-content" id="absensi-izin">
                    <div class="content-title">Pengajuan Izin</div>
                    <p class="content-description">Ajukan izin tidak masuk kerja dengan alasan yang jelas</p>
                    
                    <form id="formIzin">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal Izin</label>
                                <input type="date" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Jenis Izin</label>
                                <select class="form-control" required>
                                    <option value="">Pilih jenis izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="urusan_keluarga">Urusan Keluarga</option>
                                    <option value="urusan_pribadi">Urusan Pribadi</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Alasan Izin</label>
                            <textarea class="form-control" rows="4" placeholder="Jelaskan alasan izin secara detail" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Upload Bukti (Opsional)</label>
                            <input type="file" class="form-control" accept="image/*,.pdf">
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Surat dokter, foto, atau dokumen pendukung</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajukan Izin</button>
                    </form>
                </div>
                
                <!-- Pengajuan Cuti -->
                <div class="page-content" id="absensi-cuti">
                    <div class="content-title">Pengajuan Cuti</div>
                    <p class="content-description">Ajukan cuti tahunan, melahirkan, atau khusus</p>
                    
                    <form id="formCuti">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Jenis Cuti</label>
                            <select class="form-control" required>
                                <option value="">Pilih jenis cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="melahirkan">Cuti Melahirkan</option>
                                <option value="besar">Cuti Besar</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Cuti Alasan Penting</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Alasan Cuti</label>
                            <textarea class="form-control" rows="4" placeholder="Jelaskan alasan cuti secara detail" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Upload Dokumen Pendukung</label>
                            <input type="file" class="form-control" accept="image/*,.pdf" multiple>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Surat dokter, surat keterangan, atau dokumen lainnya</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
                    </form>
                </div>
                
                <!-- Rekap Harian -->
                <div class="page-content" id="rekap-harian">
                    <div class="content-title">Rekap Absensi Harian</div>
                    <p class="content-description">Data absensi seluruh karyawan hari ini</p>
                    
                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <input type="date" class="form-control" style="width: 200px;" value="2025-12-24">
                        <select class="form-control" style="width: 200px;">
                            <option value="all">Semua Departemen</option>
                            <option value="it">IT</option>
                            <option value="hrd">HRD</option>
                            <option value="finance">Finance</option>
                            <option value="marketing">Marketing</option>
                        </select>
                        <button class="btn btn-primary">Filter</button>
                        <button class="btn btn-success">Export Excel</button>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                                <th>Lokasi Masuk</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Wijaya</td>
                                <td>IT</td>
                                <td>07:45</td>
                                <td>17:15</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('ahmad')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Siti Rahma</td>
                                <td>HRD</td>
                                <td>08:05</td>
                                <td>-</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('siti')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>Finance</td>
                                <td>08:31</td>
                                <td>-</td>
                                <td><span class="status-badge status-late">Terlambat</span></td>
                                <td>Kantor Cabang</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('budi')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Dewi Anggraini</td>
                                <td>Marketing</td>
                                <td>07:50</td>
                                <td>17:05</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('dewi')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Rudi Hartono</td>
                                <td>IT</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="status-badge status-absent">Tidak Hadir</span></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Rekap Bulanan -->
                <div class="page-content" id="rekap-bulanan">
                    <div class="content-title">Rekap Absensi Bulanan</div>
                    <p class="content-description">Statistik absensi bulan Desember 2025</p>
                    
                    <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                        <select class="form-control" style="width: 200px;">
                            <option value="12">Desember 2025</option>
                            <option value="11">November 2025</option>
                            <option value="10">Oktober 2025</option>
                        </select>
                        <select class="form-control" style="width: 200px;">
                            <option value="all">Semua Departemen</option>
                            <option value="it">IT</option>
                            <option value="hrd">HRD</option>
                            <option value="finance">Finance</option>
                        </select>
                        <button class="btn btn-primary">Tampilkan</button>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Hadir</th>
                                <th>Terlambat</th>
                                <th>Izin</th>
                                <th>Cuti</th>
                                <th>Alpha</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Wijaya</td>
                                <td>20</td>
                                <td>2</td>
                                <td>1</td>
                                <td>0</td>
                                <td>0</td>
                                <td>95%</td>
                            </tr>
                            <tr>
                                <td>Siti Rahma</td>
                                <td>19</td>
                                <td>1</td>
                                <td>2</td>
                                <td>0</td>
                                <td>1</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>18</td>
                                <td>5</td>
                                <td>1</td>
                                <td>1</td>
                                <td>0</td>
                                <td>85%</td>
                            </tr>
                            <tr>
                                <td>Dewi Anggraini</td>
                                <td>22</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>100%</td>
                            </tr>
                            <tr>
                                <td>Rudi Hartono</td>
                                <td>17</td>
                                <td>3</td>
                                <td>2</td>
                                <td>0</td>
                                <td>1</td>
                                <td>80%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const menuItems = document.querySelectorAll('.menu-item');
            const pageContents = document.querySelectorAll('.page-content');
            const mainPageTitle = document.getElementById('mainPageTitle');
            const mainPageSubtitle = document.getElementById('mainPageSubtitle');
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
            
            // Handle menu item clicks
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const pageId = this.getAttribute('data-page');
                    
                    // Handle logout
                    if (pageId === 'logout') {
                        if (confirm('Apakah Anda yakin ingin logout?')) {
                            alert('Logout berhasil!');
                            // window.location.href = '/login';
                        }
                        return;
                    }
                    
                    // Remove active class from all menu items
                    menuItems.forEach(menuItem => {
                        menuItem.classList.remove('active');
                    });
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Show corresponding page content
                    showPageContent(pageId);
                });
            });
            
            // Function to show page content
            function showPageContent(pageId) {
                // Hide all page contents
                pageContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show selected page content
                const targetPage = document.getElementById(pageId);
                if (targetPage) {
                    targetPage.classList.add('active');
                    
                    // Update page title and subtitle
                    switch(pageId) {
                        case 'dashboard-home':
                            mainPageTitle.textContent = 'Dashboard Absensi';
                            mainPageSubtitle.textContent = 'Selamat datang! Sistem absensi dengan GPS dan face recognition';
                            break;
                        case 'absensi-masuk':
                            mainPageTitle.textContent = 'Absensi Masuk';
                            mainPageSubtitle.textContent = 'Lakukan absensi masuk dengan foto wajah dan GPS';
                            initWebcam('webcam', 'captureBtn', 'capturedPhoto', 'photoPreview');
                            break;
                        case 'absensi-pulang':
                            mainPageTitle.textContent = 'Absensi Pulang';
                            mainPageSubtitle.textContent = 'Lakukan absensi pulang dengan foto wajah dan GPS';
                            initWebcam('webcamPulang', 'captureBtnPulang', 'capturedPhotoPulang', 'photoPreviewPulang');
                            break;
                        case 'absensi-izin':
                            mainPageTitle.textContent = 'Pengajuan Izin';
                            mainPageSubtitle.textContent = 'Ajukan izin tidak masuk kerja dengan alasan yang jelas';
                            break;
                        case 'absensi-cuti':
                            mainPageTitle.textContent = 'Pengajuan Cuti';
                            mainPageSubtitle.textContent = 'Ajukan cuti tahunan, melahirkan, atau khusus';
                            break;
                        case 'rekap-harian':
                            mainPageTitle.textContent = 'Rekap Absensi Harian';
                            mainPageSubtitle.textContent = 'Data absensi seluruh karyawan hari ini';
                            break;
                        case 'rekap-bulanan':
                            mainPageTitle.textContent = 'Rekap Absensi Bulanan';
                            mainPageSubtitle.textContent = 'Statistik absensi bulan Desember 2025';
                            break;
                        case 'monitoring-live':
                            mainPageTitle.textContent = 'Monitoring Live';
                            mainPageSubtitle.textContent = 'Pantau absensi karyawan secara real-time';
                            break;
                        case 'laporan-absensi':
                            mainPageTitle.textContent = 'Laporan Absensi';
                            mainPageSubtitle.textContent = 'Laporan lengkap absensi karyawan';
                            break;
                        case 'lokasi-kantor':
                            mainPageTitle.textContent = 'Lokasi Kantor';
                            mainPageSubtitle.textContent = 'Kelola lokasi kantor untuk validasi absensi';
                            break;
                        case 'jam-kerja':
                            mainPageTitle.textContent = 'Jam Kerja';
                            mainPageSubtitle.textContent = 'Atur jadwal jam kerja perusahaan';
                            break;
                        case 'profile':
                            mainPageTitle.textContent = 'Profile';
                            mainPageSubtitle.textContent = 'Kelola profil dan pengaturan akun';
                            break;
                    }
                }
            }
            
            // Webcam functionality
            function initWebcam(videoId, captureBtnId, capturedPhotoId, previewId) {
                const video = document.getElementById(videoId);
                const captureBtn = document.getElementById(captureBtnId);
                const capturedPhoto = document.getElementById(capturedPhotoId);
                const photoPreview = document.getElementById(previewId);
                
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function(stream) {
                            video.srcObject = stream;
                        })
                        .catch(function(error) {
                            console.error("Error accessing webcam:", error);
                            video.style.display = 'none';
                            
                            // Show placeholder if webcam fails
                            const container = video.parentElement;
                            container.innerHTML = `
                                <div style="height: 480px; display: flex; align-items: center; justify-content: center; background-color: #1e293b; color: white; border-radius: 12px;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 48px;">📷</div>
                                        <div style="margin-top: 16px; font-weight: 500;">Webcam tidak tersedia</div>
                                        <div style="margin-top: 8px; font-size: 14px;">Silakan gunakan perangkat dengan webcam</div>
                                    </div>
                                </div>
                            `;
                        });
                }
                
                if (captureBtn) {
                    captureBtn.onclick = function() {
                        // Create canvas to capture photo
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        const context = canvas.getContext('2d');
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        
                        // Convert to data URL
                        const dataURL = canvas.toDataURL('image/png');
                        capturedPhoto.src = dataURL;
                        
                        // Show preview
                        photoPreview.style.display = 'block';
                        
                        // Stop webcam stream
                        const stream = video.srcObject;
                        if (stream) {
                            const tracks = stream.getTracks();
                            tracks.forEach(track => track.stop());
                        }
                    };
                }
            }
            
            // Initialize webcam for masuk page
            if (document.getElementById('absensi-masuk').classList.contains('active')) {
                initWebcam('webcam', 'captureBtn', 'capturedPhoto', 'photoPreview');
            }
            
            // Form submission handlers
            const formIzin = document.getElementById('formIzin');
            if (formIzin) {
                formIzin.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Ajukan izin?')) {
                        alert('Izin berhasil diajukan! Menunggu persetujuan atasan.');
                        this.reset();
                    }
                });
            }
            
            const formCuti = document.getElementById('formCuti');
            if (formCuti) {
                formCuti.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Ajukan cuti?')) {
                        alert('Cuti berhasil diajukan! Menunggu persetujuan atasan.');
                        this.reset();
                    }
                });
            }
            
            // Mobile responsive
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                }
            }
            
            handleResize();
            window.addEventListener('resize', handleResize);
        });
        
        // Global functions
        function submitAttendance(type) {
            if (type === 'masuk') {
                alert('Absensi masuk berhasil! Waktu: ' + new Date().toLocaleTimeString());
            } else if (type === 'pulang') {
                alert('Absensi pulang berhasil! Waktu: ' + new Date().toLocaleTimeString());
            }
            
            // Simulate redirect to dashboard
            document.querySelector('[data-page="dashboard-home"]').click();
        }
        
        function retakePhoto() {
            document.getElementById('photoPreview').style.display = 'none';
            location.reload(); // Simple reload for demo
        }
        
        function retakePhotoPulang() {
            document.getElementById('photoPreviewPulang').style.display = 'none';
            location.reload(); // Simple reload for demo
        }
        
        function showPhoto(name) {
            alert(`Menampilkan foto absensi ${name}`);
        }
        
        // Simulate GPS data
        function simulateGPSData() {
            const locations = [
                { address: "Kantor Pusat, Jl. Sudirman No. 123, Jakarta", coords: "-6.2088, 106.8456", distance: "0.2 km" },
                { address: "Kantor Cabang, Jl. Thamrin No. 45, Jakarta", coords: "-6.1865, 106.8232", distance: "1.5 km" },
                { address: "Kantor Cabang 2, Jl. Gatot Subroto No. 67, Jakarta", coords: "-6.2212, 106.8193", distance: "2.1 km" }
            ];
            
            const randomLocation = locations[Math.floor(Math.random() * locations.length)];
            
            // Update location info
            document.querySelectorAll('#locationAddress, #locationAddressPulang').forEach(el => {
                el.textContent = randomLocation.address;
            });
            
            document.querySelectorAll('#locationCoords, #locationCoordsPulang').forEach(el => {
                el.textContent = randomLocation.coords;
            });
            
            document.querySelectorAll('#locationDistance, #locationDistancePulang').forEach(el => {
                el.textContent = randomLocation.distance;
            });
        }
        
        // Initialize GPS data
        setTimeout(simulateGPSData, 1000);
        
        // Update GPS status periodically
        setInterval(() => {
            const gpsElements = document.querySelectorAll('.gps-status');
            gpsElements.forEach(el => {
                el.className = 'gps-status gps-active';
                el.innerHTML = `
                    <div>✅</div>
                    <div>
                        <div style="font-weight: 500;">GPS Aktif</div>
                        <div style="font-size: 12px;">Lokasi terdeteksi</div>
                    </div>
                `;
            });
        }, 30000);
    </script>
</body>
</html>