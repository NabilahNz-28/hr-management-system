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

    {{-- Filter --}}
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important;">
        <div class="card-body">
            <form action="{{ route('inventory.laporan-transfer') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label" style="font-size: 14px; font-weight: 500;">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label" style="font-size: 14px; font-weight: 500;">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label" style="font-size: 14px; font-weight: 500;">Status</label>
                        <select class="form-control" name="status">
                            <option value="">Semua Status</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-black" style="padding: 10px 16px; border-radius: 8px;">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('inventory.laporan-transfer') }}" class="btn btn-secondary" style="padding: 10px 16px; border-radius: 8px; background-color: #e2e8f0; color: #334155; border: none;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                        @forelse($laporan ?? [] as $index => $item)
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