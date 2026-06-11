@extends('layouts.absen')

@section('title', 'Rekap Bulanan Personal')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $persentaseHadir = $totalHari > 0 ? round(($hadir / $totalHari) * 100) : 0;
    $persentaseTerlambat = $totalHari > 0 ? round(($terlambat / $totalHari) * 100) : 0;
    $progressColor = $persentaseHadir >= 80 ? '#10b981' : ($persentaseHadir >= 60 ? '#f59e0b' : '#ef4444');
    $kedisiplinan = $persentaseTerlambat <= 5 ? 'Baik' : ($persentaseTerlambat <= 10 ? 'Cukup' : 'Perlu Perbaikan');
@endphp
<div class="dashboard-content">
    <div class="page-content active" id="rekap-bulanan-personal">
        <div class="content-title">Rekap Absensi Bulanan Saya</div>
        <p class="content-description">Statistik absensi pribadi Anda per bulan</p>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('monitoring.bulanan') }}" class="filter-section">
            <div class="filter-group">
                <label>Bulan</label>
                <select name="bulan" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ $namaBulan[$m] }}</option>
                    @endfor
                </select>
            </div>

            <div class="filter-group">
                <label>Tahun</label>
                <select name="tahun" class="form-control">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn-filter">Tampilkan</button>
            <button type="button" id="btnExportExcel" class="btn-filter" style="background: #10b981;">Export Excel</button>
        </form>

        <!-- Profile Ringkasan -->
        <div class="profile-info-card" style="margin-bottom: 20px;">
            <div class="profile-info-grid-laporan">
                <div class="info-item">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIK</span>
                    <span class="info-value">{{ $user->nik ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Departemen</span>
                    <span class="info-value">{{ $user->departemen ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">{{ $user->jabatan ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Result -->
        <div id="resultContainer">
            <div class="info-summary" style="margin-bottom: 20px; justify-content: center;">
                <p>Periode: <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong></p>
                <p>Total Hari: <strong>{{ $totalHari }}</strong> hari</p>
            </div>

            <div class="rekap-stats" style="margin-bottom: 24px;">
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #10b981;">{{ $hadir }}</div>
                    <div class="rekap-label">Hadir</div>
                    <div class="rekap-unit">hari</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #f59e0b;">{{ $terlambat }}</div>
                    <div class="rekap-label">Terlambat</div>
                    <div class="rekap-unit">kali</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #8b5cf6;">{{ $izin }}</div>
                    <div class="rekap-label">Izin</div>
                    <div class="rekap-unit">kali</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #3b82f6;">{{ $cuti }}</div>
                    <div class="rekap-label">Cuti</div>
                    <div class="rekap-unit">kali</div>
                </div>
                <div class="rekap-card">
                    <div class="rekap-value" style="color: #6c7e97;">{{ $libur }}</div>
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
                        <span>{{ $persentaseHadir }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $persentaseHadir }}%; background: {{ $progressColor }};"></div>
                    </div>
                </div>
                <div class="summary-stats">
                    <div class="summary-stat">
                        <span class="summary-stat-label">Total Kehadiran</span>
                        <span class="summary-stat-value">{{ $hadir }} / {{ $totalHari }}</span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label">Kedisiplinan</span>
                        <span class="summary-stat-value">{{ $kedisiplinan }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        const excelContent = `
            <html><head><meta charset="UTF-8"><title>Rekap Absensi Bulanan</title>
            <style>th{background:#2c7da0;color:#fff;padding:8px}td{padding:6px;border:1px solid #ddd}</style></head>
            <body>
                <h2>REKAP ABSENSI BULANAN</h2>
                <p>Nama: {{ $user->name }}</p>
                <p>NIK: {{ $user->nik ?? '-' }}</p>
                <p>Departemen: {{ $user->departemen ?? '-' }}</p>
                <p>Periode: {{ $namaBulan[$bulan] }} {{ $tahun }}</p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead><tr><th>Keterangan</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        <tr><td>Total Hari</td><td>{{ $totalHari }}</td></tr>
                        <tr><td>Hadir</td><td>{{ $hadir }}</td></tr>
                        <tr><td>Terlambat</td><td>{{ $terlambat }}</td></tr>
                        <tr><td>Izin</td><td>{{ $izin }}</td></tr>
                        <tr><td>Cuti</td><td>{{ $cuti }}</td></tr>
                        <tr><td>Libur</td><td>{{ $libur }}</td></tr>
                        <tr><td>Tingkat Kehadiran</td><td>{{ $persentaseHadir }}%</td></tr>
                    </tbody>
                </table>
            </body></html>`;
        const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Rekap_Bulanan_{{ $tahun }}_{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
</script>
@endsection
