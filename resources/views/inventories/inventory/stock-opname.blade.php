@extends('layouts.pic')

@section('title', 'Stock Opname')

@section('content')
<div id="stock-opname" class="page-content">
                <h1 class="page-title">Stock Opname</h1>
                <p class="page-subtitle">Kelola stock opname inventory</p>

                <div class="content-description">
                    <p>Pilih kategori untuk melihat barang yang tersedia:</p>
                </div>
                
                <div class="category-filter" style="align-items: flex-start;">

                    <!-- Tombol kategori (kiri) -->
                    <div class="category-buttons" style="margin-top: 5px;">
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'all'])) }}" class="category-btn {{ request('kategori', 'all') == 'all' ? 'active' : '' }}">Semua Kategori</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'eco'])) }}" class="category-btn {{ request('kategori') == 'eco' ? 'active' : '' }}">Eco</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'fragile'])) }}" class="category-btn {{ request('kategori') == 'fragile' ? 'active' : '' }}">Fragile</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'plastic'])) }}" class="category-btn {{ request('kategori') == 'plastic' ? 'active' : '' }}">Plastic</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'thermal'])) }}" class="category-btn {{ request('kategori') == 'thermal' ? 'active' : '' }}">Thermal</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'carton'])) }}" class="category-btn {{ request('kategori') == 'carton' ? 'active' : '' }}">Carton</a>
                        <a href="{{ route('inventory.stock-opname', array_merge(request()->query(), ['kategori' => 'other'])) }}" class="category-btn {{ request('kategori') == 'other' ? 'active' : '' }}">Other</a>
                    </div>

                    <!-- Kolom Kanan: Search Bar + Tombol Aksi -->
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                        <form method="GET" action="{{ route('inventory.stock-opname') }}" style="display: flex; gap: 10px; width: 100%; min-width: 300px; justify-content: flex-end;">
                            @if(request('kategori'))
                                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                            @endif
                            <input type="text" name="search" placeholder="Cari nama barang..." value="{{ request('search') }}" style="flex: 1; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none;">
                            <button type="submit" class="btn btn-black" style="background-color: #0f172a; color: white; padding: 10px 15px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer; transition: background 0.3s;">
                                <i class="bi bi-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('inventory.stock-opname', ['kategori' => request('kategori')]) }}" class="btn" style="background-color: #f1f5f9; color: #334155; padding: 10px 15px; border-radius: 8px; text-decoration: none;">Reset</a>
                            @endif
                        </form>

                        <!-- Tombol aksi di kanan: Tambahkan Barang + Input Opname -->
                        <div class="action-buttons">
                            <a href="{{ route('inventory.tambah-barang') }}" class="btn btn-black">
                                <i class="bi bi-plus-circle me-2"></i> Tambahkan Barang
                            </a>
                            <a href="{{ route('inventory.input-opname') }}" class="btn btn-black">
                                <i class="bi bi-card-checklist me-2"></i> Input Opname
                            </a>
                        </div>
                    </div>
                </div>

                <div class="stock-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok </th>
                                <!-- tambahkan titik 3 "detail" disini-->
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            @forelse($barang ?? [] as $item)
                            <tr class="item-row" data-category="{{ strtolower($item->kategori) }}">
                                <td>{{ $item->nama_barang }}</td>
                                <td>
                                    <span style="padding: 4px 10px; font-size: 12px; border-radius: 20px; background: #e2e8f0; font-weight: 500; color: #334155;">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                </td>
                                <td>{{ $item->stok_fisik }} pcs</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 40px; color: #94a3b8;">
                                    Belum ada barang di inventory.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($barang instanceof \Illuminate\Pagination\LengthAwarePaginator && $barang->total() > 0)
                <div class="pagination-container">
                    <div class="pagination-info">
                        Menampilkan <strong>{{ $barang->firstItem() ?? 0 }}</strong>–<strong>{{ $barang->lastItem() ?? 0 }}</strong>
                        dari <strong>{{ $barang->total() }}</strong> barang
                    </div>

                    <nav class="pagination-nav">
                        @if($barang->onFirstPage())
                            <span class="page-btn disabled">‹</span>
                        @else
                            <a href="{{ $barang->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
                        @endif

                        @foreach($barang->getUrlRange(1, $barang->lastPage()) as $page => $url)
                            @if($page == $barang->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($barang->hasMorePages())
                            <a href="{{ $barang->nextPageUrl() }}" class="page-btn" rel="next">›</a>
                        @else
                            <span class="page-btn disabled">›</span>
                        @endif
                    </nav>
                </div>
                @endif
</div>
@endsection

@section('scripts')
@endsection