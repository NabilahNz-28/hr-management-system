@extends('layouts.absen')

@section('title', 'Laporan Keterlambatan')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
@endphp
<div class="dashboard-content">
    <!-- Page Header Formal -->
    <div class="welcome-section" style="margin-bottom: 24px;">
        <h1 class="page-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Riwayat Keterlambatan Absensi</h1>
        <p class="page-subtitle" style="font-size: 14px; color: #64748b; margin: 0;">Monitoring kedisiplinan dan catatan keterlambatan jam masuk kerja per bulan</p>
    </div>

    <!-- Filter Section Formal -->
    <div class="content-card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('laporan.terlambat') }}">
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
                <div>
                    <button type="submit" style="background: #1e293b; color: #ffffff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Tampilkan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary & Result Card -->
    <div class="content-card">
        <div class="content-card-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 class="content-title" style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0 0 4px 0; border: none; padding: 0;">Catatan Waktu Keterlambatan</h3>
                <span style="font-size: 13px; color: #64748b;">Periode: <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong></span>
            </div>
            <div>
                <span style="font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 20px; background: {{ $total === 0 ? '#dcfce7; color: #166534;' : '#fef3c7; color: #92400e;' }}">
                    Frekuensi: {{ $total }} Kali
                </span>
            </div>
        </div>

        @if($total === 0)
            <div style="text-align: center; padding: 48px 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h4 style="font-size: 16px; font-weight: 600; color: #166534; margin-bottom: 6px;">Tepat Waktu Sepanjang Bulan</h4>
                <p style="font-size: 14px; color: #475569; margin: 0; max-width: 480px; margin-left: auto; margin-right: auto;">Anda tidak memiliki catatan keterlambatan pada periode <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>. Tingkat kedisiplinan kehadiran mencapai 100%.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table-laporan" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 70px;">No</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Absensi</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Waktu Jam Masuk</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Durasi Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($terlambat as $i => $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; color: #64748b; font-weight: 500;">{{ $i + 1 }}</td>
                            <td style="padding: 14px 18px; color: #1e293b; font-weight: 600;">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                            <td style="padding: 14px 18px; color: #334155; font-weight: 500;">{{ $item['jam_masuk'] }}</td>
                            <td style="padding: 14px 18px;">
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                    {{ $item['menit'] }} menit
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
