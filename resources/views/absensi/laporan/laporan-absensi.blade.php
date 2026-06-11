@extends('layouts.absen')

@section('title', 'Laporan Absensi')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
@endphp
<div class="dashboard-content">
    <div class="page-content active" id="profile-pribadi">
        <div class="content-title">Profile Karyawan</div>
        <p class="content-description">Informasi pribadi dan rekap absensi</p>

        <!-- Profile Info Card -->
        <div class="profile-info-card">
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
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. HP</span>
                    <span class="info-value">{{ $user->no_hp ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Rekap -->
        <form method="GET" action="{{ route('laporan.absensi') }}" class="filter-section">
            <div class="filter-group">
                <label>Bulan</label>
                <select name="bulan">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ $namaBulan[$m] }}</option>
                    @endfor
                </select>
            </div>

            <div class="filter-group">
                <label>Tahun</label>
                <select name="tahun">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn-filter">Lihat</button>
        </form>

        <!-- Rekap Stats -->
        <div class="rekap-stats" id="rekap-stats">
            <div class="rekap-card">
                <div class="rekap-value">{{ $hadir }}</div>
                <div class="rekap-label">Hadir</div>
                <div class="rekap-unit">hari</div>
            </div>
            <div class="rekap-card">
                <div class="rekap-value">{{ $libur }}</div>
                <div class="rekap-label">Libur</div>
                <div class="rekap-unit">hari</div>
            </div>
            <div class="rekap-card">
                <div class="rekap-value">{{ $izin + $cuti }}</div>
                <div class="rekap-label">Cuti / Izin</div>
                <div class="rekap-unit">kali</div>
            </div>
        </div>

        <!-- Total Hari -->
        <div class="total-hari-card">
            <p>Total Hari: <strong>{{ $totalHari }} hari</strong></p>
        </div>
    </div>
</div>
@endsection
