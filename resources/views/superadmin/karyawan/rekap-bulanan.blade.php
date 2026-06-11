@extends('layouts.absen')

@section('title', 'Rekap Bulanan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="rekap-bulanan">
        <div class="content-title">Rekap Absensi Bulanan</div>
        <p class="content-description">Statistik absensi karyawan per bulan</p>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Periode</label>
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
            
            <button class="btn-filter" onclick="filterData()">Tampilkan</button>
            <button id="btnExportExcel" class="btn-filter" style="background: #10b981;">Export Excel</button>
        </div>
        
        <!-- Result Container -->
        <div id="resultContainer">
            <!-- Tabel hasil filter akan muncul di sini -->
        </div>
    </div>
</div>

<script>
    // Data dummy rekap absensi bulanan
    const dataRekapBulanan = [
        { nama: 'Ahmad Wijaya', nik: '001', departemen: 'OUTGOING', hadir: 21, terlambat: 2, izin: 1, cuti: 0, alpha: 0 },
        { nama: 'Siti Rahma', nik: '002', departemen: 'OUTGOING', hadir: 20, terlambat: 1, izin: 2, cuti: 0, alpha: 1 },
        { nama: 'Budi Santoso', nik: '003', departemen: 'ADMIN CC', hadir: 18, terlambat: 5, izin: 1, cuti: 1, alpha: 0 },
        { nama: 'Dewi Anggraini', nik: '004', departemen: 'INCOMING', hadir: 22, terlambat: 0, izin: 0, cuti: 0, alpha: 0 },
        { nama: 'Rudi Hartono', nik: '005', departemen: 'ADMIN RESI', hadir: 17, terlambat: 3, izin: 2, cuti: 0, alpha: 1 },
        { nama: 'Maya Sari', nik: '006', departemen: 'ADMIN RESI', hadir: 20, terlambat: 2, izin: 1, cuti: 1, alpha: 0 },
        { nama: 'Joko Widodo', nik: '007', departemen: 'INCOMING', hadir: 19, terlambat: 4, izin: 0, cuti: 0, alpha: 1 },
        { nama: 'Linda Cahyani', nik: '008', departemen: 'ADMIN CC', hadir: 21, terlambat: 0, izin: 2, cuti: 0, alpha: 0 },
        { nama: 'Agus Salim', nik: '009', departemen: 'RETUR', hadir: 20, terlambat: 1, izin: 1, cuti: 1, alpha: 0 },
        { nama: 'Nina Kartika', nik: '010', departemen: 'RETUR', hadir: 22, terlambat: 0, izin: 0, cuti: 0, alpha: 0 }
    ];

    // Nama bulan
    const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Render tabel
    function renderTable(data, bulan, tahun) {
        const container = document.getElementById('resultContainer');
        
        if (data.length === 0) {
            container.innerHTML = `
                <div class="success-message" style="background: #fef3c7; border-color: #fbbf24;">
                    <div class="emoji-big">📭</div>
                    <h3 style="color: #92400e;">Tidak Ada Data</h3>
                    <p style="color: #92400e;">Tidak ditemukan data rekap untuk filter yang dipilih.</p>
                </div>
            `;
            return;
        }
        
        // Hitung total keseluruhan
        const totalHadir = data.reduce((sum, item) => sum + item.hadir, 0);
        const totalTerlambat = data.reduce((sum, item) => sum + item.terlambat, 0);
        const totalIzin = data.reduce((sum, item) => sum + item.izin, 0);
        const totalCuti = data.reduce((sum, item) => sum + item.cuti, 0);
        const totalAlpha = data.reduce((sum, item) => sum + item.alpha, 0);
        
        let tabelHTML = `
            <div class="info-summary" style="margin-bottom: 20px; justify-content: center;">
                <p>📅 Periode: <strong>${namaBulan[parseInt(bulan)-1]} ${tahun}</strong></p>
                <p>👥 Total Karyawan: <strong>${data.length}</strong> orang</p>
            </div>
            
            <div class="table-responsive">
                <table class="data-table-laporan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Cuti</th>
                            <th class="text-center">Alpha</th>
                            <th class="text-center">Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.forEach((item, index) => {
            const totalHari = item.hadir + item.terlambat + item.izin + item.cuti + item.alpha;
            const persentase = Math.round((item.hadir / totalHari) * 100) || 0;
            let progressColor = persentase >= 80 ? '#10b981' : (persentase >= 60 ? '#f59e0b' : '#ef4444');
            
            tabelHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nik}</td>
                    <td>${item.nama}</td>
                    <td>${item.departemen}</td>
                    <td class="text-center"><strong>${item.hadir}</strong></td>
                    <td class="text-center">${item.terlambat}</td>
                    <td class="text-center">${item.izin}</td>
                    <td class="text-center">${item.cuti}</td>
                    <td class="text-center">${item.alpha}</td>
                    <td class="text-center">
                        <div class="progress-mini">
                            <div class="progress-mini-fill" style="width: ${persentase}%; background: ${progressColor};"></div>
                            <span>${persentase}%</span>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tabelHTML += `
                    </tbody>
                    <tfoot>
                        <tr style="background: #f1f5f9; font-weight: 600;">
                            <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                            <td class="text-center">${totalHadir}</td>
                            <td class="text-center">${totalTerlambat}</td>
                            <td class="text-center">${totalIzin}</td>
                            <td class="text-center">${totalCuti}</td>
                            <td class="text-center">${totalAlpha}</td>
                            <td class="text-center">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
        
        container.innerHTML = tabelHTML;
    }

    // Filter data
    function filterData() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const departemen = document.getElementById('departemen').value;
        
        let filteredData = [...dataRekapBulanan];
        
        // Filter berdasarkan departemen
        if (departemen !== 'all') {
            filteredData = filteredData.filter(item => item.departemen === departemen);
        }
        
        renderTable(filteredData, bulan, tahun);
    }

    // Export ke Excel
    function exportToExcel() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const departemen = document.getElementById('departemen').value;
        const departemenText = departemen === 'all' ? 'Semua Departemen' : departemen;
        
        let filteredData = [...dataRekapBulanan];
        if (departemen !== 'all') {
            filteredData = filteredData.filter(item => item.departemen === departemen);
        }
        
        const totalHadir = filteredData.reduce((sum, item) => sum + item.hadir, 0);
        const totalTerlambat = filteredData.reduce((sum, item) => sum + item.terlambat, 0);
        const totalIzin = filteredData.reduce((sum, item) => sum + item.izin, 0);
        const totalCuti = filteredData.reduce((sum, item) => sum + item.cuti, 0);
        const totalAlpha = filteredData.reduce((sum, item) => sum + item.alpha, 0);
        
        let excelContent = `
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Rekap Absensi Bulanan</title>
                <style>
                    th { background: #2c7da0; color: white; padding: 8px; }
                    td { padding: 6px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <h2>REKAP ABSENSI BULANAN</h2>
                <p>Periode: ${namaBulan[parseInt(bulan)-1]} ${tahun} | Departemen: ${departemenText}</p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Hadir</th>
                            <th>Terlambat</th>
                            <th>Izin</th>
                            <th>Cuti</th>
                            <th>Alpha</th>
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
                    <td align="center">${item.hadir}</td>
                    <td align="center">${item.terlambat}</td>
                    <td align="center">${item.izin}</td>
                    <td align="center">${item.cuti}</td>
                    <td align="center">${item.alpha}</td>
                </tr>
            `;
        });
        
        excelContent += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>TOTAL</strong></td>
                            <td align="center"><strong>${totalHadir}</strong></td>
                            <td align="center"><strong>${totalTerlambat}</strong></td>
                            <td align="center"><strong>${totalIzin}</strong></td>
                            <td align="center"><strong>${totalCuti}</strong></td>
                            <td align="center"><strong>${totalAlpha}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <p><strong>Ringkasan Kehadiran:</strong></p>
                <p>Total Karyawan: ${filteredData.length}</p>
                <p>Total Kehadiran: ${totalHadir} hari</p>
                <p>Presentase Kehadiran: ${Math.round((totalHadir / (filteredData.length * 22)) * 100)}%</p>
            </body>
            </html>
        `;
        
        const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Rekap_Bulanan_${tahun}_${bulan}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Event listener
    document.addEventListener('DOMContentLoaded', function() {
        // Render awal dengan data default
        renderTable(dataRekapBulanan, '12', '2025');
        
        // Event listener export
        document.getElementById('btnExportExcel').addEventListener('click', exportToExcel);
    });
</script>
@endsection