@extends('layouts.absen')

@section('title', 'Rekap Harian Personal')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="rekap-harian-personal">
        <div class="content-title">Rekap Absensi Harian Saya</div>
        <p class="content-description">Data absensi pribadi Anda hari ini</p>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            
            <button class="btn-filter" onclick="filterData()">Filter</button>
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

    // Data dummy absensi personal (harian)
    const dataAbsensiPersonal = {
        '2025-05-01': { jam_masuk: '07:55', jam_pulang: '16:30', status: 'Hadir', keterangan: '-' },
        '2025-05-02': { jam_masuk: '07:50', jam_pulang: '16:25', status: 'Hadir', keterangan: '-' },
        '2025-05-03': { jam_masuk: '08:15', jam_pulang: '16:45', status: 'Terlambat', keterangan: 'Macet' },
        '2025-05-04': { jam_masuk: '-', jam_pulang: '-', status: 'Libur', keterangan: 'Hari Libur Nasional' },
        '2025-05-05': { jam_masuk: '07:48', jam_pulang: '16:30', status: 'Hadir', keterangan: '-' },
        '2025-05-06': { jam_masuk: '08:20', jam_pulang: '16:50', status: 'Terlambat', keterangan: 'Bangun kesiangan' },
        '2025-05-07': { jam_masuk: '07:52', jam_pulang: '16:28', status: 'Hadir', keterangan: '-' },
        '2025-05-08': { jam_masuk: '-', jam_pulang: '-', status: 'Izin', keterangan: 'Ada keperluan keluarga' },
        '2025-05-09': { jam_masuk: '07:45', jam_pulang: '16:20', status: 'Hadir', keterangan: '-' },
        '2025-05-10': { jam_masuk: '-', jam_pulang: '-', status: 'Libur', keterangan: 'Hari Libur Sabtu' },
        '2025-05-11': { jam_masuk: '-', jam_pulang: '-', status: 'Libur', keterangan: 'Hari Libur Minggu' },
        '2025-05-12': { jam_masuk: '08:05', jam_pulang: '16:35', status: 'Hadir', keterangan: '-' },
        '2025-05-13': { jam_masuk: '08:10', jam_pulang: '16:40', status: 'Hadir', keterangan: '-' },
        '2025-05-14': { jam_masuk: '07:55', jam_pulang: '16:30', status: 'Hadir', keterangan: '-' },
        '2025-05-15': { jam_masuk: '-', jam_pulang: '-', status: 'Cuti', keterangan: 'Cuti tahunan' },
        '2025-05-16': { jam_masuk: '07:50', jam_pulang: '16:25', status: 'Hadir', keterangan: '-' }
    };

    // Status badge mapping (tanpa emoji, tanpa simbol)
    function getStatusBadge(status) {
        switch(status) {
            case 'Hadir':
                return '<span class="status-badge status-present">Hadir</span>';
            case 'Terlambat':
                return '<span class="status-badge status-late">Terlambat</span>';
            case 'Absen':
                return '<span class="status-badge status-absent">Absen</span>';
            case 'Izin':
                return '<span class="status-badge status-wfh">Izin</span>';
            case 'Cuti':
                return '<span class="status-badge status-wfh">Cuti</span>';
            case 'Libur':
                return '<span class="status-badge status-early">Libur</span>';
            default:
                return '<span class="status-badge">-</span>';
        }
    }

    // Update profile di halaman
    function updateProfile() {
        document.getElementById('profile-nama').textContent = profileKaryawan.nama;
        document.getElementById('profile-nik').textContent = profileKaryawan.nik;
        document.getElementById('profile-departemen').textContent = profileKaryawan.departemen;
        document.getElementById('profile-jabatan').textContent = profileKaryawan.jabatan;
    }

    // Render tabel
    function renderTable(data, tanggal) {
        const container = document.getElementById('resultContainer');
        
        if (!data) {
            container.innerHTML = `
                <div class="success-message" style="background: #fef3c7; border-color: #fbbf24;">
                    <div class="emoji-big" style="font-size: 3rem;">📭</div>
                    <h3 style="color: #92400e;">Belum Ada Data</h3>
                    <p style="color: #92400e;">Data absensi untuk tanggal ${tanggal} belum tersedia.</p>
                </div>
            `;
            return;
        }
        
        let statusRingkasan = '';
        let ringkasanClass = '';
        
        if (data.status === 'Hadir') {
            ringkasanClass = 'status-present';
            statusRingkasan = 'Hadir';
        } else if (data.status === 'Terlambat') {
            ringkasanClass = 'status-late';
            statusRingkasan = 'Terlambat';
        } else if (data.status === 'Izin') {
            ringkasanClass = 'status-wfh';
            statusRingkasan = 'Izin';
        } else if (data.status === 'Cuti') {
            ringkasanClass = 'status-wfh';
            statusRingkasan = 'Cuti';
        } else if (data.status === 'Libur') {
            ringkasanClass = 'status-early';
            statusRingkasan = 'Libur';
        } else {
            ringkasanClass = 'status-absent';
            statusRingkasan = 'Absen';
        }
        
        let tabelHTML = `
            <div class="info-summary" style="margin-bottom: 20px; justify-content: space-between; flex-wrap: wrap;">
                <p>📅 Tanggal: <strong>${tanggal}</strong></p>
                <p>Status: <span class="status-badge ${ringkasanClass}" style="margin-left: 5px;">${statusRingkasan}</span></p>
            </div>
            
            <div class="table-responsive">
                <table class="data-table-laporan">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 200px; font-weight: 600;">Jam Masuk</td>
                            <td>${data.jam_masuk}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Jam Pulang</td>
                            <td>${data.jam_pulang}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Keterangan / Alasan</td>
                            <td>${data.keterangan || '-'}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        
        container.innerHTML = tabelHTML;
    }

    // Filter data
    function filterData() {
        const tanggal = document.getElementById('tanggal').value;
        
        let data = dataAbsensiPersonal[tanggal];
        
        renderTable(data, tanggal);
        
        console.log(`Menampilkan data absensi untuk tanggal: ${tanggal}`);
    }

    // Export ke Excel
    function exportToExcel() {
        const tanggal = document.getElementById('tanggal').value;
        const data = dataAbsensiPersonal[tanggal];
        
        if (!data) {
            alert('Tidak ada data untuk tanggal yang dipilih');
            return;
        }
        
        let excelContent = `
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Rekap Absensi Harian Personal</title>
                <style>
                    th { background: #2c7da0; color: white; padding: 8px; }
                    td { padding: 6px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <h2>REKAP ABSENSI HARIAN PERSONAL</h2>
                <p>Nama: ${profileKaryawan.nama}</p>
                <p>NIK: ${profileKaryawan.nik}</p>
                <p>Departemen: ${profileKaryawan.departemen}</p>
                <p>Tanggal: ${tanggal}</p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td>${data.status}</td>
                        </tr>
                        <tr>
                            <td>Jam Masuk</td>
                            <td>${data.jam_masuk}</td>
                        </tr>
                        <tr>
                            <td>Jam Pulang</td>
                            <td>${data.jam_pulang}</td>
                        </tr>
                        <tr>
                            <td>Keterangan / Alasan</td>
                            <td>${data.keterangan || '-'}</td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>
        `;
        
        const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Rekap_Absensi_Personal_${tanggal}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Event listener
    document.addEventListener('DOMContentLoaded', function() {
        // Update profile
        updateProfile();
        
        // Set default tanggal hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').value = today;
        
        // Render awal
        filterData();
        
        // Event listener export
        document.getElementById('btnExportExcel').addEventListener('click', exportToExcel);
    });
</script>
@endsection