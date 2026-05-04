<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Stock Opname - HR Management</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 25px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #1e293b;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        .back-link:hover {
            opacity: 0.7;
        }
        
        .back-arrow {
            font-size: 20px;
            line-height: 1;
        }
        
        .btn-batal {
            background: none;
            border: none;
            color: #1e293b;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .btn-batal:hover {
            background: #f1f5f9;
        }
        
        .btn-simpan {
            padding: 10px 24px;
            background-color: #1e293b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-simpan:hover {
            background-color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        /* Content */
        .content {
            padding: 30px 25px;
        }
        
        .content-title {
            font-size: 22px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #334155;
            font-size: 15px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #1e293b;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
        }
        
        /* Input Row untuk Produk & Jumlah */
        
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
        
        .jumlah-input {
            width: 90px;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            font-weight: 500;
            color: #1e293b;
            background: white;
        }
        
        .jumlah-input:focus {
            outline: none;
            border-color: #3b82f6;
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
        
        .btn-hapus-produk:hover {
            background: #fef2f2;
        }
        
        /* Tambah Produk Button */
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
        
        /* Product List */
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
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-weight: 500;
            color: #1e293b;
            font-size: 14px;
        }
        
        .product-qty {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
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
            transition: all 0.2s;
        }
        
        .btn-delete:hover {
            background: #fef2f2;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .content {
                padding: 20px;
            }
            
            .header {
                padding: 12px 20px;
            }
            
            .product-input-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .jumlah-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <a href="stok-opname.blade.php" class="back-link">
                    <span class="back-arrow">←</span>
                    <span>Batal</span>
                </a>
            </div>
            <button type="button" class="btn-simpan" id="save-btn">
                Simpan
            </button>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h1 class="content-title">Tambahkan Stok Opname</h1>
            
            <form id="opname-form">
                <!-- Tanggal -->
                <div class="form-group">
                    <label class="form-label">Tanggal Stok Opname</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="tanggal"
                    >
                </div>
                
                <!-- Catatan (TextField) -->
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="catatan" 
                        placeholder="Catatan"
                    >
                </div>
                
                <!-- Pilih Produk & Jumlah (Sampingan) -->
                <div class="product-input-row">
                    <div class="form-group">
                        <label class="form-label">Pilih Produk</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="produk" 
                            placeholder="Pilih Produk"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <div class="jumlah-input-group">
                            <input 
                                type="number" 
                                class="jumlah-input" 
                                id="jumlah" 
                                value="0" 
                                min="0"
                            >
                            <button type="button" class="btn-hapus-produk" id="clear-input">
                                Hapus
                            </button>
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
    </div>
    
    <script>
        // Clear input produk & jumlah
        document.getElementById('clear-input').addEventListener('click', function() {
            document.getElementById('produk').value = '';
            document.getElementById('jumlah').value = '0';
        });
        
        // Array untuk nyimpen produk
        let products = [];
        
        // Tambah produk ke list
        document.getElementById('add-btn').addEventListener('click', function() {
            const produkNama = document.getElementById('produk').value.trim();
            const jumlah = parseInt(document.getElementById('jumlah').value);
            
            // Validasi
            if (!produkNama) {
                alert('Nama produk harus diisi!');
                return;
            }
            
            if (jumlah <= 0) {
                alert('Jumlah harus lebih dari 0!');
                return;
            }
            
            // Tambah produk ke array
            products.push({
                nama: produkNama,
                jumlah: jumlah
            });
            
            // Update tampilan list
            renderProductList();
            
            // Reset input
            document.getElementById('produk').value = '';
            document.getElementById('jumlah').value = '0';
        });
        
        // Render list produk
        function renderProductList() {
            const productList = document.getElementById('product-list');
            
            if (products.length === 0) {
                productList.innerHTML = '';
                return;
            }
            
            productList.innerHTML = products.map((product, index) => `
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">${product.nama}</div>
                        <div class="product-qty">Jumlah: ${product.jumlah}</div>
                    </div>
                    <button type="button" class="btn-delete" onclick="deleteProduct(${index})">
                        Hapus
                    </button>
                </div>
            `).join('');
        }
        
        // Hapus produk dari list
        function deleteProduct(index) {
            products.splice(index, 1);
            renderProductList();
        }
        
        // Simpan semua data
        document.getElementById('save-btn').addEventListener('click', function() {
            const tanggal = document.getElementById('tanggal').value;
            const catatan = document.getElementById('catatan').value.trim();
            
            // Validasi
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
</body>
</html>