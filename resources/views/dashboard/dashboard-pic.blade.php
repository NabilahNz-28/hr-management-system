@extends('layouts.pic')

@section('title', 'Dashboard PIC Inventory')

@section('content')
<div id="dashboard-home" class="dashboard-home-container">
    <div class="welcome-section">
        <h1 class="page-title">Dashboard PIC Inventory</h1>
        <p class="page-subtitle">Ringkasan aktivitas inventory bulan ini</p>
    </div>

    {{-- Holder untuk statistik --}}
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-title">Ringkasan Inventory</h3>
            <span class="content-badge">Bulan Ini</span>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Transaksi Opname Bulan Ini</div>
                    <div class="stat-icon icon-opname">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalOpname ?? '0' }}</div>
                <div class="stat-change positive">Data opname bulan berjalan</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Transfer Stock Bulan Ini</div>
                    <div class="stat-icon icon-transfer">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalTransfer ?? '0' }}</div>
                <div class="stat-change positive">Data transfer stock bulan berjalan</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Barang</div>
                    <div class="stat-icon icon-inventory">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalBarang ?? '0' }}</div>
                <div class="stat-change positive">Jumlah seluruh barang terdaftar</div>
            </div>
        </div>
    </div>

    {{-- Holder untuk tabel aktivitas --}}
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h3 class="content-title">Aktivitas Terbaru</h3>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Aktivitas</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktivitasList ?? [] as $aktivitas)
                        <tr>
                            <td>
                                {{ isset($aktivitas->tanggal) ? \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') : '-' }}
                            </td>
                            <td>{{ $aktivitas->jenis ?? '-' }}</td>
                            <td>{{ $aktivitas->nama_barang ?? '-' }}</td>
                            <td>{{ $aktivitas->jumlah ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection