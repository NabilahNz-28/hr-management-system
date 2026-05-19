 <!-- Stock Opname -->
            <div id="stock-opname" class="page-content">
                <h1 class="page-title">Stock Opname</h1>
                <p class="page-subtitle">Kelola stock opname inventory</p>

                <div class="content-description">
                    <p>Pilih kategori untuk melihat barang yang tersedia:</p>
                </div>
                
                    <div class="category-filter">

                    <!-- Tombol kategori (kiri) -->
                    <div class="category-buttons">
                        <button class="category-btn active" data-category="all">Semua Kategori</button>
                        <button class="category-btn" data-category="eco">Eco</button>
                        <button class="category-btn" data-category="fragile">Fragile</button>
                        <button class="category-btn" data-category="plastic">Plastic</button>
                        <button class="category-btn" data-category="thermal">Thermal</button>
                        <button class="category-btn" data-category="carton">Carton</button>
                    </div>

                    <!-- Tombol aksi di kanan: Tambahkan Barang + Input Opname -->
                    <div class="action-buttons">
                        <button class="btn btn-black" id="add-item-btn">
                            <i class="bi bi-plus-circle me-2"></i> Tambahkan Barang
                        </button>
                        <button class="btn btn-black" id="show-opname-form">
                            <i class="bi bi-plus-circle me-2"></i> Input Opname
                        </button>
                    </div>
                </div>

                <div class="stock-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok </th>
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
        

      