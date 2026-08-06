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
                        <td class="text-center" data-label="No">{{ ($karyawan->firstItem() ?? 1) + $index }}</td>
                        <td data-label="Nama Lengkap">
                            <button type="button"
                                    class="link-detail btn-detail-karyawan"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-nik="{{ $item->nik ?? '-' }}"
                                    data-email="{{ $item->email }}"
                                    data-departemen="{{ $item->departemen ?? '-' }}"
                                    data-jabatan="{{ $item->jabatan ?? '-' }}"
                                    data-role="{{ $item->role ?? '-' }}"
                                    data-status="{{ $item->status ?? '-' }}">
                                {{ $item->name }}
                            </button>
                        </td>
                        <td data-label="NIK">{{ $item->nik ?? '-' }}</td>
                        <td data-label="Email">{{ $item->email }}</td>
                        <td data-label="Departemen">{{ $item->departemen ?? '-' }}</td>
                        <td data-label="Jabatan">{{ $item->jabatan ?? '-' }}</td>
                        <td data-label="Role">
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
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-nik="{{ $item->nik ?? '-' }}"
                                    data-email="{{ $item->email }}"
                                    data-departemen="{{ $item->departemen ?? '-' }}"
                                    data-jabatan="{{ $item->jabatan ?? '-' }}"
                                    data-role="{{ $item->role ?? '-' }}"
                                    data-status="{{ $item->status ?? '-' }}">
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

                            <form action="{{ route('superadmin.karyawan.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return window.confirmFormSubmit(event, 'Yakin ingin menghapus karyawan ini?', 'Konfirmasi Hapus Karyawan')">
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
            <div class="detail-month-selector" style="border-top: 2px dashed #e5e7eb; margin-top: 10px; padding-top: 15px;">
                <label for="detailBulanSelect" style="font-weight: 600; color: #555; font-size: 0.9rem;">Data Absensi Bulan:</label>
                <select id="detailBulanSelect" class="detail-bulan-select"></select>
            </div>
            <div id="detailAttendanceLoading" style="display:none; text-align:center; padding: 20px 0;">
                <div class="attendance-spinner"></div>
                <span style="color:#888; font-size:0.85rem; margin-top:8px; display:block;">Memuat data...</span>
            </div>
            <div id="detailAttendanceData">
                <div class="detail-item">
                    <span class="detail-label">Hari Masuk</span>
                    <span class="detail-value font-weight-bold" id="detailHadir">0 Hari</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Izin</span>
                    <span class="detail-value font-weight-bold" id="detailIzin">0 Hari</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Cuti</span>
                    <span class="detail-value font-weight-bold" id="detailCuti">0 Hari</span>
                </div>
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
    text-align: left;
}

.link-detail:hover {
    color: #0a58ca;
    text-decoration: underline;
}

.modal-detail {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(4px);
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
    max-width: 520px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
    animation: modalSlideIn 0.25s ease-out;
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.modal-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    color: #ffffff;
}

.modal-detail-header h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #ffffff;
}

.modal-close {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.25);
}

.modal-detail-body {
    padding: 20px 24px;
    max-height: 70vh;
    overflow-y: auto;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 11px 0;
    border-bottom: 1px solid #f1f5f9;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #64748b;
    font-size: 0.875rem;
    min-width: 120px;
    flex-shrink: 0;
}

.detail-value {
    flex: 1;
    text-align: right;
    color: #1e293b;
    font-size: 0.875rem;
    font-weight: 500;
    word-break: break-word;
}

.detail-month-selector {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 12px;
}

.detail-bulan-select {
    padding: 8px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    background: #f9fafb;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    outline: none;
    min-width: 160px;
    appearance: auto;
}

.detail-bulan-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    background: #fff;
}

.attendance-spinner {
    width: 28px;
    height: 28px;
    border: 3px solid #e5e7eb;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== Mobile responsive untuk modal detail karyawan ===== */
@media (max-width: 576px) {
    .modal-detail {
        padding: 0;
        align-items: flex-end;
    }
    .modal-detail-content {
        max-width: 100%;
        border-radius: 16px 16px 0 0;
        max-height: 90vh;
    }
    .modal-detail-header {
        padding: 14px 18px;
    }
    .modal-detail-header h3 {
        font-size: 15px;
    }
    .modal-detail-body {
        padding: 16px 18px;
        max-height: 75vh;
        overflow-y: auto;
    }
    .detail-item {
        gap: 8px;
        padding: 9px 0;
    }
    .detail-label {
        min-width: 95px;
        font-size: 0.8rem;
    }
    .detail-value {
        font-size: 0.8rem;
    }
    .detail-month-selector {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    .detail-month-selector label {
        font-size: 0.82rem !important;
    }
    .detail-bulan-select {
        width: 100%;
        min-width: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalDetailKaryawan');
    const closeBtn = document.getElementById('closeModalDetail');
    const buttons = document.querySelectorAll('.btn-detail-karyawan');
    const bulanSelect = document.getElementById('detailBulanSelect');
    const loadingEl = document.getElementById('detailAttendanceLoading');
    const dataEl = document.getElementById('detailAttendanceData');

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

    let currentUserId = null;

    // Populate bulan dropdown (12 bulan terakhir)
    function populateBulanOptions() {
        bulanSelect.innerHTML = '';
        const now = new Date();
        const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        for (let i = 0; i < 12; i++) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const val = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
            const label = namaBulan[d.getMonth()] + ' ' + d.getFullYear();
            const opt = document.createElement('option');
            opt.value = val;
            opt.textContent = label;
            if (i === 0) opt.selected = true;
            bulanSelect.appendChild(opt);
        }
    }
    populateBulanOptions();

    // Fetch attendance data via AJAX
    function fetchAttendanceData(userId, bulan) {
        loadingEl.style.display = 'block';
        dataEl.style.display = 'none';

        fetch('/superadmin/karyawan/' + userId + '/attendance-data?bulan=' + bulan)
            .then(res => res.json())
            .then(data => {
                detailHadir.textContent = (data.hadir ?? 0) + ' Hari';
                detailIzin.textContent = (data.izin ?? 0) + ' Hari';
                detailCuti.textContent = (data.cuti ?? 0) + ' Hari';
                loadingEl.style.display = 'none';
                dataEl.style.display = 'block';
            })
            .catch(() => {
                detailHadir.textContent = '-';
                detailIzin.textContent = '-';
                detailCuti.textContent = '-';
                loadingEl.style.display = 'none';
                dataEl.style.display = 'block';
            });
    }

    // Saat bulan diganti
    bulanSelect.addEventListener('change', function () {
        if (currentUserId) {
            fetchAttendanceData(currentUserId, this.value);
        }
    });

    // Saat tombol detail diklik
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            currentUserId = this.dataset.id;

            detailName.textContent = this.dataset.name || '-';
            detailNik.textContent = this.dataset.nik || '-';
            detailEmail.textContent = this.dataset.email || '-';
            detailDepartemen.textContent = this.dataset.departemen || '-';
            detailJabatan.textContent = this.dataset.jabatan || '-';
            detailRole.textContent = this.dataset.role || '-';
            detailStatus.textContent = this.dataset.status || '-';

            // Reset bulan ke bulan ini dan fetch
            bulanSelect.selectedIndex = 0;
            fetchAttendanceData(currentUserId, bulanSelect.value);

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
        currentUserId = showUser.id;
        detailName.textContent = showUser.name || '-';
        detailNik.textContent = showUser.nik || '-';
        detailEmail.textContent = showUser.email || '-';
        detailDepartemen.textContent = showUser.departemen || '-';
        detailJabatan.textContent = showUser.jabatan || '-';
        detailRole.textContent = showUser.role || '-';
        detailStatus.textContent = showUser.status || '-';
        bulanSelect.selectedIndex = 0;
        fetchAttendanceData(currentUserId, bulanSelect.value);
        modal.classList.add('show');
    }
    @endif
});
</script>
@endsection
