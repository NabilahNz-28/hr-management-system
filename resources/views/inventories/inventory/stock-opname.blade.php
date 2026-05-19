@extends('layouts.pic')

@section('title', 'Stock Opname')

@section('content')
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
                        <button class="category-btn" data-category="other">Other</button>

                    </div>

                    <!-- Tombol aksi di kanan: Tambahkan Barang + Input Opname -->
                    <div class="action-buttons">
                        <a href="{{ route('inventory.tambah-barang') }}" class="btn btn-black">
                            <i class="bi bi-plus-circle me-2"></i> Tambahkan Barang
                        </a>
                        <a href="{{ route('inventory.input-opname') }}" class="btn btn-black">
                            <i class="bi bi-card-checklist me-2"></i> Input Opname
                        </a>
                    </div>
                </div>

                <div class="stock-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok </th>
                                <!-- tambahkan titik 3 "detail" disini-->
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            @forelse($barang ?? [] as $item)
                            <tr class="item-row" data-category="{{ strtolower($item->kategori) }}">
                                <td>{{ $item->nama_barang }}</td>
                                <td>
                                    <span style="padding: 4px 10px; font-size: 12px; border-radius: 20px; background: #e2e8f0; font-weight: 500; color: #334155;">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                </td>
                                <td>{{ $item->stok_fisik }} pcs</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 40px; color: #94a3b8;">
                                    Belum ada barang di inventory.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryBtns = document.querySelectorAll('.category-btn');
    const itemRows = document.querySelectorAll('.item-row');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all buttons
            categoryBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const selectedCategory = this.getAttribute('data-category');
            
            // Filter rows
            itemRows.forEach(row => {
                if (selectedCategory === 'all' || row.getAttribute('data-category') === selectedCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection