<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Form Tambah Barang - Stock Opname</title>
    
    <!-- CSS terpisah (nanti tinggal pindahin ke file css Laravel) -->
    <style>
        /* ========== INI YANG NANTI LO PINDAHIN KE CSS LARAVEL ========== */
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .content-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        /* Form Styles */
        #add-item-form {
            padding: 25px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background-color: #f8fafc;
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #334155;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Button Styles */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-black {
            background-color: #1e293b;
            color: white;
        }
        
        .btn-black:hover {
            background-color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }
        
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .container {
                padding: 20px;
            }
        }
        /* ========== END CSS ========== */
    </style>
</head>
<body>
    <div class="container">
        <!-- Form Tambah Barang - VISIBLE (tanpa display:none) -->
        <div id="add-item-form">
            <h3 class="content-title">Form Tambah Barang Stock Opname</h3>
            
            <form id="item-form">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="item-name">Nama Barang</label>
                        <input type="text" class="form-control" id="item-name" placeholder="Contoh: Box Eco 250ml" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="item-category">Kategori</label>
                        <select class="form-select" id="item-category" required>
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
                        <input type="number" class="form-control" id="item-pcs" placeholder="0" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="item-carton">Jumlah (Carton)</label>
                        <input type="number" class="form-control" id="item-carton" placeholder="0" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="item-notes">Catatan (Opsional)</label>
                    <textarea class="form-control" id="item-notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-black">Submit</button>
                    <button type="button" class="btn btn-secondary" id="cancel-form">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript (nanti dipindah ke file JS Laravel) -->
    <script>
        // Test form submission
        document.getElementById('item-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Form berhasil disubmit! Cek console untuk data.');
            
            const formData = {
                name: document.getElementById('item-name').value,
                category: document.getElementById('item-category').value,
                pcs: document.getElementById('item-pcs').value,
                carton: document.getElementById('item-carton').value,
                notes: document.getElementById('item-notes').value
            };
            
            console.log('Form Data:', formData);
        });
        
        // Cancel button
        document.getElementById('cancel-form').addEventListener('click', function() {
            document.getElementById('item-form').reset();
            alert('Form direset!');
        });
    </script>
</body>
</html>