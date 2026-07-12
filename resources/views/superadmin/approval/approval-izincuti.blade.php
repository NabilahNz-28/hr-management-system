@extends('layouts.superadmin')

@section('title', 'Approval Izin Cuti')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="approval-izin-cuti">
        <div class="content-title">Approval Data Izin Cuti</div>
        <p class="content-description">Daftar pengajuan izin dan cuti yang menunggu persetujuan.</p>

        <div class="table-responsive">
            <table class="data-table-laporan">
                <thead>
                    <tr>
                        <th width="15%">Tanggal Pengajuan</th>
                        <th width="20%">Nama Karyawan</th>
                        <th width="10%">Tipe</th>
                        <th width="25%">Keterangan / Alasan</th>
                        <th width="15%" class="text-center">File Lampiran</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $data)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
                            </td>
                            <td style="font-weight: 600; color: #1e293b;">
                                {{ $data->karyawan->name }}
                            </td>
                            <td>
                                @if($data->jenis == 'izin')
                                    <span class="badge-custom badge-izin">Izin</span>
                                @else
                                    <span class="badge-custom badge-cuti">Cuti</span>
                                @endif
                            </td>
                            <td style="white-space: normal; max-width: 250px; line-height: 1.4;">
                                {{ $data->keterangan }}
                            </td>
                            <td class="text-center">
                                @if($data->file_path)
                                    <a href="{{ asset('storage/' . $data->file_path) }}"
                                       target="_blank"
                                       class="btn-download">
                                        Lihat File
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem;">Tidak ada lampiran</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->status == 'approved')
                                    <span class="badge-custom badge-approved">Disetujui</span>
                                @elseif($data->status == 'rejected')
                                    <span class="badge-custom badge-rejected">Ditolak</span>
                                @else
                                    <span class="badge-custom badge-pending">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->status == 'pending')
                                    <div class="action-buttons-approval">
                                        <form action="{{ route('superadmin.approval.approve', $data->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-approval btn-approve">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('superadmin.approval.reject', $data->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-approval btn-reject">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted" style="padding: 40px 10px; color: #94a3b8;">
                                <div style="font-size: 2rem; margin-bottom: 10px;">📭</div>
                                Belum ada pengajuan izin atau cuti yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($pengajuan) && $pengajuan->total() > 0)
        <div class="pagination-container" style="margin-top: 20px;">
            <div class="pagination-info">
                Menampilkan <strong>{{ $pengajuan->firstItem() }}</strong>–<strong>{{ $pengajuan->lastItem() }}</strong>
                dari <strong>{{ $pengajuan->total() }}</strong> pengajuan
            </div>

            @if($pengajuan->hasPages())
            <nav class="pagination-nav">
                @if($pengajuan->onFirstPage())
                    <span class="page-btn disabled">‹</span>
                @else
                    <a href="{{ $pengajuan->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
                @endif

                @foreach($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)
                    @if($page == $pengajuan->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($pengajuan->hasMorePages())
                    <a href="{{ $pengajuan->nextPageUrl() }}" class="page-btn" rel="next">›</a>
                @else
                    <span class="page-btn disabled">›</span>
                @endif
            </nav>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
/* Custom Page specific CSS for Approval Table */
.badge-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-izin {
    background-color: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

.badge-cuti {
    background-color: #ecfeff;
    color: #0891b2;
    border: 1px solid #c5f6fa;
}

.badge-approved {
    background-color: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.badge-rejected {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.badge-pending {
    background-color: #f8fafc;
    color: #64748b;
    border: 1px solid #cbd5e1;
}

.btn-download {
    color: #3b82f6;
    background-color: #eff6ff;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.78rem;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-block;
    border: 1px dashed #3b82f6;
}

.btn-download:hover {
    background-color: #dbeafe;
    color: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-1px);
}

.action-buttons-approval {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.btn-action-approval {
    padding: 8px 16px;
    font-size: 0.78rem;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
}

.btn-approve:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-1.5px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.1);
}

.btn-reject:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-1.5px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
}
</style>
@endsection