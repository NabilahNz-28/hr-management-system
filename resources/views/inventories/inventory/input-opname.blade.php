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
    <div class="mb-4 border-bottom pb-3">
        <h3 class="content-title mb-0 border-0 pb-0">Tambahkan Stok Opname</h3>
    </div>

    @if($errors->any())
        <div class="success-message" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if(($barang ?? collect())->isEmpty())
        <div class="success-message" style="background:#fef3c7;border:1px solid #fbbf24;color:#92400e;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
            Belum ada barang terdaftar. Tambahkan barang dulu lewat menu <a href="{{ route('inventory.tambah-barang') }}">Tambah Barang</a>.
        </div>
    @endif

    <form id="opname-form" method="POST" action="{{ route('inventory.input-opname.store') }}">
        @csrf

        <!-- Tanggal -->
        <div class="form-group">
            <label class="form-label">Tanggal Stok Opname</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
        </div>

        <!-- Catatan -->
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" id="catatan" name="catatan" placeholder="Catatan" value="{{ old('catatan') }}">
        </div>

        <!-- Pilih Produk & Jumlah -->
        <div class="product-input-row mt-4">
            <div class="form-group">
                <label class="form-label">Pilih Produk</label>
                <select class="form-control" id="produk">
                    <option value="">Pilih Produk</option>
                    @foreach($barang ?? [] as $item)
                        <option value="{{ $item->nama_barang }}">{{ $item->nama_barang }} ({{ ucfirst($item->kategori) }}) - stok {{ $item->stok_fisik }} pcs</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah (stok fisik aktual)</label>
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

        <!-- List Produk yang Sudah Ditambah (berisi hidden input yang dikirim ke server) -->
        <div class="product-list" id="product-list"></div>

        <div class="action-buttons mt-4 pt-3 border-top d-flex gap-2">
            <button type="submit" class="btn btn-black flex-fill py-2" id="save-btn">Simpan</button>
            <a href="{{ route('inventory.stock-opname') }}" class="btn flex-fill py-2 text-center" style="background-color: #e2e8f0; color: #334155; text-decoration: none;">Batal</a>
        </div>
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
            alert('Pilih produk terlebih dahulu!');
            return;
        }

        if (!jumlah || jumlah <= 0) {
            alert('Jumlah harus lebih dari 0!');
            return;
        }

        if (products.some(p => p.nama === produkNama)) {
            alert('Produk ini sudah ditambahkan.');
            return;
        }

        products.push({ nama: produkNama, jumlah: jumlah });
        renderProductList();

        document.getElementById('produk').value = '';
        document.getElementById('jumlah').value = '0';
    });

    function renderProductList() {
        const productList = document.getElementById('product-list');

        // Render daftar + hidden input agar ikut ter-submit ke server
        productList.innerHTML = products.map((product, index) => `
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name" style="font-weight: 500; font-size: 14px; color: #1e293b;">${product.nama}</div>
                    <div class="product-qty" style="color: #64748b; font-size: 13px; margin-top: 2px;">Jumlah: ${product.jumlah}</div>
                </div>
                <button type="button" class="btn-delete" onclick="deleteProduct(${index})">Hapus</button>
                <input type="hidden" name="produk[${index}][nama]" value="${product.nama.replace(/"/g, '&quot;')}">
                <input type="hidden" name="produk[${index}][jumlah]" value="${product.jumlah}">
            </div>
        `).join('');
    }

    window.deleteProduct = function(index) {
        products.splice(index, 1);
        renderProductList();
    }

    // Validasi sebelum form benar-benar dikirim ke server
    document.getElementById('opname-form').addEventListener('submit', function(e) {
        const tanggal = document.getElementById('tanggal').value;

        if (!tanggal) {
            e.preventDefault();
            alert('Tanggal harus diisi!');
            return;
        }

        if (products.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 produk!');
            return;
        }
    });
</script>
@endsection