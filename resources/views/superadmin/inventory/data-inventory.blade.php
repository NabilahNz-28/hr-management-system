@extends('layouts.superadmin')

@section('title', 'Data Inventory')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-title">Data Inventory</h1>
            <p class="page-subtitle">Rekap stock opname seluruh PIC</p>
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
            <form action="{{ route('superadmin.inventory.index') }}" method="GET">
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
                        <label class="form-label" style="font-size: 14px; font-weight: 500;">Kategori</label>
                        <select class="form-control" name="kategori">
                            <option value="">Semua Kategori</option>
                            <option value="eco" {{ request('kategori') == 'eco' ? 'selected' : '' }}>Eco</option>
                            <option value="fragile" {{ request('kategori') == 'fragile' ? 'selected' : '' }}>Fragile</option>
                            <option value="plastic" {{ request('kategori') == 'plastic' ? 'selected' : '' }}>Plastic</option>
                            <option value="thermal" {{ request('kategori') == 'thermal' ? 'selected' : '' }}>Thermal</option>
                            <option value="carton" {{ request('kategori') == 'carton' ? 'selected' : '' }}>Carton</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-black" style="padding: 10px 16px; border-radius: 8px;">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('superadmin.inventory.index') }}" class="btn btn-secondary" style="padding: 10px 16px; border-radius: 8px; background-color: #e2e8f0; color: #334155; border: none;">
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
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Stock Opname</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Selisih</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
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
                            <td colspan="9" class="text-center" style="padding: 40px; color: #94a3b8;">
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