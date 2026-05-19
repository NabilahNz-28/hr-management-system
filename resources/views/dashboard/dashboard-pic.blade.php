@extends('layouts.pic')

@section('content')
<div id="dashboard-home" class="page-content active">
    <h1 class="page-title">Dashboard PIC Inventory</h1>
    <p class="page-subtitle">Ringkasan aktivitas inventory bulan ini</p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Transaksi Opname Bulan Ini</div>
                <div class="stat-icon icon-opname">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>
            <div class="stat-value"> no add yet</div>
            <div class="stat-change positive">+12% dari bulan lalu</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Transfer Stock Bulan Ini</div>
                <div class="stat-icon icon-transfer">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
            <div class="stat-value">no add yet</div>
            <div class="stat-change positive">+8% dari bulan lalu</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Stok Menipis</div>
                <div class="stat-icon icon-pending">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-value">no add yet</div>
            <div class="stat-change negative">Perlu perhatian</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Barang</div>
                <div class="stat-icon icon-inventory">
                    <i class="bi bi-box"></i>
                </div>
            </div>
            <div class="stat-value">no add yet</div>
            <div class="stat-change positive">+5 barang baru</div>
        </div>
    </div>

    <div class="page-content">
        <h3 class="content-title">Aktivitas Terbaru</h3>
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
                    <td>{{ isset($aktivitas->tanggal) ? \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') : '-' }}</td>
                    <td>{{ $aktivitas->jenis ?? '-' }}</td>
                    <td>{{ $aktivitas->nama_barang ?? '-' }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada aktivitas</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
