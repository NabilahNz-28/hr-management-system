@extends('layouts.superadmin')

@section('title', 'Data Inventory')

@section('content')
<div class="dashboard-content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px; background-color: #dcfce7; border-color: #bbf7d0; color: #166534;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 4px;">Data Inventory</h4>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Rekap riwayat invoice stock opname dari seluruh PIC</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important;">
        <div class="card-body">
            <form action="{{ route('superadmin.inventory.index') }}" method="GET">
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
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center" style="padding: 0 16px; min-height: 42px; border-radius: 8px; font-size: 13.5px; font-weight: 500; white-space: nowrap; background-color: #1e293b; border: none;">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('superadmin.inventory.index') }}" class="btn btn-secondary d-inline-flex align-items-center justify-content-center" style="padding: 0 16px; min-height: 42px; border-radius: 8px; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 13.5px; font-weight: 500; white-space: nowrap; text-decoration: none;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                        <a href="{{ route('superadmin.inventory.export', request()->query()) }}" class="btn btn-success d-inline-flex align-items-center justify-content-center shadow-sm" style="padding: 0 18px; min-height: 42px; border-radius: 8px; background-color: #10b981; color: #ffffff; border: none; font-size: 13.5px; font-weight: 600; white-space: nowrap; text-decoration: none;">
                            <i class="bi bi-file-earmark-excel me-1"></i> Ekspor Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
            <h6 class="m-0 font-weight-bold" style="color: #1e293b; font-size: 15px;">Riwayat Invoice Stock Opname</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-visible;">
                <table class="table table-hover mb-0" width="100%" cellspacing="0" style="font-size: 13.5px;">
                    <thead style="background: #f8fafc; color: #475569; border-bottom: 1px solid #e2e8f0;">
                        <tr>
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">No. Invoice</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Petugas (PIC)</th>
                            <th class="py-3 px-3">Produk</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Total Selisih</th>
                            <th class="py-3 px-3">Catatan</th>
                            <th class="py-3 px-3 text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan ?? [] as $index => $inv)
                        <tr style="border-bottom: 1px solid #f1f5f9; vertical-align: middle;">
                            <td class="px-3">{{ ($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator ? $laporan->firstItem() + $index : $index + 1) }}</td>
                            <td class="px-3">
                                <span style="font-weight: 600; color: #1e293b;">{{ $inv['invoice_no'] }}</span>
                            </td>
                            <td class="px-3">{{ \Carbon\Carbon::parse($inv['tanggal'])->format('d M Y') }}</td>
                            <td class="px-3">
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 12px;">
                                    <i class="bi bi-person me-1"></i> {{ $inv['petugas_name'] }}
                                </span>
                            </td>
                            <td class="px-3">
                                <div style="font-weight: 600; color: #1e293b;">
                                    <span class="badge" style="background:#f1f5f9; color:#334155; padding: 4px 8px; border-radius: 6px; font-size:12px;">{{ $inv['item_count'] }} Produk</span>
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $inv['produk_names'] }}">
                                    {{ $inv['produk_names'] }}
                                </div>
                            </td>
                            <td class="px-3">
                                <span style="font-size: 13px; color: #475569;">{{ $inv['kategori_list'] ?: '-' }}</span>
                            </td>
                            <td class="px-3">
                                @if($inv['total_selisih'] > 0)
                                    <span style="color: #22c55e; font-weight: 600;">+{{ $inv['total_selisih'] }} pcs</span>
                                @elseif($inv['total_selisih'] < 0)
                                    <span style="color: #ef4444; font-weight: 600;">{{ $inv['total_selisih'] }} pcs</span>
                                @else
                                    <span style="color: #64748b; font-weight: 600;">0 pcs</span>
                                @endif
                            </td>
                            <td class="px-3">{{ $inv['catatan'] ?: '-' }}</td>
                            <td class="px-3 text-center">
                                <button type="button" class="btn btn-sm btn-light d-inline-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="modal" data-bs-target="#modalOpnameDetail{{ $index }}" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 14px; background: #ffffff; color: #1e293b; font-weight: 600; font-size: 13px; white-space: nowrap;">
                                    <i class="bi bi-eye text-primary me-1"></i> Lihat
                                </button>

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
                                                        <div style="font-size: 12px; color: #64748b;">Petugas PIC & Tanggal:</div>
                                                        <div style="font-size: 14px; font-weight: 600; color: #1e293b;">
                                                            {{ $inv['petugas_name'] }} — {{ \Carbon\Carbon::parse($inv['tanggal'])->format('d F Y') }}
                                                        </div>
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
                                                                <td style="font-weight: 600; color: #1e293b;">{{ $item->inventory->nama_barang ?? '-' }}</td>
                                                                <td>{{ ucfirst($item->inventory->kategori ?? '-') }}</td>
                                                                <td>{{ $item->stok_sebelum }} pcs</td>
                                                                <td>{{ $item->stok_sesudah }} pcs</td>
                                                                <td>
                                                                    @if($item->selisih > 0)
                                                                        <span style="color: #22c55e; font-weight: 600;">+{{ $item->selisih }}</span>
                                                                    @elseif($item->selisih < 0)
                                                                        <span style="color: #ef4444; font-weight: 600;">{{ $item->selisih }}</span>
                                                                    @else
                                                                        <span>0</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->catatan ?: '-' }}</td>
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
                            <td colspan="9" class="text-center" style="padding: 40px; color: #94a3b8;">
                                Belum ada data invoice opname.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator && $laporan->total() > 0)
            <div class="pagination-container p-3 d-flex justify-content-between align-items-center">
                <div class="pagination-info" style="font-size: 13px; color: #64748b;">
                    Menampilkan <strong>{{ $laporan->firstItem() ?? 0 }}</strong>–<strong>{{ $laporan->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $laporan->total() }}</strong> laporan opname
                </div>

                <nav class="pagination-nav">
                    @if($laporan->onFirstPage())
                        <span class="page-btn disabled" style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; color: #cbd5e1; font-size: 13px;">‹</span>
                    @else
                        <a href="{{ $laporan->previousPageUrl() }}" class="page-btn" rel="prev" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; font-size: 13px;">‹</a>
                    @endif

                    @foreach($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                        @if($page == $laporan->currentPage())
                            <span class="page-btn active" style="padding: 6px 12px; border: 1px solid #1e293b; background: #1e293b; color: #ffffff; border-radius: 6px; font-size: 13px; font-weight: 600;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; font-size: 13px;">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($laporan->hasMorePages())
                        <a href="{{ $laporan->nextPageUrl() }}" class="page-btn" rel="next" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; text-decoration: none; font-size: 13px;">›</a>
                    @else
                        <span class="page-btn disabled" style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; color: #cbd5e1; font-size: 13px;">›</span>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
