@extends('layouts.superadmin')

@section('title', 'Transfer Stock')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="transfer-stock">
        <div class="content-title">Transfer Stock</div>
        <p class="content-description">Rekap transfer stock antar gudang seluruh PIC</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filter --}}
        <form action="{{ route('superadmin.transfer.index') }}" method="GET" class="inventory-filter">
            <div class="filter-field">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="filter-field">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="filter-field">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="">Semua Status</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-small">Filter</button>
                <a href="{{ route('superadmin.transfer.index') }}" class="btn btn-secondary btn-small">Reset</a>
            </div>
        </form>

        {{-- Tabel Laporan --}}
        <div class="table-responsive">
            <table class="data-table-laporan">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="11%">Tanggal</th>
                        <th width="13%">Petugas</th>
                        <th width="13%">Barang</th>
                        <th width="12%">Gudang Asal</th>
                        <th width="12%">Ke Gudang</th>
                        <th width="8%">Jumlah</th>
                        <th width="8%">Satuan</th>
                        <th width="9%">Status</th>
                        <th width="9%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan ?? [] as $index => $item)
                    <tr>
                        <td class="text-center">{{ ($laporan->firstItem() ?? 1) + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td><span style="font-weight: 500; color: #1e293b;">Gudang Utama</span></td>
                        <td>{{ $item->ke_gudang }}</td>
                        <td>{{ $item->jumlah }} pcs</td>
                        <td style="text-transform: capitalize;">{{ $item->satuan }}</td>
                        <td>
                            @if($item->status === 'Selesai')
                                <span class="status-badge-tf status-selesai">Selesai</span>
                            @elseif($item->status === 'Dibatalkan')
                                <span class="status-badge-tf status-batal">Dibatalkan</span>
                            @else
                                <span class="status-badge-tf status-pending">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <div class="empty-state">
                                <div class="empty-icon">🔄</div>
                                <p>Belum ada data transfer.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($laporan) && $laporan->total() > 0)
        <div class="pagination-container">
            <div class="pagination-info">
                Menampilkan <strong>{{ $laporan->firstItem() }}</strong>–<strong>{{ $laporan->lastItem() }}</strong>
                dari <strong>{{ $laporan->total() }}</strong> data transfer
            </div>

            @if($laporan->hasPages())
            <nav class="pagination-nav">
                @if($laporan->onFirstPage())
                    <span class="page-btn disabled">‹</span>
                @else
                    <a href="{{ $laporan->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
                @endif

                @foreach($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                    @if($page == $laporan->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($laporan->hasMorePages())
                    <a href="{{ $laporan->nextPageUrl() }}" class="page-btn" rel="next">›</a>
                @else
                    <span class="page-btn disabled">›</span>
                @endif
            </nav>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
/* Filter bar */
.inventory-filter {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 16px;
    margin-bottom: 24px;
    padding: 20px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}
.inventory-filter .filter-field { flex: 1 1 180px; }
.inventory-filter .form-label { font-size: 14px; font-weight: 500; color: #334155; margin-bottom: 8px; }
.inventory-filter .filter-actions { display: flex; gap: 8px; }
.btn-secondary { background: #e2e8f0; color: #334155; }
.btn-secondary:hover { background: #cbd5e1; }

/* Status badges */
.status-badge-tf {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-selesai { background: #dcfce7; color: #16a34a; }
.status-pending { background: #fef9c3; color: #ca8a04; }
.status-batal   { background: #fee2e2; color: #dc2626; }

.empty-state { padding: 20px; }
.empty-icon { font-size: 2rem; margin-bottom: 8px; }

/* Pagination */
.pagination-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
}
.pagination-info { color: #6b7280; font-size: 0.9rem; }
.pagination-nav { display: flex; align-items: center; gap: 6px; }
.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #374151;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}
.page-btn:hover:not(.disabled):not(.active) { background: #f3f4f6; border-color: #d1d5db; }
.page-btn.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }
.page-btn.disabled { color: #cbd5e1; cursor: not-allowed; background: #f9fafb; }

@media (max-width: 576px) {
    .pagination-container { justify-content: center; }
    .inventory-filter .filter-field { flex: 1 1 100%; }
}
</style>
@endsection
