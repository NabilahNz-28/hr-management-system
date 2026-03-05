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
    
<link rel="stylesheet" href="/css/style.css">
     
</head>

<body>
    <!-- MOBILE MENU BUTTON -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    
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
                
                <div class="menu-item" data-page="monitoring-live">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <div class="menu-text">Monitoring Live</div>
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
                        <div class="stat-title">Hadir Hari Ini</div>
                        <div class="stat-icon icon-present">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">85%</div>
                    <div class="stat-change positive">102 dari 120 karyawan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Terlambat Hari Ini</div>
                        <div class="stat-icon icon-late">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">12%</div>
                    <div class="stat-change warning">14 karyawan terlambat</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Tidak Hadir</div>
                        <div class="stat-icon icon-absent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">3%</div>
                    <div class="stat-change negative">4 karyawan absen</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Online Sekarang</div>
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
                    <div class="stat-value">94%</div>
                    <div class="stat-change positive">113 karyawan online</div>
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
                                </div>
                                <button class="capture-btn" id="captureBtn">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                                </button>
                            </div>
                            
                            <div id="photoPreview" style="display: none; margin-top: 16px;">
                                <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                                <img id="capturedPhoto" class="captured-photo" alt="Captured Photo">
                                <div style="margin-top: 12px;">
                                    <button class="btn btn-success" onclick="submitAttendance('masuk')">Submit Absensi Masuk</button>
                                    <button class="btn btn-danger" onclick="retakePhoto()">Ambil Ulang</button>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
                            <div class="map-container">
                                <div class="map-placeholder" id="mapPlaceholder">
                                    <div style="text-align: center;">
                                        <div style="font-size: 48px;">📍</div>
                                        <div style="font-weight: 500; margin-top: 8px;">Memuat peta...</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="gps-status gps-active" id="gpsStatus">
                                <div>✅</div>
                                <div>
                                    <div style="font-weight: 500;">GPS Aktif</div>
                                    <div style="font-size: 12px;">Lokasi terdeteksi</div>
                                </div>
                            </div>
                            
                            <div class="location-info">
                                <div style="font-weight: 500; margin-bottom: 8px;">Detail Lokasi:</div>
                                <div style="font-size: 14px;">
                                    <div>📍 <span id="locationAddress">Kantor Pusat, Jl. Sudirman No. 123, Jakarta</span></div>
                                    <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoords">-6.2088, 106.8456</span></div>
                                    <div style="margin-top: 8px;">📍 Jarak dari kantor: <span id="locationDistance">0.2 km</span></div>
                                </div>
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
                            <div style="font-size: 13px;">Waktu masuk: 08:15 | Anda dapat absen pulang mulai pukul 16:30</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
                        <div>
                            <div class="content-title" style="font-size: 16px;">Foto Wajah</div>
                            <div class="camera-container">
                                <div id="webcamContainerPulang">
                                    <video id="webcamPulang" autoplay playsinline></video>
                                </div>
                                <button class="capture-btn" id="captureBtnPulang">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                                </button>
                            </div>
                            
                            <div id="photoPreviewPulang" style="display: none; margin-top: 16px;">
                                <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                                <img id="capturedPhotoPulang" class="captured-photo" alt="Captured Photo">
                                <div style="margin-top: 12px;">
                                    <button class="btn btn-success" onclick="submitAttendance('pulang')">Submit Absensi Pulang</button>
                                    <button class="btn btn-danger" onclick="retakePhotoPulang()">Ambil Ulang</button>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
                            <div class="map-container">
                                <div class="map-placeholder" id="mapPlaceholderPulang">
                                    <div style="text-align: center;">
                                        <div style="font-size: 48px;">📍</div>
                                        <div style="font-weight: 500; margin-top: 8px;">Memuat peta...</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="gps-status gps-active" id="gpsStatusPulang">
                                <div>✅</div>
                                <div>
                                    <div style="font-weight: 500;">GPS Aktif</div>
                                    <div style="font-size: 12px;">Lokasi terdeteksi</div>
                                </div>
                            </div>
                            
                            <div class="location-info">
                                <div style="font-weight: 500; margin-bottom: 8px;">Detail Lokasi:</div>
                                <div style="font-size: 14px;">
                                    <div>📍 <span id="locationAddressPulang">Kantor Pusat, Jl. Sudirman No. 123, Jakarta</span></div>
                                    <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoordsPulang">-6.2088, 106.8456</span></div>
                                    <div style="margin-top: 8px;">📍 Jarak dari kantor: <span id="locationDistancePulang">0.2 km</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                        
                        <div class="alert-box alert-warning">
                            <div style="font-size: 24px;">📋</div>
                            <div>
                                <div style="font-weight: 500;">Sisa Cuti Tahunan Anda: 12 hari</div>
                                <div style="font-size: 13px;">Pengajuan cuti membutuhkan persetujuan atasan langsung</div>
                            </div>
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
    

     <script src="js/script.js"></script>


</body>
</html>