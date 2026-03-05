@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stock Opname</h1>
        <button class="btn btn-dark" data-toggle="modal" data-target="#tambahBarangModal">
            <i class="fas fa-plus mr-2"></i> Tambahkan Barang
        </button>
    </div>

    <!-- Filter Kategori -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="?kategori=all" class="btn btn-sm {{ request('kategori', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                <a href="?kategori=eco" class="btn btn-sm {{ request('kategori') == 'eco' ? 'btn-success' : 'btn-outline-success' }}">Eco</a>
                <a href="?kategori=fragile" class="btn btn-sm {{ request('kategori') == 'fragile' ? 'btn-danger' : 'btn-outline-danger' }}">Fragile</a>
                <a href="?kategori=plastic" class="btn btn-sm {{ request('kategori') == 'plastic' ? 'btn-info' : 'btn-outline-info' }}">Plastic</a>
                <a href="?kategori=thermal" class="btn btn-sm {{ request('kategori') == 'thermal' ? 'btn-warning' : 'btn-outline-warning' }}">Thermal</a>
                <a href="?kategori=carton" class="btn btn-sm {{ request('kategori') == 'carton' ? 'btn-secondary' : 'btn-outline-secondary' }}">Carton</a>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Selisih</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $item)
                        <tr>
                            <td>{{ $item->nama_barang }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'eco' => 'success',
                                        'fragile' => 'danger',
                                        'plastic' => 'info',
                                        'thermal' => 'warning',
                                        'carton' => 'secondary'
                                    ][$item->kategori] ?? 'primary';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($item->kategori) }}</span>
                            </td>
                            <td>{{ $item->stok_sistem }} pcs</td>
                            <td>{{ $item->stok_fisik }} pcs</td>
                            <td>
                                @php
                                    $selisih = $item->stok_fisik - $item->stok_sistem;
                                @endphp
                                @if($selisih > 0)
                                    <span class="text-success">+{{ $selisih }} pcs</span>
                                @elseif($selisih < 0)
                                    <span class="text-danger">{{ $selisih }} pcs</span>
                                @else
                                    <span class="text-muted">0 pcs</span>
                                @endif
                            </td>
                            <td>
                                @if($selisih == 0)
                                    <span class="badge badge-success">Sesuai</span>
                                @elseif($selisih > 0)
                                    <span class="badge badge-info">Lebih</span>
                                @else
                                    <span class="badge badge-warning">Kurang</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" 
                                        data-target="#editStockModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Modal Edit Stock -->
                        <div class="modal fade" id="editStockModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Stock Fisik</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('inventories.update-stock') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Barang</label>
                                                <input type="text" class="form-control" value="{{ $item->nama_barang }}" readonly>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Stok Sistem</label>
                                                        <input type="number" class="form-control" value="{{ $item->stok_sistem }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Stok Fisik Baru</label>
                                                        <input type="number" class="form-control" name="stok_fisik" 
                                                               value="{{ $item->stok_fisik }}" min="0" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Catatan (Opsional)</label>
                                                <textarea class="form-control" name="catatan" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-dark">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Barang Stock Opname</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('inventories.tambah-barang') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang *</label>
                                <input type="text" class="form-control" name="nama_barang" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori *</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="eco">Eco</option>
                                    <option value="fragile">Fragile</option>
                                    <option value="plastic">Plastic</option>
                                    <option value="thermal">Thermal</option>
                                    <option value="carton">Carton</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah (Pcs) *</label>
                                <input type="number" class="form-control" name="pcs" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah (Carton)</label>
                                <input type="number" class="form-control" name="carton" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto calculate total pcs
    document.addEventListener('DOMContentLoaded', function() {
        const pcsInput = document.querySelector('input[name="pcs"]');
        const cartonInput = document.querySelector('input[name="carton"]');
        
        function updateTotal() {
            const pcs = parseInt(pcsInput.value) || 0;
            const carton = parseInt(cartonInput.value) || 0;
            const totalPcs = pcs + (carton * 24);
            console.log(`Total pcs: ${totalPcs}`);
        }
        
        pcsInput.addEventListener('input', updateTotal);
        cartonInput.addEventListener('input', updateTotal);
    });
</script>
@endsection