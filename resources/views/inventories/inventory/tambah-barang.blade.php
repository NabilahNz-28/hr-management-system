@extends('layouts.pic')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-content">
    <h3 class="content-title">Form Tambah Barang Stock Opname</h3>
    
    <form id="item-form" action="{{ route('inventory.tambah-barang.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="item-name">Nama Barang</label>
                <input type="text" class="form-control" id="item-name" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Box Eco 250ml" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="item-category">Kategori</label>
                <select class="form-select" id="item-category" name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <option value="eco" {{ old('kategori') == 'eco' ? 'selected' : '' }}>Eco</option>
                    <option value="fragile" {{ old('kategori') == 'fragile' ? 'selected' : '' }}>Fragile</option>
                    <option value="plastic" {{ old('kategori') == 'plastic' ? 'selected' : '' }}>Plastic</option>
                    <option value="thermal" {{ old('kategori') == 'thermal' ? 'selected' : '' }}>Thermal</option>
                    <option value="carton" {{ old('kategori') == 'carton' ? 'selected' : '' }}>Carton</option>
                    <option value="other" {{ old('kategori') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="item-pcs">Jumlah (Pcs)</label>
                <input type="number" class="form-control" id="item-pcs" name="jumlah_pcs" value="{{ old('jumlah_pcs') }}" placeholder="0" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="item-carton">Jumlah (Carton)</label>
                <input type="number" class="form-control" id="item-carton" name="jumlah_carton" value="{{ old('jumlah_carton') }}" placeholder="0" min="0">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="item-notes">Catatan (Opsional)</label>
            <textarea class="form-control" id="item-notes" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
        </div>
        
        <div class="action-buttons mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-black flex-fill py-2">Submit</button>
            <a href="{{ route('inventory.stock-opname') }}" class="btn flex-fill py-2 text-center" style="background-color: #e2e8f0; color: #334155; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('item-form').addEventListener('submit', function(e) {
        // e.preventDefault(); 
        // alert('Form akan di-submit ke backend Laravel!');
    });
</script>
@endsection