@extends('layouts.pic')

@section('title', 'Laporan Opname')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-title">Laporan Opname</h1>
            <p class="page-subtitle">Laporan lengkap stock opname</p>
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- Tabel Laporan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Stock Opname</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Selisih</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->inventory->nama_barang ?? '-' }}</td>
                            <td>
                                <span class="category-btn" style="padding: 4px 10px; font-size: 12px; border-radius: 20px; background: #e2e8f0; font-weight: 500;">
                                    {{ ucfirst($item->inventory->kategori ?? '-') }}
                                </span>
                            </td>
                            <td>{{ $item->stok_sebelum }} pcs</td>
                            <td>{{ $item->stok_sesudah }} pcs</td>
                            <td>
                                @if($item->selisih > 0)
                                    <span style="color: #22c55e; font-weight: 600;">+{{ $item->selisih }}</span>
                                @elseif($item->selisih < 0)
                                    <span style="color: #ef4444; font-weight: 600;">{{ $item->selisih }}</span>
                                @else
                                    <span style="color: #64748b;">0</span>
                                @endif
                            </td>
                            <td>{{ $item->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: #94a3b8;">
                                Belum ada data opname.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection