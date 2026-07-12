@extends('layouts.absen')

@section('title', 'Rekap Harian')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="rekap-harian">
        <div class="content-title">Rekap Absensi Harian</div>
        <p class="content-description">Data absensi seluruh karyawan hari ini</p>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="filter-group">
                <label>Departemen</label>
                <select id="departemen" class="form-control">
                    <option value="all">Semua Departemen</option>
                            <option value="ADMIN COD">ADMIN COD</option>
                            <option value="ADMIN RESI">ADMIN RESI</option>
                            <option value="ADMIN CC">ADMIN CC</option>
                            <option value="OUTGOING">OUTGOING</option>
                            <option value="INCOMING">INCOMING</option>
                            <option value="RETUR">RETUR</option>
                            <option value="TRANSPORTER">TRANSPORTER</option>
                            <option value="PROCESSING">PROCESSING</option>
                            <option value="LAINNYA">LAINNYA</option>
                </select>
            </div>
            
            <button class="btn-filter" onclick="filterData()">Filter</button>
            <button id="btnExportExcel" class="btn-filter" style="background: #10b981;">Export Excel</button>
        </div>
        
        <!-- Result Container -->
        <div id="resultContainer">
            <!-- Tabel hasil filter akan muncul di sini -->
        </div>
    </div>
</div>

<script>
    // Data dummy absensi karyawan
    const dataAbsensi = [
        { nama: 'Ahmad Wijaya', nik: '001', departemen: 'OUTGOING ', jam_masuk: '07:55', jam_pulang: '16:30', status: 'Hadir', foto: 'ahmad.jpg' },
        { nama: 'Siti Rahma', nik: '002', departemen: 'OUTGOING', jam_masuk: '08:15', jam_pulang: '16:45', status: 'Hadir', foto: 'siti.jpg' },
        { nama: 'Budi Santoso', nik: '003', departemen: 'ADMIN CC', jam_masuk: '08:30', jam_pulang: '17:00', status: 'Hadir', foto: 'budi.jpg' },
        { nama: 'Dewi Anggraini', nik: '004', departemen: 'INCOMING', jam_masuk: '07:50', jam_pulang: '16:20', status: 'Hadir', foto: 'dewi.jpg' },
        { nama: 'Rudi Hartono', nik: '005', departemen: 'ADMIN RESI', jam_masuk: '-', jam_pulang: '-', status: 'Absen', foto: 'rudi.jpg' },
        { nama: 'Maya Sari', nik: '006', departemen: 'ADMIN RESI', jam_masuk: '08:05', jam_pulang: '16:35', status: 'Hadir', foto: 'maya.jpg' },
        { nama: 'Joko Widodo', nik: '007', departemen: 'INCOMING', jam_masuk: '08:20', jam_pulang: '16:50', status: 'Hadir', foto: 'joko.jpg' },
        { nama: 'Linda Cahyani', nik: '008', departemen: 'ADMIN CC', jam_masuk: '07:45', jam_pulang: '16:15', status: 'Hadir', foto: 'linda.jpg' },
        { nama: 'Agus Salim', nik: '009', departemen: 'RETUR', jam_masuk: '-', jam_pulang: '-', status: 'Cuti', foto: 'agus.jpg' },
        { nama: 'Nina Kartika', nik: '010', departemen: 'RETUR', jam_masuk: '08:10', jam_pulang: '16:40', status: 'Hadir', foto: 'nina.jpg' }
    ];

    // Status badge mapping (tanpa emoji, tanpa checklist, tanpa X)
    function getStatusBadge(status) {
        switch(status) {
            case 'Hadir':
                return '<span class="status-badge status-present">Hadir</span>';
            case 'Absen':
                return '<span class="status-badge status-absent">Absen</span>';
            case 'Izin':
                return '<span class="status-badge status-wfh">Izin</span>';
            case 'Cuti':
                return '<span class="status-badge status-wfh">Cuti</span>';
            default:
                return '<span class="status-badge">-</span>';
        }
    }

    // Render tabel
    function renderTable(data) {
        const container = document.getElementById('resultContainer');
        
        if (data.length === 0) {
            container.innerHTML = `
                <div class="success-message" style="background: #fef3c7; border-color: #fbbf24;">
                    <div class="emoji-big" style="font-size: 3rem;">📭</div>
                    <h3 style="color: #92400e;">Tidak Ada Data</h3>
                    <p style="color: #92400e;">Tidak ditemukan data absensi untuk filter yang dipilih.</p>
                </div>
            `;
            return;
        }
        
        let tabelHTML = `
            <div class="table-responsive">
                <table class="data-table-laporan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.forEach((item, index) => {
            tabelHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nik}</td>
                    <td>${item.nama}</td>
                    <td>${item.departemen}</td>
                    <td>${item.jam_masuk}</td>
                    <td>${item.jam_pulang}</td>
                    <td>${getStatusBadge(item.status)}</td>
                    <td class="text-center">
                        <button class="btn-link" onclick="showPhoto('${item.nama}')">Lihat</button>
                    </td>
                </tr>
            `;
        });
        
        tabelHTML += `
                    </tbody>
                </table>
            </div>
            <div class="info-summary" style="margin-top: 20px; justify-content: center;">
                <p>Total Karyawan: <strong>${data.length}</strong> orang</p>
                <p>Hadir: <strong>${data.filter(d => d.status === 'Hadir').length}</strong></p>
                <p>Absen: <strong>${data.filter(d => d.status === 'Absen').length}</strong></p>
                <p>Cuti / Izin: <strong>${data.filter(d => d.status === 'Cuti' || d.status === 'Izin').length}</strong></p>
            </div>
        `;
        
        container.innerHTML = tabelHTML;
    }

    // Filter data
    function filterData() {
        const tanggal = document.getElementById('tanggal').value;
        const departemen = document.getElementById('departemen').value;
        
        let filteredData = [...dataAbsensi];
        
        // Filter berdasarkan departemen
        if (departemen !== 'all') {
            filteredData = filteredData.filter(item => item.departemen === departemen);
        }
        
        renderTable(filteredData);
        
        // Tampilkan notifikasi di console
        console.log(`Menampilkan data untuk tanggal: ${tanggal}, departemen: ${departemen}`);
    }

    // Export ke Excel
    function exportToExcel() {
        const tanggal = document.getElementById('tanggal').value;
        const departemen = document.getElementById('departemen').value;
        const departemenText = departemen === 'all' ? 'Semua Departemen' : departemen;
        
        // Ambil data yang sedang difilter
        let filteredData = [...dataAbsensi];
        if (departemen !== 'all') {
            filteredData = filteredData.filter(item => item.departemen === departemen);
        }
        
        // Hitung statistik
        const totalHadir = filteredData.filter(d => d.status === 'Hadir').length;
        const totalAbsen = filteredData.filter(d => d.status === 'Absen').length;
        const totalCutiIzin = filteredData.filter(d => d.status === 'Cuti' || d.status === 'Izin').length;
        
        // Buat konten Excel
        let excelContent = `
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Rekap Absensi Harian</title>
                <style>
                    th { background: #2c7da0; color: white; padding: 8px; }
                    td { padding: 6px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <h2>REKAP ABSENSI HARIAN</h2>
                <p>Tanggal: ${tanggal} | Departemen: ${departemenText}</p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        filteredData.forEach((item, index) => {
            excelContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nik}</td>
                    <td>${item.nama}</td>
                    <td>${item.departemen}</td>
                    <td>${item.jam_masuk}</td>
                    <td>${item.jam_pulang}</td>
                    <td>${item.status}</td>
                </tr>
            `;
        });
        
        excelContent += `
                    </tbody>
                </table>
                <br>
                <p><strong>Ringkasan:</strong></p>
                <p>Total Karyawan: ${filteredData.length}</p>
                <p>Hadir: ${totalHadir}</p>
                <p>Absen: ${totalAbsen}</p>
                <p>Cuti / Izin: ${totalCutiIzin}</p>
            </body>
            </html>
        `;
        
        const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Rekap_Absensi_${tanggal}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Fungsi show photo (placeholder)
    function showPhoto(nama) {
        window.showFormalAlert(`Menampilkan foto absensi ${nama}.`, 'info', 'Informasi Foto');
    }

    // Event listener
    document.addEventListener('DOMContentLoaded', function() {
        // Set default tanggal hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').value = today;
        
        // Render awal
        renderTable(dataAbsensi);
        
        // Event listener export
        document.getElementById('btnExportExcel').addEventListener('click', exportToExcel);
    });
</script>
@endsection