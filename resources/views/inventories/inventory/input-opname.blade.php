@extends('layouts.pic')

@section('title', 'Input Opname')

@section('styles')
<style>
    /* Specific styles for input opname */
    .product-input-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }
    .product-input-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }
    .jumlah-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-hapus-produk {
        background: white;
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-hapus-produk:hover { background: #fef2f2; }
    .add-product-btn {
        width: 100%;
        padding: 14px;
        background: white;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 8px;
    }
    .add-product-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #f8fafc;
    }
    .product-list {
        margin-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .product-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }
    .btn-delete {
        background: transparent;
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-delete:hover { background: #fef2f2; }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h3 class="content-title mb-0 border-0 pb-0">Tambahkan Stok Opname</h3>
        <div>
            <a href="{{ route('inventory.stock-opname') }}" class="btn" style="background-color: #e2e8f0; color: #334155; margin-right: 10px;">Batal</a>
            <button type="button" class="btn btn-black" id="save-btn">Simpan</button>
        </div>
    </div>
    
    <form id="opname-form">
        <!-- Tanggal -->
        <div class="form-group">
            <label class="form-label">Tanggal Stok Opname</label>
            <input type="date" class="form-control" id="tanggal">
        </div>
        
        <!-- Catatan (TextField) -->
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" id="catatan" placeholder="Catatan">
        </div>
        
        <!-- Pilih Produk & Jumlah (Sampingan) -->
        <div class="product-input-row mt-4">
            <div class="form-group">
                <label class="form-label">Pilih Produk</label>
                <input type="text" class="form-control" id="produk" placeholder="Pilih Produk">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah</label>
                <div class="jumlah-input-group">
                    <input type="number" class="form-control" style="width: 120px;" id="jumlah" value="0" min="0">
                    <button type="button" class="btn-hapus-produk" id="clear-input">Hapus</button>
                </div>
            </div>
        </div>
        
        <!-- Tambah Produk Button -->
        <button type="button" class="add-product-btn" id="add-btn">
            + Tambah Produk
        </button>
        
        <!-- List Produk yang Sudah Ditambah -->
        <div class="product-list" id="product-list"></div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('clear-input').addEventListener('click', function() {
        document.getElementById('produk').value = '';
        document.getElementById('jumlah').value = '0';
    });
    
    let products = [];
    
    document.getElementById('add-btn').addEventListener('click', function() {
        const produkNama = document.getElementById('produk').value.trim();
        const jumlah = parseInt(document.getElementById('jumlah').value);
        
        if (!produkNama) {
            alert('Nama produk harus diisi!');
            return;
        }
        
        if (jumlah <= 0) {
            alert('Jumlah harus lebih dari 0!');
            return;
        }
        
        products.push({
            nama: produkNama,
            jumlah: jumlah
        });
        
        renderProductList();
        
        document.getElementById('produk').value = '';
        document.getElementById('jumlah').value = '0';
    });
    
    function renderProductList() {
        const productList = document.getElementById('product-list');
        
        if (products.length === 0) {
            productList.innerHTML = '';
            return;
        }
        
        productList.innerHTML = products.map((product, index) => `
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name" style="font-weight: 500; font-size: 14px; color: #1e293b;">${product.nama}</div>
                    <div class="product-qty" style="color: #64748b; font-size: 13px; margin-top: 2px;">Jumlah: ${product.jumlah}</div>
                </div>
                <button type="button" class="btn-delete" onclick="deleteProduct(${index})">
                    Hapus
                </button>
            </div>
        `).join('');
    }
    
    window.deleteProduct = function(index) {
        products.splice(index, 1);
        renderProductList();
    }
    
    document.getElementById('save-btn').addEventListener('click', function() {
        const tanggal = document.getElementById('tanggal').value;
        const catatan = document.getElementById('catatan').value.trim();
        
        if (!tanggal) {
            alert('Tanggal harus diisi!');
            return;
        }
        
        if (products.length === 0) {
            alert('Tambahkan minimal 1 produk!');
            return;
        }
        
        const formData = {
            tanggal: tanggal,
            catatan: catatan,
            products: products
        };
        
        console.log('Data Disimpan:', formData);
        alert('✅ Data berhasil disimpan!\n\nCek console untuk detail.');
    });
</script>
@endsection