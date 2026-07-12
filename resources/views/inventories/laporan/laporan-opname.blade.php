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



    {{-- Filter --}}
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important;">
        <div class="card-body">
            <form action="{{ route('inventory.laporan-opname') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label class="form-label" style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px; min-height: 42px;">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label class="form-label" style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px; min-height: 42px;">
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label" style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Kategori</label>
                        <select class="form-select" name="kategori" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 36px 8px 12px; font-size: 13.5px; min-height: 42px; line-height: 1.5;">
                            <option value="">Semua Kategori</option>
                            <option value="eco" {{ request('kategori') == 'eco' ? 'selected' : '' }}>Eco</option>
                            <option value="fragile" {{ request('kategori') == 'fragile' ? 'selected' : '' }}>Fragile</option>
                            <option value="plastic" {{ request('kategori') == 'plastic' ? 'selected' : '' }}>Plastic</option>
                            <option value="thermal" {{ request('kategori') == 'thermal' ? 'selected' : '' }}>Thermal</option>
                            <option value="carton" {{ request('kategori') == 'carton' ? 'selected' : '' }}>Carton</option>
                        </select>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-black d-inline-flex align-items-center justify-content-center" style="padding: 0 16px; min-height: 42px; border-radius: 8px; font-size: 13.5px; font-weight: 500; white-space: nowrap;">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('inventory.laporan-opname') }}" class="btn btn-secondary d-inline-flex align-items-center justify-content-center" style="padding: 0 16px; min-height: 42px; border-radius: 8px; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 13.5px; font-weight: 500; white-space: nowrap; text-decoration: none;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                        <a href="{{ route('inventory.laporan-opname.export', request()->query()) }}" class="btn btn-success d-inline-flex align-items-center justify-content-center shadow-sm" style="padding: 0 18px; min-height: 42px; border-radius: 8px; background-color: #10b981; color: #ffffff; border: none; font-size: 13.5px; font-weight: 600; white-space: nowrap; text-decoration: none;">
                            <i class="bi bi-file-earmark-excel me-1"></i> Ekspor Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Invoice Stock Opname</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-visible;">
                <table class="data-table table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Total Selisih</th>
                            <th>Catatan</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan ?? [] as $index => $inv)
                        <tr>
                            <td data-label="No">{{ ($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator ? $laporan->firstItem() + $index : $index + 1) }}</td>
                            <td data-label="No. Invoice">
                                <span style="font-weight: 600; color: #1e293b;">{{ $inv['invoice_no'] }}</span>
                            </td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($inv['tanggal'])->format('d M Y') }}</td>
                            <td data-label="Produk">
                                <div style="font-weight: 600; color: #1e293b;">
                                    <span class="badge" style="background:#f1f5f9; color:#334155; padding: 4px 8px; border-radius: 6px; font-size:12px;">{{ $inv['item_count'] }} Produk</span>
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $inv['produk_names'] }}">
                                    {{ $inv['produk_names'] }}
                                </div>
                            </td>
                            <td data-label="Kategori">
                                <span style="font-size: 13px; color: #475569;">{{ $inv['kategori_list'] ?: '-' }}</span>
                            </td>
                            <td data-label="Total Selisih">
                                @if($inv['total_selisih'] > 0)
                                    <span style="color: #22c55e; font-weight: 600;">+{{ $inv['total_selisih'] }} pcs</span>
                                @elseif($inv['total_selisih'] < 0)
                                    <span style="color: #ef4444; font-weight: 600;">{{ $inv['total_selisih'] }} pcs</span>
                                @else
                                    <span style="color: #64748b; font-weight: 600;">0 pcs</span>
                                @endif
                            </td>
                            <td data-label="Catatan">{{ $inv['catatan'] ?: '-' }}</td>
                            <td data-label="" class="text-center" style="position: relative;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle d-inline-flex align-items-center justify-content-center shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 12px; background: #ffffff; color: #334155; font-weight: 500; font-size: 13px; white-space: nowrap;">
                                        <i class="bi bi-three-dots me-1"></i> Opsi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" style="border-radius: 8px; border: 1px solid #e2e8f0; z-index: 1050;">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modalOpnameDetail{{ $index }}" style="font-size: 13px; padding: 8px 16px;">
                                                <i class="bi bi-eye me-2 text-primary"></i> Lihat Detail
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('inventory.laporan-opname.batalkan') }}" method="POST" onsubmit="return window.confirmFormSubmit(event, 'Yakin ingin membatalkan transaksi opname pada tanggal ini? Stok barang akan dikembalikan seperti semula.', 'Batalkan Transaksi Opname');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="tanggal" value="{{ $inv['tanggal'] }}">
                                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger" style="font-size: 13px; padding: 8px 16px;">
                                                    <i class="bi bi-x-circle me-2"></i> Batalkan Transaksi
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                {{-- Modal Detail Invoice --}}
                                <div class="modal fade text-start" id="modalOpnameDetail{{ $index }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
                                            <div class="modal-header" style="background: #1e293b; color: #ffffff; padding: 16px 24px;">
                                                <h5 class="modal-title" style="font-size: 16px; font-weight: 600;">
                                                    Detail Transaksi — {{ $inv['invoice_no'] }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4" style="background: #f8fafc;">
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div style="font-size: 12px; color: #64748b;">Tanggal Transaksi:</div>
                                                        <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ \Carbon\Carbon::parse($inv['tanggal'])->format('d F Y') }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="badge" style="background: #dcfce7; color: #16a34a; padding: 6px 14px; border-radius: 20px; font-size: 12px;">Selesai</span>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden;">
                                                    <table class="table mb-0" style="font-size: 13px;">
                                                        <thead style="background: #f1f5f9; color: #475569;">
                                                            <tr>
                                                                <th>Nama Barang</th>
                                                                <th>Kategori</th>
                                                                <th>Stok Sebelum</th>
                                                                <th>Stok Sesudah</th>
                                                                <th>Selisih</th>
                                                                <th>Catatan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($inv['items'] as $item)
                                                            <tr>
                                                                <td data-label="Nama Barang" style="font-weight: 600; color: #1e293b;">{{ $item->inventory->nama_barang ?? '-' }}</td>
                                                                <td data-label="Kategori">{{ ucfirst($item->inventory->kategori ?? '-') }}</td>
                                                                <td data-label="Stok Sebelum">{{ $item->stok_sebelum }} pcs</td>
                                                                <td data-label="Stok Sesudah">{{ $item->stok_sesudah }} pcs</td>
                                                                <td data-label="Selisih">
                                                                    @if($item->selisih > 0)
                                                                        <span style="color: #22c55e; font-weight: 600;">+{{ $item->selisih }}</span>
                                                                    @elseif($item->selisih < 0)
                                                                        <span style="color: #ef4444; font-weight: 600;">{{ $item->selisih }}</span>
                                                                    @else
                                                                        <span>0</span>
                                                                    @endif
                                                                </td>
                                                                <td data-label="Catatan">{{ $item->catatan ?: '-' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="background: #ffffff; padding: 12px 24px; border-top: 1px solid #e2e8f0;">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 6px; padding: 8px 16px;">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: #94a3b8;">
                                Belum ada data invoice opname.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator && $laporan->total() > 0)
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan <strong>{{ $laporan->firstItem() ?? 0 }}</strong>–<strong>{{ $laporan->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $laporan->total() }}</strong> laporan opname
                </div>

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
            </div>
            @endif
        </div>
    </div>

</div>
@endsection