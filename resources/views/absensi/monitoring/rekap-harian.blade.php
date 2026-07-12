@extends('layouts.absen')

@section('title', 'Rekap Harian Personal')

@section('content')
@php
    $badgeMap = [
        'Hadir'       => 'background: #dcfce7; color: #166534; border: 1px solid #86efac;',
        'Tidak Hadir' => 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
        'Izin'        => 'background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
        'Cuti'        => 'background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;',
        'Libur'       => 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;',
    ];
    $badgeStyle = $badgeMap[$status] ?? 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;';
@endphp
<div class="dashboard-content">
    <!-- Page Header Formal -->
    <div class="welcome-section" style="margin-bottom: 24px;">
        <h1 class="page-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Rekap Absensi Harian</h1>
        <p class="page-subtitle" style="font-size: 14px; color: #64748b; margin: 0;">Laporan catatan kehadiran harian karyawan secara rinci</p>
    </div>

    <!-- Filter Section Formal -->
    <div class="content-card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('monitoring.harian') }}">
            <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; justify-content: space-between;">
                <div style="flex: 1; min-width: 240px;">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; display: block;">Tanggal Absensi</label>
                    <input type="date" name="tanggal" class="form-control" style="border-radius: 8px; padding: 10px 14px; border: 1px solid #cbd5e1; width: 100%; font-size: 14px; color: #1e293b;" value="{{ $tanggal }}">
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" style="background: #1e293b; color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Tampilkan Data
                    </button>
                    <button type="button" id="btnExportExcel" style="background: #10b981; color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Informasi Karyawan Card -->
    <div class="content-card" style="margin-bottom: 24px;">
        <div class="content-card-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 18px;">
            <h3 class="content-title" style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0; border: none; padding: 0;">Informasi Karyawan</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <span style="display: block; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nama Karyawan</span>
                <span style="font-size: 15px; font-weight: 600; color: #1e293b;">{{ $user->name }}</span>
            </div>
            <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <span style="display: block; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nomor Induk Karyawan (NIK)</span>
                <span style="font-size: 15px; font-weight: 600; color: #1e293b;">{{ $user->nik ?? '-' }}</span>
            </div>
            <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <span style="display: block; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Departemen</span>
                <span style="font-size: 15px; font-weight: 600; color: #1e293b;">{{ $user->departemen ?? '-' }}</span>
            </div>
            <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <span style="display: block; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Jabatan</span>
                <span style="font-size: 15px; font-weight: 600; color: #1e293b;">{{ $user->jabatan ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Result Data Absensi -->
    <div class="content-card">
        <div class="content-card-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 class="content-title" style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0 0 4px 0; border: none; padding: 0;">Rincian Data Absensi</h3>
                <span style="font-size: 13px; color: #64748b;">Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong></span>
            </div>
            <div>
                <span style="font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 20px; {{ $badgeStyle }} display: inline-block;">
                    Status: {{ $status }}
                </span>
            </div>
        </div>

        @if(!$adaData)
            <div style="text-align: center; padding: 48px 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <h4 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 6px;">Belum Ada Data Kehadiran</h4>
                <p style="font-size: 14px; color: #64748b; margin: 0; max-width: 450px; margin-left: auto; margin-right: auto;">Data absensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong> belum tersedia atau belum dilakukan perekaman absensi pada sistem.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table-laporan" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 260px;">Komponen Keterangan</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Detail Rekam Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px 18px; font-weight: 600; color: #334155;">Waktu Absen Masuk</td>
                            <td style="padding: 16px 18px; color: #1e293b; font-weight: 500;">{{ $jamMasuk }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px 18px; font-weight: 600; color: #334155;">Waktu Absen Pulang</td>
                            <td style="padding: 16px 18px; color: #1e293b; font-weight: 500;">{{ $jamPulang }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 16px 18px; font-weight: 600; color: #334155;">Lokasi / Koordinat Rekam</td>
                            <td style="padding: 16px 18px; color: #1e293b;">{{ $alamat }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        @if(!$adaData)
            window.showFormalAlert('Tidak ada data absensi untuk tanggal yang dipilih.', 'info', 'Informasi');
            return;
        @else
            const excelContent = `
                <html><head><meta charset="UTF-8"><title>Rekap Absensi Harian</title>
                <style>th{background:#1e293b;color:#fff;padding:10px;text-align:left;}td{padding:8px;border:1px solid #e2e8f0;}</style></head>
                <body>
                    <h2 style="font-family: Arial, sans-serif; color: #1e293b;">REKAP ABSENSI HARIAN KARYAWAN</h2>
                    <table border="0" cellpadding="3">
                        <tr><td><strong>Nama</strong></td><td>: {{ $user->name }}</td></tr>
                        <tr><td><strong>NIK</strong></td><td>: {{ $user->nik ?? '-' }}</td></tr>
                        <tr><td><strong>Departemen</strong></td><td>: {{ $user->departemen ?? '-' }}</td></tr>
                        <tr><td><strong>Jabatan</strong></td><td>: {{ $user->jabatan ?? '-' }}</td></tr>
                        <tr><td><strong>Tanggal</strong></td><td>: {{ $tanggal }}</td></tr>
                    </table>
                    <br>
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                        <thead><tr><th>Komponen Keterangan</th><th>Detail Rekam Data</th></tr></thead>
                        <tbody>
                            <tr><td>Status Kehadiran</td><td>{{ $status }}</td></tr>
                            <tr><td>Waktu Absen Masuk</td><td>{{ $jamMasuk }}</td></tr>
                            <tr><td>Waktu Absen Pulang</td><td>{{ $jamPulang }}</td></tr>
                            <tr><td>Lokasi / Koordinat Rekam</td><td>{{ $alamat }}</td></tr>
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
