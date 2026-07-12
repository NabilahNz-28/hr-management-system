@extends('layouts.pic')

@section('title', 'Transfer Stock')

@section('styles')
<style>
    /* Styling khusus Modal Pilih Produk agar persis desain UI saat opname */
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
<div class="page-content active">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="content-title" style="margin-bottom: 4px;">Transfer Stock</h3>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Pengiriman barang dari Gudang Utama ke Gudang Tujuan / Cabang</p>
        </div>
        <span class="badge mt-2 mt-sm-0" style="background: #e0f2fe; color: #0369a1; font-weight: 600; padding: 8px 14px; border-radius: 20px; font-size: 13px; align-self: flex-start;">
            <i class="bi bi-arrow-left-right me-1"></i> Gudang Utama → Gudang Tujuan
        </span>
    </div>

    <!-- Transfer Form -->
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
        <div class="card-header py-3" style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
            <h6 class="m-0 font-weight-bold" style="color: #1e293b; font-size: 15px;">
                <i class="bi bi-box-seam me-2 text-primary"></i>Form Transfer Stock
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('inventory.transfer-stock.store') }}">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Hari Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal" 
                               value="{{ date('Y-m-d') }}" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 14px; min-height: 42px;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Gudang Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="gudang_tujuan" 
                               placeholder="Contoh: Gudang Cabang Bandung" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 14px; min-height: 42px;" required>
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Nama Barang <span class="text-danger">*</span></label>
                        @php
                            $selectedBarang = null;
                            if (old('barang_id')) {
                                $selectedBarang = ($barang ?? collect())->firstWhere('id', old('barang_id'));
                            }
                        @endphp
                        <div id="product-select-trigger" onclick="openProductModal()">
                            <span id="selected-product-text" style="color: {{ $selectedBarang ? '#1e293b' : '#64748b' }}; font-size: 14px; font-weight: {{ $selectedBarang ? '600' : 'normal' }};">
                                @if($selectedBarang)
                                    {{ $selectedBarang->nama_barang }} ({{ ucfirst($selectedBarang->kategori) }}) - {{ $selectedBarang->stok_fisik ?? 0 }} pcs tersedia
                                @else
                                    Pilih Produk
                                @endif
                            </span>
                            <i class="bi bi-search" style="color: #94a3b8; font-size: 14px;"></i>
                        </div>
                        <input type="hidden" name="barang_id" id="barang_id" required value="{{ old('barang_id') }}">
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="jumlah" min="1" placeholder="Masukkan jumlah transfer" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 14px; min-height: 42px;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Satuan <span class="text-danger">*</span></label>
                        <select class="form-select" name="satuan" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 14px; min-height: 42px;" required>
                            <option value="pcs">Pcs</option>
                            <option value="carton">Carton (24 pcs)</option>
                            <option value="box">Box (12 pcs)</option>
                            <option value="pack">Pack (6 pcs)</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Catatan (Opsional)</label>
                    <textarea class="form-control" name="catatan" rows="3" 
                              placeholder="Tambahkan catatan jika diperlukan..." style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 12px; font-size: 14px;"></textarea>
                </div>
                
                <div class="alert mb-4" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; color: #334155; font-size: 13.5px; display: flex; align-items: center; gap: 12px; padding: 14px 16px;">
                    <i class="bi bi-info-circle-fill text-primary" style="font-size: 1.3rem; flex-shrink: 0;"></i>
                    <div>
                        Transfer dilakukan dari <strong>Gudang Utama</strong> ke 
                        <strong id="dest-preview" style="color: #0369a1;">[Gudang Tujuan]</strong>. 
                        Gudang utama tidak dapat diganti.
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark d-inline-flex align-items-center justify-content-center shadow-sm" style="background: #1e293b; border: none; border-radius: 8px; padding: 10px 24px; font-size: 14.5px; font-weight: 600; min-height: 44px; width: auto;">
                        <i class="bi bi-send-fill me-2"></i> Submit Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Transfers -->
    <div class="card shadow mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
        <div class="card-header py-3" style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
            <h6 class="m-0 font-weight-bold" style="color: #1e293b; font-size: 15px;">
                <i class="bi bi-clock-history me-2 text-primary"></i>Transfer Terbaru
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table table table-hover mb-0" width="100%" cellspacing="0" style="font-size: 13.5px;">
                    <thead style="background: #f8fafc; color: #475569; border-bottom: 1px solid #e2e8f0;">
                        <tr>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Barang</th>
                            <th class="py-3 px-3">Ke Gudang</th>
                            <th class="py-3 px-3">Jumlah</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfer_terbaru ?? [] as $transfer)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="px-3 py-3" data-label="Tanggal">{{ $transfer->tanggal->format('d M Y') }}</td>
                            <td class="px-3 py-3" data-label="Barang">
                                <span style="font-weight: 600; color: #1e293b;">{{ $transfer->barang->nama_barang ?? '-' }}</span>
                            </td>
                            <td class="px-3 py-3" data-label="Ke Gudang">{{ $transfer->ke_gudang }}</td>
                            <td class="px-3 py-3" data-label="Jumlah">
                                <span style="font-weight: 600; color: #0369a1;">{{ $transfer->jumlah }} pcs</span>
                            </td>
                            <td class="px-3 py-3" data-label="Status">
                                @php $status = !empty($transfer->status) ? ucfirst($transfer->status) : 'Selesai'; @endphp
                                @if(strtolower($status) === 'selesai')
                                    <span style="padding: 5px 12px; border-radius: 20px; background: #dcfce7; color: #16a34a; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span style="padding: 5px 12px; border-radius: 20px; background: #fef9c3; color: #ca8a04; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ $status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3" data-label="Catatan">{{ $transfer->catatan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada transfer terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transfer_terbaru instanceof \Illuminate\Pagination\LengthAwarePaginator && $transfer_terbaru->total() > 0)
            <div class="pagination-container p-3 border-top">
                <div class="pagination-info">
                    Menampilkan <strong>{{ $transfer_terbaru->firstItem() ?? 0 }}</strong>–<strong>{{ $transfer_terbaru->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $transfer_terbaru->total() }}</strong> data transfer
                </div>

                <nav class="pagination-nav">
                    @if($transfer_terbaru->onFirstPage())
                        <span class="page-btn disabled">‹</span>
                    @else
                        <a href="{{ $transfer_terbaru->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
                    @endif

                    @foreach($transfer_terbaru->getUrlRange(1, $transfer_terbaru->lastPage()) as $page => $url)
                        @if($page == $transfer_terbaru->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($transfer_terbaru->hasMorePages())
                        <a href="{{ $transfer_terbaru->nextPageUrl() }}" class="page-btn" rel="next">›</a>
                    @else
                        <span class="page-btn disabled">›</span>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Cari Produk (Realtime Search Sesuai UI Tambah Produk saat Opname) -->
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
                    <button type="button" class="btn-select-product ms-3" onclick="selectProductFromModal('${item.id}', '${safeName}', '${kat}', '${stok}')">
                        Pilih Produk
                    </button>
                </div>
            `;
        }).join('');
    }

    window.selectProductFromModal = function(id, nama_barang, kategori, stok) {
        const decodedName = nama_barang.replace(/&quot;/g, '"').replace(/&#39;/g, "'");
        
        document.getElementById('barang_id').value = id;
        const triggerText = document.getElementById('selected-product-text');
        triggerText.textContent = `${decodedName} (${kategori}) - ${stok} pcs tersedia`;
        triggerText.style.color = '#1e293b';
        triggerText.style.fontWeight = '600';
        closeProductModal();
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Update destination preview
        const gudangInput = document.querySelector('input[name="gudang_tujuan"]');
        const preview = document.getElementById('dest-preview');
        
        if (gudangInput && preview) {
            gudangInput.addEventListener('input', function() {
                preview.textContent = this.value || '[Gudang Tujuan]';
            });
        }
    });
</script>
@endsection