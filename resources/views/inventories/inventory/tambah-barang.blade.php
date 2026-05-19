@extends('layouts.pic')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-content">
    <h3 class="content-title">Form Tambah Barang Stock Opname</h3>
    
    <form id="item-form" action="#" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="item-name">Nama Barang</label>
                <input type="text" class="form-control" id="item-name" name="item_name" placeholder="Contoh: Box Eco 250ml" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="item-category">Kategori</label>
                <select class="form-select" id="item-category" name="item_category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="eco">Eco</option>
                    <option value="fragile">Fragile</option>
                    <option value="plastic">Plastic</option>
                    <option value="thermal">Thermal</option>
                    <option value="carton">Carton</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="item-pcs">Jumlah (Pcs)</label>
                <input type="number" class="form-control" id="item-pcs" name="item_pcs" placeholder="0" min="0" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="item-carton">Jumlah (Carton)</label>
                <input type="number" class="form-control" id="item-carton" name="item_carton" placeholder="0" min="0">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="item-notes">Catatan (Opsional)</label>
            <textarea class="form-control" id="item-notes" name="item_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
        </div>
        
        <div class="action-buttons mt-4" style="gap: 15px;">
            <button type="submit" class="btn btn-black">Submit</button>
            <a href="{{ route('inventory.stock-opname') }}" class="btn" style="background-color: #e2e8f0; color: #334155;">Batal</a>
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