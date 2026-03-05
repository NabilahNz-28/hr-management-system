@extends('layouts.app')

@section('title', 'Transfer Stock')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transfer Stock</h1>
        <span class="badge badge-primary">Gudang Utama → Gudang Tujuan</span>
    </div>

    <!-- Transfer Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Transfer Stock</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('inventories.transfer-stock.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hari Tanggal *</label>
                            <input type="date" class="form-control" name="tanggal" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gudang Tujuan *</label>
                            <input type="text" class="form-control" name="gudang_tujuan" 
                                   placeholder="Contoh: Gudang Cabang Bandung" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Barang *</label>
                            <select class="form-control" name="barang_id" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama_barang }} ({{ $item->kategori }}) - {{ $item->stok_fisik }} pcs tersedia
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jumlah *</label>
                            <input type="number" class="form-control" name="jumlah" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Satuan *</label>
                            <select class="form-control" name="satuan" required>
                                <option value="pcs">Pcs</option>
                                <option value="carton">Carton (24 pcs)</option>
                                <option value="box">Box (12 pcs)</option>
                                <option value="pack">Pack (6 pcs)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea class="form-control" name="catatan" rows="3" 
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Transfer dilakukan dari <strong>Gudang Utama</strong> ke 
                    <strong id="dest-preview">[Gudang Tujuan]</strong>. 
                    Gudang utama tidak dapat diganti.
                </div>
                
                <button type="submit" class="btn btn-dark btn-lg btn-block">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Transfer
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Transfers -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transfer Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Ke Gudang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfer_terbaru ?? [] as $transfer)
                        <tr>
                            <td>{{ $transfer->tanggal->format('d M Y') }}</td>
                            <td>{{ $transfer->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $transfer->ke_gudang }}</td>
                            <td>{{ $transfer->jumlah }} pcs</td>
                            <td>
                                @if($transfer->status == 'Selesai')
                                    <span class="badge badge-success">{{ $transfer->status }}</span>
                                @else
                                    <span class="badge badge-warning">{{ $transfer->status }}</span>
                                @endif
                            </td>
                            <td>{{ $transfer->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transfer</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update destination preview
        const gudangInput = document.querySelector('input[name="gudang_tujuan"]');
        const preview = document.getElementById('dest-preview');
        
        gudangInput.addEventListener('input', function() {
            preview.textContent = this.value || '[Gudang Tujuan]';
        });
        
        // Auto-check stock
        const barangSelect = document.querySelector('select[name="barang_id"]');
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        const satuanSelect = document.querySelector('select[name="satuan"]');
        
        function checkStock() {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            if (selectedOption.value) {
                const text = selectedOption.text;
                const stockMatch = text.match(/\((\d+) pcs tersedia\)/);
                if (stockMatch) {
                    const availableStock = parseInt(stockMatch[1]);
                    console.log(`Stok tersedia: ${availableStock} pcs`);
                }
            }
        }
        
        barangSelect.addEventListener('change', checkStock);
    });
</script>
@endsection