@extends('layouts.superadmin')

@section('title', 'Dashboard Superadmin')

@section('content')
<div class="dashboard-content">
    <div class="welcome-section">
        <h1 class="page-title">Dashboard Superadmin</h1>
        <p class="page-subtitle">Ringkasan data karyawan hari ini</p>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-title">Statistik Karyawan</h3>
            <span class="content-badge">Hari Ini</span>
        </div>

        <div class="stats-grid stats-grid-superadmin">
            <!-- Jumlah Hadir -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Karyawan Hadir</span>
                    <div class="stat-icon icon-present">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $jumlahHadir ?? 0 }}</div>
                <div class="stat-change positive">Karyawan yang masuk hari ini</div>
            </div>

            <!-- Total Karyawan -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Karyawan</span>
                    <div class="stat-icon icon-total">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalKaryawan ?? 0 }}</div>
                <div class="stat-change">Seluruh karyawan terdaftar</div>
            </div>

            <!-- Karyawan Libur -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Karyawan Libur</span>
                    <div class="stat-icon icon-libur">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $jumlahLibur ?? 0 }}</div>
                <div class="stat-change warning">Karyawan yang sedang libur</div>
            </div>
        </div>
    </div>
</div>
@endsection