@extends('layouts.absen')

@section('title', 'Rekap Bulanan Personal')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $persentaseHadir = $totalHari > 0 ? round(($hadir / $totalHari) * 100) : 0;
    $persentaseTerlambat = $totalHari > 0 ? round(($terlambat / $totalHari) * 100) : 0;
    $progressColor = $persentaseHadir >= 80 ? '#10b981' : ($persentaseHadir >= 60 ? '#f59e0b' : '#ef4444');
    $kedisiplinan = $persentaseTerlambat <= 5 ? 'Sangat Disiplin (Baik)' : ($persentaseTerlambat <= 10 ? 'Cukup Disiplin' : 'Perlu Evaluasi');
@endphp
<div class="dashboard-content">
    <!-- Page Header Formal -->
    <div class="welcome-section" style="margin-bottom: 24px;">
        <h1 class="page-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Rekap Absensi Bulanan</h1>
        <p class="page-subtitle" style="font-size: 14px; color: #64748b; margin: 0;">Laporan akumulasi statistik kehadiran bulanan secara komprehensif</p>
    </div>

    <!-- Filter Section Formal -->
    <div class="content-card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('monitoring.bulanan') }}">
            <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; justify-content: space-between;">
                <div style="display: flex; flex-wrap: wrap; gap: 16px; flex: 1;">
                    <div style="min-width: 180px; flex: 1;">
                        <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; display: block;">Bulan Periode</label>
                        <select name="bulan" class="form-control" style="border-radius: 8px; padding: 10px 14px; border: 1px solid #cbd5e1; width: 100%; font-size: 14px; color: #1e293b;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ $namaBulan[$m] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div style="min-width: 140px; flex: 1;">
                        <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; display: block;">Tahun Periode</label>
                        <select name="tahun" class="form-control" style="border-radius: 8px; padding: 10px 14px; border: 1px solid #cbd5e1; width: 100%; font-size: 14px; color: #1e293b;">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" style="background: #1e293b; color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Tampilkan Rekap
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

    <!-- Rekap Stats Grid -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Hadir</span>
                <div class="stat-icon icon-present">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color: #10b981;">{{ $hadir }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">hari</span></div>
            <div class="stat-change positive">Kehadiran Tercatat</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Keterlambatan</span>
                <div class="stat-icon icon-late">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color: #f59e0b;">{{ $terlambat }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">kali</span></div>
            <div class="stat-change warning">Total Terlambat</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Izin Kerja</span>
                <div class="stat-icon icon-online">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color: #3b82f6;">{{ $izin }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">hari</span></div>
            <div class="stat-change">Izin Disetujui</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Cuti</span>
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color: #8b5cf6;">{{ $cuti }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">hari</span></div>
            <div class="stat-change">Cuti Disetujui</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Hari Libur</span>
                <div class="stat-icon icon-absent">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color: #64748b;">{{ $libur }} <span style="font-size: 14px; font-weight: 500; color: #64748b;">hari</span></div>
            <div class="stat-change">Akhir Pekan / Libur</div>
        </div>
    </div>

    <!-- Summary Analysis Card -->
    <div class="content-card">
        <div class="content-card-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 class="content-title" style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0 0 4px 0; border: none; padding: 0;">Analisis Kehadiran Periode {{ $namaBulan[$bulan] }} {{ $tahun }}</h3>
                <span style="font-size: 13px; color: #64748b;">Total Hari Kalender: <strong>{{ $totalHari }} Hari</strong></span>
            </div>
            <span class="content-badge" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #334155;">{{ $kedisiplinan }}</span>
        </div>

        <div style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                <span>Tingkat Kehadiran Efektif</span>
                <span>{{ $persentaseHadir }}%</span>
            </div>
            <div style="width: 100%; height: 10px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                <div style="width: {{ $persentaseHadir }}%; height: 100%; background: {{ $progressColor }}; border-radius: 999px; transition: width 0.5s ease;"></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f8fafc; border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 500; color: #64748b;">Total Rasio Kehadiran</span>
                <span style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $hadir }} / {{ $totalHari }} Hari</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f8fafc; border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 500; color: #64748b;">Evaluasi Kedisiplinan</span>
                <span style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $kedisiplinan }}</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnExportExcel').addEventListener('click', function () {
        const excelContent = `
            <html><head><meta charset="UTF-8"><title>Rekap Absensi Bulanan</title>
            <style>th{background:#1e293b;color:#fff;padding:10px;text-align:left;}td{padding:8px;border:1px solid #e2e8f0;}</style></head>
            <body>
                <h2 style="font-family: Arial, sans-serif; color: #1e293b;">REKAP ABSENSI BULANAN KARYAWAN</h2>
                <table border="0" cellpadding="3">
                    <tr><td><strong>Nama</strong></td><td>: {{ $user->name }}</td></tr>
                    <tr><td><strong>NIK</strong></td><td>: {{ $user->nik ?? '-' }}</td></tr>
                    <tr><td><strong>Departemen</strong></td><td>: {{ $user->departemen ?? '-' }}</td></tr>
                    <tr><td><strong>Jabatan</strong></td><td>: {{ $user->jabatan ?? '-' }}</td></tr>
                    <tr><td><strong>Periode</strong></td><td>: {{ $namaBulan[$bulan] }} {{ $tahun }}</td></tr>
                </table>
                <br>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead><tr><th>Komponen Catatan</th><th>Jumlah / Akumulasi</th></tr></thead>
                    <tbody>
                        <tr><td>Total Hari Periode</td><td>{{ $totalHari }} Hari</td></tr>
                        <tr><td>Total Kehadiran (Hadir)</td><td>{{ $hadir }} Hari</td></tr>
                        <tr><td>Frekuensi Keterlambatan</td><td>{{ $terlambat }} Kali</td></tr>
                        <tr><td>Izin Disetujui</td><td>{{ $izin }} Hari</td></tr>
                        <tr><td>Cuti Disetujui</td><td>{{ $cuti }} Hari</td></tr>
                        <tr><td>Hari Libur / Akhir Pekan</td><td>{{ $libur }} Hari</td></tr>
                        <tr><td>Tingkat Kehadiran Efektif</td><td>{{ $persentaseHadir }}%</td></tr>
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
