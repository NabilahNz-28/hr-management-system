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
    
    /* Styling khusus Modal Pilih Produk agar persis desain UI modern */
    #product-select-trigger {
        cursor: pointer;
        background: #ffffff;
        border-radius: 8px;
        padding: 10px 14px;
        min-height: 42px;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #product-select-trigger:hover {
        border-color: #3b82f6;
        background: #f8fafc;
    }
    .modal-product-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }
    .modal-product-item:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
    }
    .modal-product-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #64748b;
        font-size: 20px;
    }
    .btn-select-product {
        background: #232b38;
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .btn-select-product:hover {
        background: #0f172a;
        transform: scale(1.02);
    }
    #modal-search-input:focus {
        border-color: #3b82f6 !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUpModal {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="mb-4 border-bottom pb-3">
        <h3 class="content-title mb-0 border-0 pb-0">Tambahkan Stok Opname</h3>
    </div>

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
                <div id="product-select-trigger" onclick="openProductModal()">
                    <span id="selected-product-text" style="color: #64748b; font-size: 14px;">Pilih Produk</span>
                    <i class="bi bi-search" style="color: #94a3b8; font-size: 14px;"></i>
                </div>
                <input type="hidden" id="produk" value="">
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

    <!-- Modal Cari Produk (SEO / Realtime Search Sesuai UI Tambah Produk) -->
    <div id="product-search-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); z-index: 99999; align-items: center; justify-content: center; padding: 16px; backdrop-filter: blur(4px); animation: fadeInModal 0.2s ease;">
        <div style="background: white; width: 100%; max-width: 540px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); overflow: hidden; display: flex; flex-direction: column; max-height: 85vh; animation: slideUpModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
            <!-- Header Modal -->
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom" style="background: #ffffff; border-bottom: 1px solid #f1f5f9 !important;">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" onclick="closeProductModal()" style="background: transparent; border: none; font-size: 20px; color: #475569; cursor: pointer; padding: 4px; display: flex; align-items: center;">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <h5 class="m-0" style="font-weight: 600; font-size: 18px; color: #1e293b;">Tambah Produk</h5>
                </div>
                <button type="button" onclick="closeProductModal()" style="background: transparent; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; display: flex; align-items: center;">
                    <i class="bi bi-x-lg" style="font-size: 16px;"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="px-4 pt-3 pb-2" style="background: #ffffff;">
                <div style="position: relative; width: 100%;">
                    <i class="bi bi-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px;"></i>
                    <input type="text" id="modal-search-input" placeholder="Cari nama produk disini" autocomplete="off"
                        style="width: 100%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px 12px 42px; font-size: 14px; color: #1e293b; outline: none; transition: all 0.2s;">
                </div>
            </div>

            <!-- List Produk -->
            <div id="modal-product-list" class="px-4 py-2" style="overflow-y: auto; flex: 1; max-height: 480px;">
                <!-- Rendered by JavaScript -->
            </div>

            <!-- Empty State -->
            <div id="modal-empty-state" class="px-4 py-5 text-center" style="display: none;">
                <div style="font-size: 2.2rem; margin-bottom: 8px;">🔍</div>
                <div style="font-weight: 600; font-size: 15px; color: #334155;">Produk Tidak Ditemukan</div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Coba gunakan kata kunci pencarian atau nama produk lainnya.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const allProducts = @json($barang ?? []);

    document.getElementById('clear-input').addEventListener('click', function() {
        document.getElementById('produk').value = '';
        const triggerText = document.getElementById('selected-product-text');
        triggerText.textContent = 'Pilih Produk';
        triggerText.style.color = '#64748b';
        triggerText.style.fontWeight = 'normal';
        document.getElementById('jumlah').value = '0';
    });

    // Modal Functions
    window.openProductModal = function() {
        const modal = document.getElementById('product-search-modal');
        modal.style.display = 'flex';
        document.getElementById('modal-search-input').value = '';
        renderModalProducts(allProducts);
        setTimeout(() => {
            document.getElementById('modal-search-input').focus();
        }, 100);
    };

    window.closeProductModal = function() {
        document.getElementById('product-search-modal').style.display = 'none';
    };

    // Tutup modal jika klik di luar area modal card
    document.getElementById('product-search-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });

    // Tutup modal jika tekan Esc
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('product-search-modal').style.display === 'flex') {
            closeProductModal();
        }
    });

    document.getElementById('modal-search-input').addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase().trim();
        const filtered = allProducts.filter(item => {
            const nama = (item.nama_barang || '').toLowerCase();
            const kat = (item.kategori || '').toLowerCase();
            return nama.includes(keyword) || kat.includes(keyword);
        });
        renderModalProducts(filtered);
    });

    function renderModalProducts(list) {
        const container = document.getElementById('modal-product-list');
        const emptyState = document.getElementById('modal-empty-state');

        if (!list || list.length === 0) {
            container.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        container.innerHTML = list.map(item => {
            const safeName = (item.nama_barang || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            const kat = item.kategori ? item.kategori.charAt(0).toUpperCase() + item.kategori.slice(1) : 'Other';
            const stok = item.stok_fisik ?? 0;
            return `
                <div class="modal-product-item">
                    <div class="d-flex align-items-center gap-3" style="min-width: 0; flex: 1;">
                        <div class="modal-product-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 600; font-size: 15px; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${safeName}">${safeName}</div>
                            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">${stok} Pcs di Inventory (${kat})</div>
                        </div>
                    </div>
                    <button type="button" class="btn-select-product ms-3" onclick="selectProductFromModal('${safeName}')">
                        Pilih Produk
                    </button>
                </div>
            `;
        }).join('');
    }

    window.selectProductFromModal = function(nama_barang) {
        // Decode entity characters jika ada
        const decodedName = nama_barang.replace(/&quot;/g, '"').replace(/&#39;/g, "'");
        
        document.getElementById('produk').value = decodedName;
        const triggerText = document.getElementById('selected-product-text');
        triggerText.textContent = decodedName;
        triggerText.style.color = '#1e293b';
        triggerText.style.fontWeight = '600';
        
        closeProductModal();
        
        const jumlahInput = document.getElementById('jumlah');
        if (jumlahInput) {
            jumlahInput.focus();
            jumlahInput.select();
        }
    };

    let products = [];

    document.getElementById('add-btn').addEventListener('click', function() {
        const produkNama = document.getElementById('produk').value.trim();
        const jumlah = parseInt(document.getElementById('jumlah').value);

        if (!produkNama) {
            // Jika produk belum dipilih, langsung buka modal pencarian agar efisien
            openProductModal();
            return;
        }

        if (!jumlah || jumlah <= 0) {
            if (typeof window.showFormalAlert === 'function') {
                window.showFormalAlert('Jumlah produk harus lebih dari 0.', 'warning', 'Peringatan');
            } else {
                alert('Jumlah produk harus lebih dari 0.');
            }
            return;
        }

        if (products.some(p => p.nama === produkNama)) {
            if (typeof window.showFormalAlert === 'function') {
                window.showFormalAlert('Produk ini sudah ditambahkan ke dalam daftar.', 'warning', 'Peringatan');
            } else {
                alert('Produk ini sudah ditambahkan ke dalam daftar.');
            }
            return;
        }

        products.push({ nama: produkNama, jumlah: jumlah });
        renderProductList();

        document.getElementById('produk').value = '';
        const triggerText = document.getElementById('selected-product-text');
        triggerText.textContent = 'Pilih Produk';
        triggerText.style.color = '#64748b';
        triggerText.style.fontWeight = 'normal';
        document.getElementById('jumlah').value = '0';

        if (typeof window.showToast === 'function') {
            window.showToast('Produk berhasil ditambahkan ke daftar', 'success');
        }
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
    };

    // Validasi sebelum form benar-benar dikirim ke server
    document.getElementById('opname-form').addEventListener('submit', function(e) {
        const tanggal = document.getElementById('tanggal').value;

        if (!tanggal) {
            e.preventDefault();
            if (typeof window.showFormalAlert === 'function') {
                window.showFormalAlert('Tanggal harus diisi.', 'warning', 'Peringatan');
            } else {
                alert('Tanggal harus diisi.');
            }
            return;
        }

        if (products.length === 0) {
            e.preventDefault();
            if (typeof window.showFormalAlert === 'function') {
                window.showFormalAlert('Tambahkan minimal satu produk sebelum menyimpan.', 'warning', 'Peringatan');
            } else {
                alert('Tambahkan minimal satu produk sebelum menyimpan.');
            }
            return;
        }
    });
</script>
@endsection