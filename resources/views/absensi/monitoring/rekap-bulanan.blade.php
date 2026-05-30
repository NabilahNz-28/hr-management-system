@extends('layouts.absen')

@section('title', 'Rekap Bulanan Personal')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="rekap-bulanan-personal">
        <div class="content-title">Rekap Absensi Bulanan Saya</div>
        <p class="content-description">Statistik absensi pribadi Anda per bulan</p>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Bulan</label>
                <select id="bulan" class="form-control">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12" selected>Desember</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Tahun</label>
                <select id="tahun" class="form-control">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            
            <button class="btn-filter" onclick="filterData()">Tampilkan</button>
            <button id="btnExportExcel" class="btn-filter" style="background: #10b981;">Export Excel</button>
        </div>
        
        <!-- Profile Ringkasan -->
        <div class="profile-info-card" style="margin-bottom: 20px;">
            <div class="profile-info-grid-laporan">
                <div class="info-item">
                    <span class="info-label">Nama</span>
                    <span class="info-value" id="profile-nama">Ahmad Wijaya</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIK</span>
                    <span class="info-value" id="profile-nik">001</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Departemen</span>
                    <span class="info-value" id="profile-departemen">IT</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value" id="profile-jabatan">Staff IT</span>
                </div>
            </div>
        </div>
        
        <!-- Result Container -->
        <div id="resultContainer">
            <!-- Tabel hasil filter akan muncul di sini -->
        </div>
    </div>
</div>

<script>
    // Data profile karyawan yang login
    const profileKaryawan = {
        nama: 'Ahmad Wijaya',
        nik: '001',
        departemen: 'IT',
        jabatan: 'Staff IT'
    };

    // Data dummy rekap absensi personal (bulanan)
    const dataRekapPersonal = {
        '2024': {
            '01': { hadir: 18, terlambat: 3, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '02': { hadir: 16, terlambat: 2, izin: 1, cuti: 0, libur: 9, total_hari: 28 },
            '03': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '04': { hadir: 19, terlambat: 2, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '05': { hadir: 18, terlambat: 2, izin: 1, cuti: 1, libur: 8, total_hari: 30 },
            '06': { hadir: 19, terlambat: 1, izin: 1, cuti: 0, libur: 9, total_hari: 30 },
            '07': { hadir: 21, terlambat: 0, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '08': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '09': { hadir: 19, terlambat: 1, izin: 1, cuti: 0, libur: 9, total_hari: 30 },
            '10': { hadir: 21, terlambat: 0, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '11': { hadir: 17, terlambat: 2, izin: 1, cuti: 1, libur: 9, total_hari: 30 },
            '12': { hadir: 18, terlambat: 2, izin: 1, cuti: 0, libur: 9, total_hari: 30 }
        },
        '2025': {
            '01': { hadir: 19, terlambat: 2, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '02': { hadir: 17, terlambat: 1, izin: 1, cuti: 0, libur: 9, total_hari: 28 },
            '03': { hadir: 21, terlambat: 1, izin: 1, cuti: 0, libur: 7, total_hari: 30 },
            '04': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '05': { hadir: 19, terlambat: 2, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '06': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '07': { hadir: 22, terlambat: 0, izin: 1, cuti: 0, libur: 7, total_hari: 30 },
            '08': { hadir: 21, terlambat: 1, izin: 1, cuti: 0, libur: 7, total_hari: 30 },
            '09': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '10': { hadir: 22, terlambat: 0, izin: 1, cuti: 0, libur: 7, total_hari: 30 },
            '11': { hadir: 18, terlambat: 2, izin: 1, cuti: 1, libur: 8, total_hari: 30 },
            '12': { hadir: 19, terlambat: 2, izin: 1, cuti: 0, libur: 8, total_hari: 30 }
        },
        '2026': {
            '01': { hadir: 20, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 30 },
            '02': { hadir: 18, terlambat: 1, izin: 1, cuti: 0, libur: 8, total_hari: 28 },
            '03': { hadir: 22, terlambat: 0, izin: 1, cuti: 0, libur: 7, total_hari: 30 }
        }
    };

    // Nama bulan
    const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Update profile di halaman
    function updateProfile() {
        document.getElementById('profile-nama').textContent = profileKaryawan.nama;
        document.getElementById('profile-nik').textContent = profileKaryawan.nik;
        document.getElementById('profile-departemen').textContent = profileKaryawan.departemen;
        document.getElementById('profile-jabatan').textContent = profileKaryawan.jabatan;
    }

    // Render tabel
    function renderTable(data, bulan, tahun) {
        const container = document.getElementById('resultContainer');
        
        if (!data) {
            container.innerHTML = `
                <div class="success-message" style="background: #fef3c7; border-color: #fbbf24;">
                    <div class="emoji-big" style="font-size: 3rem;">📭</div>
                    <h3 style="color: #92400e;">Belum Ada Data</h3>
                    <p style="color: #92400e;">Data rekap untuk ${namaBulan[parseInt(bulan)-1]} ${tahun} belum tersedia.</p>
                </div>
            `;
            return;
        }
        
        const persentaseHadir = Math.round((data.hadir / data.total_hari) * 100);
        const persentaseTerlambat = Math.round((data.terlambat / data.total_hari) * 100);
        
        let progressColor = persentaseHadir >= 80 ? '#10b981' : (persentaseHadir >= 60 ? '#f59e0b' : '#ef4444');
        
        let tabelHTML = `
            <div class="info-summary" style="margin-bottom: 20px; justify-content: center;">
                <p>Periode: <strong>${namaBulan[parseInt(bulan)-1]} ${tahun}</strong></p>
                <p>Total Hari Kerja: <strong>${data.total_hari}</strong> hari</p>
            </div>
            
            <div class="rekap-stats" style="margin-bottom: 24px;">
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #10b981;">${data.hadir}</div>
                    <div class="rekap-label">Hadir</div>
                    <div class="rekap-unit">hari</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #f59e0b;">${data.terlambat}</div>
                    <div class="rekap-label">Terlambat</div>
                    <div class="rekap-unit">kali</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #8b5cf6;">${data.izin}</div>
                    <div class="rekap-label">Izin</div>
                    <div class="rekap-unit">hari</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #3b82f6;">${data.cuti}</div>
                    <div class="rekap-label">Cuti</div>
                    <div class="rekap-unit">hari</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #6c7e97;">${data.libur}</div>
                    <div class="rekap-label">Libur</div>
                    <div class="rekap-unit">hari</div>
                </div>
            </div>
            
            <div class="summary-section" style="margin-top: 0;">
                <div class="summary-header">
                    <h3>Ringkasan Kehadiran</h3>
                </div>
                <div class="progress-item">
                    <div class="progress-label">
                        <span>Tingkat Kehadiran</span>
                        <span>${persentaseHadir}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${persentaseHadir}%; background: ${progressColor};"></div>
                    </div>
                </div>
                <div class="summary-stats">
                    <div class="summary-stat">
                        <span class="summary-stat-label">Total Kehadiran</span>
                        <span class="summary-stat-value">${data.hadir} / ${data.total_hari}</span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label">Kedisiplinan</span>
                        <span class="summary-stat-value">${persentaseTerlambat <= 5 ? 'Baik' : (persentaseTerlambat <= 10 ? 'Cukup' : 'Perlu Perbaikan')}</span>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = tabelHTML;
    }

    // Filter data
    function filterData() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        
        let data = dataRekapPersonal[tahun]?.[bulan];
        
        renderTable(data, bulan, tahun);
        
        console.log(`Menampilkan data rekap untuk: ${namaBulan[parseInt(bulan)-1]} ${tahun}`);
    }

    // Export ke Excel
    function exportToExcel() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const data = dataRekapPersonal[tahun]?.[bulan];
        
        if (!data) {
            alert('Tidak ada data untuk periode yang dipilih');
            return;
        }
        
        const persentaseHadir = Math.round((data.hadir / data.total_hari) * 100);
        
        let excelContent = `
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Rekap Absensi Bulanan Personal</title>
                <style>
                    th { background: #2c7da0; color: white; padding: 8px; }
                    td { padding: 6px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <h2>REKAP ABSENSI BULANAN PERSONAL</h2>
                <p>Nama: ${profileKaryawan.nama}</p>
                <p>NIK: ${profileKaryawan.nik}</p>
                <p>Departemen: ${profileKaryawan.departemen}</p>
                <p>Periode: ${namaBulan[parseInt(bulan)-1]} ${tahun}</p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Total Hari Kerja</td><td>${data.total_hari}</td></tr>
                        <tr><td>Hadir</td><td>${data.hadir}</td></tr>
                        <tr><td>Terlambat</td><td>${data.terlambat}</td></tr>
                        <tr><td>Izin</td><td>${data.izin}</td></tr>
                        <tr><td>Cuti</td><td>${data.cuti}</td></tr>
                        <tr><td>Libur</td><td>${data.libur}</td></tr>
                        <tr><td>Tingkat Kehadiran</td><td>${persentaseHadir}%</td></tr>
                    </tbody>
                </table>
            </body>
            </html>
        `;
        
        const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Rekap_Bulanan_Personal_${tahun}_${bulan}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Event listener
    document.addEventListener('DOMContentLoaded', function() {
        // Update profile
        updateProfile();
        
        // Set default bulan dan tahun
        const sekarang = new Date();
        const bulanSekarang = String(sekarang.getMonth() + 1).padStart(2, '0');
        const tahunSekarang = sekarang.getFullYear();
        
        document.getElementById('bulan').value = bulanSekarang;
        document.getElementById('tahun').value = tahunSekarang;
        
        // Render awal
        filterData();
        
        // Event listener export
        document.getElementById('btnExportExcel').addEventListener('click', exportToExcel);
    });
</script>
@endsection