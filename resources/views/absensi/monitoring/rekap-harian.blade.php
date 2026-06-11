@extends('layouts.absen')

@section('title', 'Rekap Harian Personal')

@section('content')
@php
    $badgeMap = [
        'Hadir'       => 'status-present',
        'Terlambat'   => 'status-late',
        'Tidak Hadir' => 'status-absent',
        'Izin'        => 'status-wfh',
        'Cuti'        => 'status-wfh',
        'Libur'       => 'status-early',
    ];
    $badgeClass = $badgeMap[$status] ?? 'status-absent';
@endphp
<div class="dashboard-content">
    <div class="page-content active" id="rekap-harian-personal">
        <div class="content-title">Rekap Absensi Harian Saya</div>
        <p class="content-description">Data absensi pribadi Anda</p>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('monitoring.harian') }}" class="filter-section">
            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
            </div>

            <button type="submit" class="btn-filter">Filter</button>
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
            @if(!$adaData)
                <div class="success-message" style="background: #fef3c7; border-color: #fbbf24;">
                    <div class="emoji-big" style="font-size: 3rem;">📭</div>
                    <h3 style="color: #92400e;">Belum Ada Data</h3>
                    <p style="color: #92400e;">Data absensi untuk tanggal {{ $tanggal }} belum tersedia.</p>
                </div>
            @else
                <div class="info-summary" style="margin-bottom: 20px; justify-content: space-between; flex-wrap: wrap;">
                    <p>📅 Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong></p>
                    <p>Status: <span class="status-badge {{ $badgeClass }}" style="margin-left: 5px;">{{ $status }}</span></p>
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
                                <td>{{ $jamMasuk }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Jam Pulang</td>
                                <td>{{ $jamPulang }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Lokasi</td>
                                <td>{{ $alamat }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Export Excel dari data yang sudah dirender server-side
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        @if(!$adaData)
            alert('Tidak ada data untuk tanggal yang dipilih');
            return;
        @else
            const excelContent = `
                <html><head><meta charset="UTF-8"><title>Rekap Absensi Harian</title>
                <style>th{background:#2c7da0;color:#fff;padding:8px}td{padding:6px;border:1px solid #ddd}</style></head>
                <body>
                    <h2>REKAP ABSENSI HARIAN</h2>
                    <p>Nama: {{ $user->name }}</p>
                    <p>NIK: {{ $user->nik ?? '-' }}</p>
                    <p>Departemen: {{ $user->departemen ?? '-' }}</p>
                    <p>Tanggal: {{ $tanggal }}</p>
                    <table border="1" cellpadding="5" cellspacing="0">
                        <thead><tr><th>Keterangan</th><th>Detail</th></tr></thead>
                        <tbody>
                            <tr><td>Status</td><td>{{ $status }}</td></tr>
                            <tr><td>Jam Masuk</td><td>{{ $jamMasuk }}</td></tr>
                            <tr><td>Jam Pulang</td><td>{{ $jamPulang }}</td></tr>
                            <tr><td>Lokasi</td><td>{{ $alamat }}</td></tr>
                        </tbody>
                    </table>
                </body></html>`;
            const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Rekap_Absensi_Harian_{{ $tanggal }}.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        @endif
    });
</script>
@endsection
