@extends('layouts.absen')

@section('title', 'Riwayat Izin & Cuti')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="laporan-izin-cuti">
        <div class="content-title">Riwayat Pengajuan Izin & Cuti</div>
        <p class="content-description">Daftar pengajuan izin dan cuti Anda beserta statusnya</p>

        @if(session('success'))
            <div class="success-message" style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="data-table-laporan">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Tipe</th>
                        <th>Jenis</th>
                        <th>Periode</th>
                        <th>Keterangan / Alasan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $leave->created_at->format('d M Y') }}</td>
                        <td>
                            @if($leave->jenis == 'izin')
                                <span class="status-badge status-wfh">Izin</span>
                            @else
                                <span class="status-badge status-present">Cuti</span>
                            @endif
                        </td>
                        <td>{{ ucwords(str_replace('_', ' ', $leave->jenis_detail)) }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                            @if($leave->end_date)
                                &ndash; {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            @endif
                        </td>
                        <td>{{ $leave->keterangan }}</td>
                        <td>
                            @if($leave->file_path)
                                <a href="{{ asset('storage/' . $leave->file_path) }}" target="_blank">Lihat File</a>
                            @else
                                <span style="color:#94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($leave->status == 'approved')
                                <span class="status-badge status-present">Disetujui</span>
                            @elseif($leave->status == 'rejected')
                                <span class="status-badge status-absent">Ditolak</span>
                            @else
                                <span class="status-badge status-late">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding:24px;color:#94a3b8;">
                            Belum ada pengajuan izin atau cuti.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top:16px;">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
