@extends('layouts.absen')

@section('title', 'Dashboard Absensi')

@section('content')
<div class="dashboard-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="page-title">Selamat Datang, Ahmad Wijaya! 👋</h1>
        <p class="page-subtitle">Berikut adalah ringkasan absensi Anda pada bulan ini</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Card Hadir -->
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Hadir</span>
                <div class="stat-icon icon-present">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value" id="total-hadir">0</div>
            <div class="stat-change positive" id="hadir-change">Hari Masuk</div>
        </div>

        <!-- Card Terlambat -->
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Keterlambatan</span>
                <div class="stat-icon icon-late">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value" id="total-terlambat">0</div>
            <div class="stat-change warning" id="terlambat-change">Kali Terlambat</div>
        </div>

        <!-- Card Libur -->
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Libur</span>
                <div class="stat-icon icon-absent">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value" id="total-libur">0</div>
            <div class="stat-change" id="libur-change">Hari Libur</div>
        </div>

        <!-- Card Cuti -->
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Cuti</span>
                <div class="stat-icon icon-online">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value" id="total-cuti">0</div>
            <div class="stat-change" id="cuti-change">Hari Cuti</div>
        </div>
    </div>

    <!-- Izin Card (Full Width) -->
    <div class="izin-card">
        <div class="izin-header">
            <span class="izin-title">📋 Izin Bulan Ini</span>
        </div>
        <div class="izin-content">
            <div class="izin-value" id="total-izin">0</div>
            <div class="izin-label">Hari Izin</div>
        </div>
    </div>

    <!-- Ringkasan Bulan Ini -->
    <div class="summary-section">
        <div class="summary-header">
            <h3>Ringkasan Bulan <span id="bulan-saat-ini">Januari</span> <span id="tahun-saat-ini">2025</span></h3>
            <div class="summary-badge" id="status-bulan">Sedang Berjalan</div>
        </div>
        <div class="summary-progress">
            <div class="progress-item">
                <div class="progress-label">
                    <span>Tingkat Kehadiran</span>
                    <span id="persentase-kehadiran">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill" style="width: 0%;"></div>
                </div>
            </div>
        </div>
        <div class="summary-stats">
            <div class="summary-stat">
                <span class="summary-stat-label">Hari Kerja</span>
                <span class="summary-stat-value" id="total-hari-kerja">0</span>
            </div>
            <div class="summary-stat">
                <span class="summary-stat-label">Sisa Hari</span>
                <span class="summary-stat-value" id="sisa-hari">0</span>
            </div>
            <div class="summary-stat">
                <span class="summary-stat-label">Kehadiran</span>
                <span class="summary-stat-value" id="kehadiran-bulan">0%</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data dummy untuk bulan ini (bisa diganti dengan data dari backend)
    const dataDashboard = {
        // Data per bulan untuk tahun 2025
        '2025': {
            '01': { hadir: 21, terlambat: 3, libur: 6, cuti: 2, izin: 1, total_hari_kerja: 30 },
            '02': { hadir: 19, terlambat: 2, libur: 7, cuti: 1, izin: 1, total_hari_kerja: 28 },
            '03': { hadir: 23, terlambat: 1, libur: 5, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '04': { hadir: 22, terlambat: 1, libur: 6, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '05': { hadir: 20, terlambat: 2, libur: 7, cuti: 2, izin: 1, total_hari_kerja: 30 },
            '06': { hadir: 21, terlambat: 2, libur: 6, cuti: 2, izin: 1, total_hari_kerja: 30 },
            '07': { hadir: 23, terlambat: 0, libur: 5, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '08': { hadir: 22, terlambat: 1, libur: 6, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '09': { hadir: 21, terlambat: 1, libur: 7, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '10': { hadir: 23, terlambat: 0, libur: 5, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '11': { hadir: 19, terlambat: 2, libur: 8, cuti: 2, izin: 1, total_hari_kerja: 30 },
            '12': { hadir: 20, terlambat: 2, libur: 7, cuti: 2, izin: 1, total_hari_kerja: 30 }
        },
        '2024': {
            '01': { hadir: 20, terlambat: 4, libur: 8, cuti: 1, izin: 1, total_hari_kerja: 30 },
            '02': { hadir: 18, terlambat: 3, libur: 9, cuti: 1, izin: 0, total_hari_kerja: 28 },
            '03': { hadir: 22, terlambat: 2, libur: 6, cuti: 1, izin: 1, total_hari_kerja: 30 }
        }
    };

    // Nama bulan
    const namaBulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    function updateDashboard() {
        const sekarang = new Date();
        const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
        const tahun = sekarang.getFullYear();
        const namaBulanSekarang = namaBulan[sekarang.getMonth()];
        
        // Ambil data berdasarkan tahun dan bulan
        let data = dataDashboard[tahun]?.[bulan];
        
        // Jika data tidak ada untuk tahun ini, pakai data 2025 dengan bulan yang sama
        if (!data && dataDashboard['2025']?.[bulan]) {
            data = dataDashboard['2025'][bulan];
        }
        
        // Jika tetap tidak ada, pakai data default
        if (!data) {
            data = { hadir: 0, terlambat: 0, libur: 0, cuti: 0, izin: 0, total_hari_kerja: 30 };
        }
        
        // Hitung persentase kehadiran
        const persentase = Math.round((data.hadir / data.total_hari_kerja) * 100);
        const sisaHari = data.total_hari_kerja - (data.hadir + data.libur + data.cuti + data.izin);
        
        // Update DOM
        document.getElementById('total-hadir').textContent = data.hadir;
        document.getElementById('total-terlambat').textContent = data.terlambat;
        document.getElementById('total-libur').textContent = data.libur;
        document.getElementById('total-cuti').textContent = data.cuti;
        document.getElementById('total-izin').textContent = data.izin;
        
        // Update ringkasan
        document.getElementById('bulan-saat-ini').textContent = namaBulanSekarang;
        document.getElementById('tahun-saat-ini').textContent = tahun;
        document.getElementById('total-hari-kerja').textContent = data.total_hari_kerja;
        document.getElementById('sisa-hari').textContent = sisaHari > 0 ? sisaHari : 0;
        document.getElementById('persentase-kehadiran').textContent = persentase + '%';
        document.getElementById('kehadiran-bulan').textContent = persentase + '%';
        document.getElementById('progress-fill').style.width = persentase + '%';
        
        // Update status badge
        const statusBadge = document.getElementById('status-bulan');
        const tanggalSekarang = sekarang.getDate();
        if (tanggalSekarang <= 7) {
            statusBadge.textContent = 'Awal Bulan';
            statusBadge.style.background = '#dbeafe';
            statusBadge.style.color = '#1e40af';
        } else if (tanggalSekarang >= 25) {
            statusBadge.textContent = 'Akhir Bulan';
            statusBadge.style.background = '#fed7aa';
            statusBadge.style.color = '#9a3412';
        } else {
            statusBadge.textContent = 'Sedang Berjalan';
            statusBadge.style.background = '#d1fae5';
            statusBadge.style.color = '#065f46';
        }
        
        // Update warna pada keterlambatan
        const terlambatEl = document.getElementById('terlambat-change');
        if (data.terlambat === 0) {
            terlambatEl.innerHTML = '✨ Disiplin! ✨';
            terlambatEl.className = 'stat-change positive';
        } else if (data.terlambat <= 2) {
            terlambatEl.innerHTML = `${data.terlambat} Kali Terlambat (Perbaiki)`;
            terlambatEl.className = 'stat-change warning';
        } else {
            terlambatEl.innerHTML = `${data.terlambat} Kali Terlambat (Perhatian!)`;
            terlambatEl.className = 'stat-change negative';
        }
    }

    // Jalankan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateDashboard);
</script>
@endsection