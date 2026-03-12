<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Dashboard - HR Management</title>
    
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #3b82f6;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --text-main: #334155;
            --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --card-shadow-hover: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .portal-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-text h2 {
            font-weight: 700;
            color: #1e293b;
        }

        .header-text p {
            color: #64748b;
        }

        .option-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            text-decoration: none;
            color: var(--text-main);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 2px solid transparent;
            display: block;
            height: 100%;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
            border-color: var(--primary-blue);
            color: var(--text-main);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: #eff6ff;
            color: var(--primary-blue);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: var(--transition);
        }

        .option-card:hover .icon-wrapper {
            background: var(--primary-blue);
            color: white;
        }

        .option-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1e293b;
        }

        .option-desc {
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="portal-container">
        <div class="header-text">
            <h2>Selamat Datang, {{ auth()->user()->name ?? 'PIC Inventory' }}!</h2>
            <p>Silakan pilih dashboard yang ingin Anda akses</p>
        </div>

        <div class="row g-4">
            <!-- Pilihan 1: Absensi -->
            <div class="col-md-6">
                <a href="{{ route('dashboard.absensi') }}" class="option-card">
                    <div class="icon-wrapper">
                        <!-- Icon Clock/Calendar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="option-title">Dashboard Absensi</div>
                    <div class="option-desc">Kelola data kehadiran, cuti, dan jadwal absensi harian Anda di sini.</div>
                </a>
            </div>

            <!-- Pilihan 2: PIC Inventory -->
            <div class="col-md-6">
                <a href="{{ route('dashboard.pic') }}" class="option-card">
                    <div class="icon-wrapper">
                        <!-- Icon Box/Inventory -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="option-title">Dashboard Inventory</div>
                    <div class="option-desc">Kelola stok barang, permintaan aset, dan manajemen inventory kantor.</div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
