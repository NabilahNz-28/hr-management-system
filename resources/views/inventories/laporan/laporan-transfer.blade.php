@extends('layouts.pic')

@section('title', 'Laporan Transfer')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-title">Laporan Transfer</h1>
            <p class="page-subtitle">Laporan lengkap transfer stock antar gudang</p>
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
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Transfer Stock</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Gudang Asal</th>
                            <th>Ke Gudang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td>
                                <span style="font-weight: 500; color: #1e293b;">Gudang Utama</span>
                            </td>
                            <td>{{ $item->ke_gudang }}</td>
                            <td>{{ $item->jumlah }} pcs</td>
                            <td>
                                <span style="text-transform: capitalize;">{{ $item->satuan }}</span>
                            </td>
                            <td>
                                @if($item->status === 'Selesai')
                                    <span class="badge badge-success"
                                          style="padding: 5px 10px; border-radius: 20px; background: #dcfce7; color: #16a34a; font-size: 12px;">
                                        Selesai
                                    </span>
                                @else
                                    <span class="badge badge-warning"
                                          style="padding: 5px 10px; border-radius: 20px; background: #fef9c3; color: #ca8a04; font-size: 12px;">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $item->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 40px; color: #94a3b8;">
                                Belum ada data transfer.
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