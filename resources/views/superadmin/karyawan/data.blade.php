@extends('layouts.superadmin')

@section('title', 'Data Karyawan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="data-karyawan">
        <div class="content-title">Data Karyawan</div>
        <p class="content-description">Kelola data seluruh karyawan perusahaan</p>

        <div class="action-header">
            <a href="{{ route('superadmin.karyawan.create') }}" class="btn btn-primary btn-small" style="text-decoration: none !important;">
                + Tambah Karyawan
            </a>

            <form action="{{ route('superadmin.karyawan.index') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search">Cari</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table-laporan">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Lengkap</th>
                        <th width="15%">NIK</th>
                        <th width="20%">Email</th>
                        <th width="10%">Departemen</th>
                        <th width="10%">Jabatan</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan ?? [] as $index => $item)
                    <tr>
                        <td class="text-center">{{ ($karyawan->firstItem() ?? 1) + $index }}</td>
                        <td>
                            <button type="button"
                                    class="link-detail btn-detail-karyawan"
                                    data-name="{{ $item->name }}"
                                    data-nik="{{ $item->nik ?? '-' }}"
                                    data-email="{{ $item->email }}"
                                    data-departemen="{{ $item->departemen ?? '-' }}"
                                    data-jabatan="{{ $item->jabatan ?? '-' }}"
                                    data-role="{{ $item->role ?? '-' }}"
                                    data-status="{{ $item->status ?? '-' }}"
                                    data-hadir="{{ $item->hadir_count }}"
                                    data-izin="{{ $item->izin_count }}"
                                    data-cuti="{{ $item->cuti_count }}"
                                    data-terlambat="{{ $item->terlambat_count }}">
                                {{ $item->name }}
                            </button>
                        </td>
                        <td>{{ $item->nik ?? '-' }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->departemen ?? '-' }}</td>
                        <td>{{ $item->jabatan ?? '-' }}</td>
                        <td>
                            @php
                                $roleClass = '';
                                $roleText = '';
                                switch($item->role) {
                                    case 'superadmin':
                                        $roleClass = 'role-superadmin';
                                        $roleText = 'Super Admin';
                                        break;
                                    case 'admin':
                                        $roleClass = 'role-admin';
                                        $roleText = 'Admin';
                                        break;
                                    case 'inventory':
                                        $roleClass = 'role-inventory';
                                        $roleText = 'Inventory';
                                        break;
                                    case 'pic':
                                        $roleClass = 'role-pic';
                                        $roleText = 'PIC';
                                        break;
                                    default:
                                        $roleClass = 'role-karyawan';
                                        $roleText = 'Karyawan';
                                }
                            @endphp
                            <span class="role-badge {{ $roleClass }}">{{ $roleText }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = $item->status === 'aktif' ? 'status-active' : 'status-inactive';
                                $statusText = $item->status === 'aktif' ? 'Aktif' : 'Nonaktif';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="action-buttons">
                            <button type="button"
                                    class="btn-action btn-view btn-detail-karyawan"
                                    title="Detail"
                                    data-name="{{ $item->name }}"
                                    data-nik="{{ $item->nik ?? '-' }}"
                                    data-email="{{ $item->email }}"
                                    data-departemen="{{ $item->departemen ?? '-' }}"
                                    data-jabatan="{{ $item->jabatan ?? '-' }}"
                                    data-role="{{ $item->role ?? '-' }}"
                                    data-status="{{ $item->status ?? '-' }}"
                                    data-hadir="{{ $item->hadir_count }}"
                                    data-izin="{{ $item->izin_count }}"
                                    data-cuti="{{ $item->cuti_count }}"
                                    data-terlambat="{{ $item->terlambat_count }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>

                            <a href="{{ route('superadmin.karyawan.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 3l4 4-7 7H10v-4l7-7z"></path>
                                    <path d="M4 20h16"></path>
                                </svg>
                            </a>

                            <form action="{{ route('superadmin.karyawan.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0h10"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <div class="empty-state">
                                <div class="empty-icon">📭</div>
                                <p>Data karyawan tidak ditemukan</p>
                                <a href="{{ route('superadmin.karyawan.create') }}" class="btn btn-primary btn-small mt-2">Tambah Karyawan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($karyawan) && $karyawan->total() > 0)
        <div class="pagination-container">
            <div class="pagination-info">
                Menampilkan <strong>{{ $karyawan->firstItem() }}</strong>–<strong>{{ $karyawan->lastItem() }}</strong>
                dari <strong>{{ $karyawan->total() }}</strong> karyawan
            </div>

            @if($karyawan->hasPages())
            <nav class="pagination-nav">
                {{-- Prev --}}
                @if($karyawan->onFirstPage())
                    <span class="page-btn disabled">‹</span>
                @else
                    <a href="{{ $karyawan->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
                @endif

                {{-- Page numbers --}}
                @foreach($karyawan->getUrlRange(1, $karyawan->lastPage()) as $page => $url)
                    @if($page == $karyawan->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($karyawan->hasMorePages())
                    <a href="{{ $karyawan->nextPageUrl() }}" class="page-btn" rel="next">›</a>
                @else
                    <span class="page-btn disabled">›</span>
                @endif
            </nav>
            @endif
        </div>
        @endif
    </div>
</div>

<div id="modalDetailKaryawan" class="modal-detail">
    <div class="modal-detail-content">
        <div class="modal-detail-header">
            <h3>Detail Karyawan</h3>
            <button type="button" class="modal-close" id="closeModalDetail">&times;</button>
        </div>

        <div class="modal-detail-body">
            <div class="detail-item">
                <span class="detail-label">Nama</span>
                <span class="detail-value" id="detailName">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">NIK</span>
                <span class="detail-value" id="detailNik">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value" id="detailEmail">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Departemen</span>
                <span class="detail-value" id="detailDepartemen">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Jabatan</span>
                <span class="detail-value" id="detailJabatan">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Role</span>
                <span class="detail-value" id="detailRole">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value" id="detailStatus">-</span>
            </div>
            <div class="detail-item" style="border-top: 2px dashed #e5e7eb; margin-top: 10px; padding-top: 15px;">
                <span class="detail-label">Hari Masuk (Bulan Ini)</span>
                <span class="detail-value font-weight-bold" id="detailHadir">0 Hari</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Izin (Bulan Ini)</span>
                <span class="detail-value font-weight-bold" id="detailIzin">0 Hari</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Cuti (Bulan Ini)</span>
                <span class="detail-value font-weight-bold" id="detailCuti">0 Hari</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Terlambat (Bulan Ini)</span>
                <span class="detail-value font-weight-bold" id="detailTerlambat">0 Kali</span>
            </div>
        </div>
    </div>
</div>

<style>
.pagination-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
}

.pagination-info {
    color: #6b7280;
    font-size: 0.9rem;
}

.pagination-nav {
    display: flex;
    align-items: center;
    gap: 6px;
}

.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #374151;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}

.page-btn:hover:not(.disabled):not(.active) {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.page-btn.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.page-btn.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    background: #f9fafb;
}

@media (max-width: 576px) {
    .pagination-container {
        justify-content: center;
    }
}

.link-detail {
    background: none;
    border: none;
    color: #0d6efd;
    cursor: pointer;
    padding: 0;
    font-weight: 600;
}

.modal-detail {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-detail.show {
    display: flex;
}

.modal-detail-content {
    background: #fff;
    width: 100%;
    max-width: 550px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
}

.modal-detail-header h3 {
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    line-height: 1;
}

.modal-detail-body {
    padding: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f1f1;
}

.detail-label {
    font-weight: 600;
    color: #555;
    min-width: 130px;
}

.detail-value {
    flex: 1;
    text-align: right;
    color: #222;
}

@media (max-width: 576px) {
    .modal-detail {
        padding: 10px;
    }
    .modal-detail-body {
        padding: 15px;
        max-height: 80vh;
        overflow-y: auto;
    }
    .detail-item {
        gap: 10px;
    }
    .detail-label {
        min-width: 110px;
        font-size: 0.85rem;
    }
    .detail-value {
        font-size: 0.85rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalDetailKaryawan');
    const closeBtn = document.getElementById('closeModalDetail');
    const buttons = document.querySelectorAll('.btn-detail-karyawan');

    const detailName = document.getElementById('detailName');
    const detailNik = document.getElementById('detailNik');
    const detailEmail = document.getElementById('detailEmail');
    const detailDepartemen = document.getElementById('detailDepartemen');
    const detailJabatan = document.getElementById('detailJabatan');
    const detailRole = document.getElementById('detailRole');
    const detailStatus = document.getElementById('detailStatus');
    const detailHadir = document.getElementById('detailHadir');
    const detailIzin = document.getElementById('detailIzin');
    const detailCuti = document.getElementById('detailCuti');
    const detailTerlambat = document.getElementById('detailTerlambat');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            detailName.textContent = this.dataset.name || '-';
            detailNik.textContent = this.dataset.nik || '-';
            detailEmail.textContent = this.dataset.email || '-';
            detailDepartemen.textContent = this.dataset.departemen || '-';
            detailJabatan.textContent = this.dataset.jabatan || '-';
            detailRole.textContent = this.dataset.role || '-';
            detailStatus.textContent = this.dataset.status || '-';
            detailHadir.textContent = (this.dataset.hadir !== undefined ? this.dataset.hadir : 0) + ' Hari';
            detailIzin.textContent = (this.dataset.izin !== undefined ? this.dataset.izin : 0) + ' Hari';
            detailCuti.textContent = (this.dataset.cuti !== undefined ? this.dataset.cuti : 0) + ' Hari';
            detailTerlambat.textContent = (this.dataset.terlambat !== undefined ? this.dataset.terlambat : 0) + ' Kali';

            modal.classList.add('show');
        });
    });

    closeBtn.addEventListener('click', function () {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    @if(session('show_karyawan'))
    const showUser = @json(session('show_karyawan'));
    if (showUser) {
        detailName.textContent = showUser.name || '-';
        detailNik.textContent = showUser.nik || '-';
        detailEmail.textContent = showUser.email || '-';
        detailDepartemen.textContent = showUser.departemen || '-';
        detailJabatan.textContent = showUser.jabatan || '-';
        detailRole.textContent = showUser.role || '-';
        detailStatus.textContent = showUser.status || '-';
        detailHadir.textContent = (showUser.hadir_count !== undefined ? showUser.hadir_count : 0) + ' Hari';
        detailIzin.textContent = (showUser.izin_count !== undefined ? showUser.izin_count : 0) + ' Hari';
        detailCuti.textContent = (showUser.cuti_count !== undefined ? showUser.cuti_count : 0) + ' Hari';
        detailTerlambat.textContent = (showUser.terlambat_count !== undefined ? showUser.terlambat_count : 0) + ' Kali';
        modal.classList.add('show');
    }
    @endif
});
</script>
@endsection