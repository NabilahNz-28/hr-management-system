<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin</title>
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
        margin: 0; padding: 0; box-sizing: border-box;
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.5; font-weight: 400; }
        
        /* SIDEBAR STYLES (Persis dari file 2 & 4) */
        #sidebar { width: var(--sidebar-width); background-color: var(--sidebar-bg); height: 100vh; position: fixed; left: 0; top: 0; transition: var(--transition); z-index: 1000; border-right: 1px solid var(--sidebar-border); box-shadow: var(--sidebar-shadow); }
        .sidebar-header { height: var(--header-height); padding: 0 24px; display: flex; align-items: center; border-bottom: 1px solid var(--sidebar-border); gap: 12px; background-color: white; }
        .logo { width: 36px; height: 36px; background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 18px; }
        .brand-text { font-size: 18px; font-weight: 600; color: #1e293b; }
        .sidebar-menu { padding: 20px 0; height: calc(100vh - var(--header-height)); overflow-y: auto; }
        .menu-section { padding: 0 16px; margin-bottom: 24px; }
        .section-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 12px; padding-left: 12px; }
        .menu-item { display: flex; align-items: center; padding: 12px 16px; color: #475569; text-decoration: none; border-radius: 8px; margin-bottom: 4px; transition: var(--transition); cursor: pointer; border-left: 3px solid transparent; }
        .menu-item:hover { background-color: var(--sidebar-hover); color: #1e293b; transform: translateX(2px); }
        .menu-item.active { background-color: #eff6ff; color: #1e293b; border-left-color: var(--sidebar-active); font-weight: 500; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1); }
        .menu-icon { width: 20px; height: 20px; margin-right: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: var(--transition); }
        .menu-item.active .menu-icon { color: var(--sidebar-active); }
        .menu-text { font-size: 14px; font-weight: 500; }

        /* MAIN CONTENT */
        #main-content { margin-left: var(--sidebar-width); transition: var(--transition); min-height: 100vh; }
        .topbar { height: var(--header-height); background-color: white; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; position: sticky; top: 0; z-index: 100; }
        .dashboard-content { padding: 24px; }
        .page-title { font-size: 24px; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        .page-subtitle { font-size: 14px; color: #64748b; margin-bottom: 24px; }

        /* CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background-color: white; border-radius: 12px; padding: 24px; box-shadow: var(--card-shadow); transition: var(--transition); border: 1px solid #e2e8f0; }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .stat-title { font-size: 14px; color: #64748b; font-weight: 500; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
        .icon-present { background: linear-gradient(135deg, #10b981, #059669); }
        .icon-online { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .icon-inventory { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-value { font-size: 28px; font-weight: 600; color: #1e293b; margin-bottom: 4px; }

        /* PAGE CONTENT ANIMATION */
        .page-content { display: none; background-color: white; border-radius: 12px; padding: 30px; box-shadow: var(--card-shadow); border: 1px solid #e2e8f0; min-height: 400px; }
        .page-content.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .content-title { font-size: 20px; font-weight: 600; color: #1e293b; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #f1f5f9; }

        /* FORM STYLES */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: #334155; margin-bottom: 8px; }
        .form-control, .form-select { width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: var(--transition); }
        .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 500; }
        .btn-black { background: #1e293b; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 500; }

        /* LIST CLICKABLE */
        .list-item { padding: 16px; border-bottom: 1px solid #e2e8f0; cursor: pointer; transition: background 0.2s; display: flex; justify-content: space-between; align-items: center; border-radius: 8px; }
        .list-item:hover { background-color: #f8fafc; }
        .badge-role { font-size: 11px; padding: 4px 8px; border-radius: 4px; background: #e2e8f0; color: #334155; text-transform: uppercase; font-weight: 600; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div id="sidebar">
        <div class="sidebar-header">
            <div class="logo">SA</div>
            <div class="brand-text">SuperAdmin</div>
        </div>
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="section-label">UTAMA</div>
                <div class="menu-item active" data-page="dashboard-home">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="section-label">MANAJEMEN</div>
                <div class="menu-item" data-page="karyawan-gaji">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="menu-text">Karyawan & Gaji</div>
                </div>
                <div class="menu-item" data-page="inventory-opname">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                    <div class="menu-text">Inventory Tipis</div>
                </div>
            </div>

            <div class="menu-section">
                <div class="section-label">PENGATURAN</div>
                <div class="menu-item" data-page="pengaturan-role">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <div class="menu-text">Tambah Role/User</div>
                </div>
                <div class="menu-item" data-page="pengaturan-gaji">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </div>
                    <div class="menu-text">Settings Gaji Global</div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <div class="topbar">
            <div style="font-weight: 600; color: #1e293b;">Superadmin Panel</div>
            <div class="user-avatar" style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #1e293b, #0f172a); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">SA</div>
        </div>

        <div class="dashboard-content">
            
            <!-- 1. DASHBOARD HOME -->
            <div id="dashboard-home" class="page-content active">
                <h1 class="page-title">Dashboard Utama</h1>
                <p class="page-subtitle">Ringkasan statistik sistem hari ini</p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Karyawan Hadir Hari Ini</div>
                            <div class="stat-icon icon-present">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ $hadirHariIni }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Jumlah Total Karyawan</div>
                            <div class="stat-icon icon-online">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ $totalKaryawan }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Estimasi Pengeluaran Gaji</div>
                            <div class="stat-icon icon-inventory">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            </div>
                        </div>
                        <div class="stat-value">Rp {{ number_format($estimasiGaji, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- 2. KARYAWAN & GAJI -->
            <div id="karyawan-gaji" class="page-content">
                <div id="view-karyawan-list">
                    <h3 class="content-title">Daftar Karyawan Terdaftar</h3>
                    <div id="karyawan-list-container">
                        <!-- Render JS -->
                    </div>
                </div>

                <div id="view-karyawan-detail" style="display: none;">
                    <button class="btn-black" style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 8px 16px; margin-bottom: 20px;" onclick="backToKaryawan()">
                        &larr; Kembali
                    </button>
                    <h3 class="content-title" id="k-name" style="border:none; padding:0; margin-bottom:5px;">Nama</h3>
                    <div class="badge-role mb-4 d-inline-block" id="k-role">Role</div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 24px;">
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 13px; color: #64748b;">Hari Kerja</div>
                            <div style="font-size: 24px; font-weight: bold; color: #1e293b;" id="k-kerja">0</div>
                        </div>
                        <div style="background: #fffbeb; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 13px; color: #64748b;">Hari Cuti</div>
                            <div style="font-size: 24px; font-weight: bold; color: #d97706;" id="k-cuti">0</div>
                        </div>
                        <div style="background: #fef2f2; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 13px; color: #64748b;">Hari Izin</div>
                            <div style="font-size: 24px; font-weight: bold; color: #dc2626;" id="k-izin">0</div>
                        </div>
                    </div>

                    <h4 style="font-size: 16px; font-weight: 600; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px;">Sistem Penggajian Bulan Ini</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 10px 0;">Gaji Pokok</td><td style="text-align: right; font-weight: 500;" id="g-pokok">0</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 10px 0;">Uang Makan <span id="g-makan-hari" style="color:#64748b; font-size:12px;"></span></td><td style="text-align: right; font-weight: 500;" id="g-makan">0</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 10px 0;">Uang Bensin <span id="g-bensin-hari" style="color:#64748b; font-size:12px;"></span></td><td style="text-align: right; font-weight: 500;" id="g-bensin">0</td></tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 10px 0;">Lembur <span id="g-lembur-jam" style="color:#64748b; font-size:12px;"></span></td><td style="text-align: right; font-weight: 500;" id="g-lembur">0</td></tr>
                        <tr><td style="padding: 15px 0; font-weight: 600; font-size: 16px;">Total Gaji Diterima</td><td style="text-align: right; font-weight: bold; font-size: 18px; color: #10b981;" id="g-total">0</td></tr>
                    </table>
                </div>
            </div>

            <!-- 3. INVENTORY OPNAME -->
            <div id="inventory-opname" class="page-content">
                <div id="view-inv-list">
                    <h3 class="content-title">Rincian Transaksi Opname</h3>
                    <div id="inv-list-container"></div>
                </div>

                <div id="view-inv-detail" style="display: none;">
                    <button class="btn-black" style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 8px 16px; margin-bottom: 20px;" onclick="backToInv()">
                        &larr; Kembali
                    </button>
                    <h3 class="content-title" id="i-name" style="border:none; padding:0; margin-bottom:5px;">Nama</h3>
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;" id="i-date">Tanggal</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0;">
                            <tr><th style="padding: 12px; text-align: left;">Nama Barang</th><th style="padding: 12px; text-align: right;">Selisih Jumlah</th></tr>
                        </thead>
                        <tbody id="inv-detail-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- 4. TAMBAH ROLE -->
            <div id="pengaturan-role" class="page-content">
                <h3 class="content-title">Penambahan Pengguna & Role</h3>
                <form action="#" method="POST" style="max-width: 600px;">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="karyawan">Karyawan</option>
                            <option value="pic">PIC</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;">
                        <input type="checkbox" name="remember" id="remember" style="width:16px; height:16px;">
                        <label for="remember" style="font-size:14px; color:#64748b;">Remember Password (Kirim info login ke email)</label>
                    </div>
                    <button type="submit" class="btn-primary w-100">Simpan Pengguna Baru</button>
                </form>
            </div>

            <!-- 5. SETTINGS GAJI -->
            <div id="pengaturan-gaji" class="page-content">
                <h3 class="content-title">Atur Standar Gaji Karyawan</h3>
                <form action="#" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Gaji Pokok Dasar</label>
                            <input type="number" name="uang_pokok" class="form-control" value="{{ $settings['uang_pokok'] }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Uang Makan / Hari</label>
                            <input type="number" name="uang_makan" class="form-control" value="{{ $settings['uang_makan'] }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Uang Bensin / Hari</label>
                            <input type="number" name="uang_bensin" class="form-control" value="{{ $settings['uang_bensin'] }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rate Lembur / Jam</label>
                            <input type="number" name="rate_lembur" class="form-control" value="{{ $settings['rate_lembur'] }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bonus Default</label>
                            <input type="number" name="bonus" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lainnya</label>
                            <input type="number" name="lainnya" class="form-control" value="0">
                        </div>
                    </div>
                    <button type="submit" class="btn-black mt-3">Simpan Pengaturan</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        // 1. INJECT DATA DARI LARAVEL KE JAVASCRIPT
        const karyawans = @json($karyawans);
        const inventories = @json($inventories);
        const settings = @json($settings);

        const formatRp = (num) => "Rp " + num.toLocaleString('id-ID');

        // 2. NAVIGASI MENU (Sama spt style aslimu)
        const menuItems = document.querySelectorAll('.menu-item');
        const pageContents = document.querySelectorAll('.page-content');

        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Update active menu
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');

                // Show target page
                const targetId = this.getAttribute('data-page');
                pageContents.forEach(page => page.classList.remove('active'));
                document.getElementById(targetId).classList.add('active');

                // Reset view if back to tab
                if(targetId === 'karyawan-gaji') backToKaryawan();
                if(targetId === 'inventory-opname') backToInv();
            });
        });

        // 3. RENDER LIST KARYAWAN
        function renderKaryawan() {
            const container = document.getElementById('karyawan-list-container');
            container.innerHTML = karyawans.map(k => `
                <div class="list-item" onclick="openKaryawan(${k.id})">
                    <div>
                        <div style="font-weight: 600; color: #1e293b;">${k.name}</div>
                        <div style="font-size: 13px; color: #64748b;">${k.email}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <span class="badge-role">${k.role}</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            `).join('');
        }

        function openKaryawan(id) {
            const k = karyawans.find(x => x.id === id);
            document.getElementById('view-karyawan-list').style.display = 'none';
            document.getElementById('view-karyawan-detail').style.display = 'block';

            document.getElementById('k-name').innerText = k.name;
            document.getElementById('k-role').innerText = k.role;
            document.getElementById('k-kerja').innerText = k.hari_kerja;
            document.getElementById('k-cuti').innerText = k.cuti;
            document.getElementById('k-izin').innerText = k.izin;

            // Hitung Gaji
            const tMakan = k.hari_kerja * settings.uang_makan;
            const tBensin = k.hari_kerja * settings.uang_bensin;
            const tLembur = k.lembur_hours * settings.rate_lembur;
            const total = settings.uang_pokok + tMakan + tBensin + tLembur;

            document.getElementById('g-pokok').innerText = formatRp(settings.uang_pokok);
            document.getElementById('g-makan-hari').innerText = `(${k.hari_kerja}x)`;
            document.getElementById('g-makan').innerText = formatRp(tMakan);
            document.getElementById('g-bensin-hari').innerText = `(${k.hari_kerja}x)`;
            document.getElementById('g-bensin').innerText = formatRp(tBensin);
            document.getElementById('g-lembur-jam').innerText = `(${k.lembur_hours} jam)`;
            document.getElementById('g-lembur').innerText = formatRp(tLembur);
            document.getElementById('g-total').innerText = formatRp(total);
        }

        function backToKaryawan() {
            document.getElementById('view-karyawan-list').style.display = 'block';
            document.getElementById('view-karyawan-detail').style.display = 'none';
        }

        // 4. RENDER LIST INVENTORY
        function renderInventory() {
            const container = document.getElementById('inv-list-container');
            container.innerHTML = inventories.map(i => `
                <div class="list-item" onclick="openInv(${i.id})">
                    <div>
                        <div style="font-weight: 600; color: #1e293b;">${i.nama_transaksi}</div>
                        <div style="font-size: 13px; color: #64748b;">Sort: ${i.tanggal}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <span style="font-size: 13px; background:#eff6ff; color:#3b82f6; padding:4px 8px; border-radius:4px; font-weight:500;">${i.jumlah_item} Items</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            `).join('');
        }

        function openInv(id) {
            const inv = inventories.find(x => x.id === id);
            document.getElementById('view-inv-list').style.display = 'none';
            document.getElementById('view-inv-detail').style.display = 'block';

            document.getElementById('i-name').innerText = inv.nama_transaksi;
            document.getElementById('i-date').innerText = "Dilakukan pada: " + inv.tanggal;
            
            document.getElementById('inv-detail-body').innerHTML = inv.items.map(item => `
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; color: #334155;">${item.nama}</td>
                    <td style="padding: 12px; text-align: right; font-weight: bold; color: ${item.selisih < 0 ? '#ef4444' : '#10b981'}">
                        ${item.selisih > 0 ? '+' : ''}${item.selisih}
                    </td>
                </tr>
            `).join('');
        }

        function backToInv() {
            document.getElementById('view-inv-list').style.display = 'block';
            document.getElementById('view-inv-detail').style.display = 'none';
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            renderKaryawan();
            renderInventory();
        });
    </script>
</body>
</html>
