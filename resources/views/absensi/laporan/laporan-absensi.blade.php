@extends('layouts.absen')

@section('title', 'Laporan Absensi')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="profile-pribadi">
        <div class="content-title">Profile Karyawan</div>
        <p class="content-description">Informasi pribadi dan rekap absensi</p>
        
        <!-- Profile Info Card -->
        <div class="profile-info-card">
            <div class="profile-info-grid-laporan">
                <div class="info-item">
                    <span class="info-label">Nama</span>
                    <span class="info-value">Ahmad Wijaya</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIK</span>
                    <span class="info-value">001</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Departemen</span>
                    <span class="info-value">IT</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">Staff IT</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">ahmad.wijaya@company.com</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. HP</span>
                    <span class="info-value">081234567890</span>
                </div>
            </div>
        </div>
        
        <!-- Filter Rekap -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Bulan</label>
                <select id="bulan-rekap">
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
                    <option value="12">Desember</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Tahun</label>
                <select id="tahun-rekap">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            
            <button class="btn-filter" onclick="lihatRekapProfile()">Lihat</button>
        </div>
        
        <!-- Rekap Stats -->
        <div class="rekap-stats" id="rekap-stats">
            <div class="rekap-card">
                <div class="rekap-value" id="jml-hadir">22</div>
                <div class="rekap-label">Hadir</div>
                <div class="rekap-unit">hari</div>
            </div>
            <div class="rekap-card">
                <div class="rekap-value" id="jml-libur">6</div>
                <div class="rekap-label">Libur</div>
                <div class="rekap-unit">hari</div>
            </div>
            <div class="rekap-card">
                <div class="rekap-value" id="jml-cuti">2</div>
                <div class="rekap-label">Cuti / Izin</div>
                <div class="rekap-unit">hari</div>
            </div>
        </div>
        
        <!-- Total Hari Kerja -->
        <div class="total-hari-card">
            <p>Total Hari Kerja: <strong><span id="total-hari">30</span> hari</strong></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data dummy rekap absensi per bulan
    const dataRekap = {
        '2024': {
            '01': { hadir: 20, libur: 8, cuti: 2, total: 30 },
            '02': { hadir: 18, libur: 9, cuti: 1, total: 28 },
            '03': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '04': { hadir: 21, libur: 7, cuti: 2, total: 30 },
            '05': { hadir: 19, libur: 8, cuti: 3, total: 30 },
            '06': { hadir: 20, libur: 7, cuti: 3, total: 30 },
            '07': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '08': { hadir: 21, libur: 7, cuti: 2, total: 30 },
            '09': { hadir: 20, libur: 8, cuti: 2, total: 30 },
            '10': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '11': { hadir: 18, libur: 9, cuti: 3, total: 30 },
            '12': { hadir: 19, libur: 8, cuti: 3, total: 30 }
        },
        '2025': {
            '01': { hadir: 21, libur: 7, cuti: 2, total: 30 },
            '02': { hadir: 19, libur: 8, cuti: 1, total: 28 },
            '03': { hadir: 23, libur: 5, cuti: 2, total: 30 },
            '04': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '05': { hadir: 20, libur: 7, cuti: 3, total: 30 },
            '06': { hadir: 21, libur: 6, cuti: 3, total: 30 },
            '07': { hadir: 23, libur: 5, cuti: 2, total: 30 },
            '08': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '09': { hadir: 21, libur: 7, cuti: 2, total: 30 },
            '10': { hadir: 23, libur: 5, cuti: 2, total: 30 },
            '11': { hadir: 19, libur: 8, cuti: 3, total: 30 },
            '12': { hadir: 20, libur: 7, cuti: 3, total: 30 }
        },
        '2026': {
            '01': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '02': { hadir: 20, libur: 7, cuti: 1, total: 28 },
            '03': { hadir: 24, libur: 4, cuti: 2, total: 30 },
            '04': { hadir: 23, libur: 5, cuti: 2, total: 30 },
            '05': { hadir: 21, libur: 6, cuti: 3, total: 30 },
            '06': { hadir: 22, libur: 5, cuti: 3, total: 30 },
            '07': { hadir: 24, libur: 4, cuti: 2, total: 30 },
            '08': { hadir: 23, libur: 5, cuti: 2, total: 30 },
            '09': { hadir: 22, libur: 6, cuti: 2, total: 30 },
            '10': { hadir: 24, libur: 4, cuti: 2, total: 30 },
            '11': { hadir: 20, libur: 7, cuti: 3, total: 30 },
            '12': { hadir: 21, libur: 6, cuti: 3, total: 30 }
        }
    };

    function lihatRekapProfile() {
        const bulan = document.getElementById('bulan-rekap').value;
        const tahun = document.getElementById('tahun-rekap').value;
        
        // Ambil data rekap
        const rekap = dataRekap[tahun]?.[bulan] || { hadir: 0, libur: 0, cuti: 0, total: 0 };
        
        // Update tampilan
        document.getElementById('jml-hadir').textContent = rekap.hadir;
        document.getElementById('jml-libur').textContent = rekap.libur;
        document.getElementById('jml-cuti').textContent = rekap.cuti;
        document.getElementById('total-hari').textContent = rekap.total;
    }

    // Set default bulan & tahun saat ini
    document.addEventListener('DOMContentLoaded', function() {
        const sekarang = new Date();
        const bulanSekarang = String(sekarang.getMonth() + 1).padStart(2, '0');
        const tahunSekarang = sekarang.getFullYear();
        
        // Cek apakah tahun tersedia di data, jika tidak pakai 2025
        const tahunTersedia = dataRekap[tahunSekarang] ? tahunSekarang : '2025';
        document.getElementById('bulan-rekap').value = bulanSekarang;
        document.getElementById('tahun-rekap').value = tahunTersedia;
        lihatRekapProfile();
    });
</script>
@endsection