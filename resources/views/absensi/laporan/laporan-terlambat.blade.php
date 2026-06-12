@extends('layouts.absen')

@section('title', 'Laporan Keterlambatan')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
@endphp
<div class="dashboard-content">
    <div class="page-content active" id="keterlambatan-bulanan">
        <div class="content-title">Riwayat Keterlambatan</div>
        <p class="content-description">Catatan keterlambatan per bulan</p>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('laporan.terlambat') }}" class="filter-section">
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

        <!-- Info Summary -->
        <div class="info-summary">
            <p>Periode: <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong></p>
            <p>Total Keterlambatan: <strong>{{ $total }} kali</strong></p>
        </div>

        <!-- Tabel atau pesan -->
        @if($total === 0)
            <div class="success-message">
                <div class="emoji-big">✅</div>
                <h3>Luar Biasa!</h3>
                <p>Anda tidak memiliki keterlambatan sama sekali di bulan {{ $namaBulan[$bulan] }} {{ $tahun }}.</p>
                <p>Pertahankan kedisiplinan Anda!</p>
                <div class="stars">⭐⭐⭐⭐⭐</div>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table-laporan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($terlambat as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                            <td>{{ $item['jam_masuk'] }}</td>
                            <td>{{ $item['menit'] }} menit</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
