@extends('layouts.absen')

@section('title', 'Riwayat Izin & Cuti')

@section('content')
<div class="dashboard-content">
    <!-- Page Header Formal -->
    <div class="welcome-section" style="margin-bottom: 24px;">
        <h1 class="page-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Riwayat Pengajuan Izin & Cuti</h1>
        <p class="page-subtitle" style="font-size: 14px; color: #64748b; margin: 0;">Monitoring status persetujuan atas pengajuan izin tidak masuk kerja dan cuti karyawan</p>
    </div>

    <div class="content-card">
        <div class="content-card-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 class="content-title" style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0; border: none; padding: 0;">Daftar Pengajuan</h3>
            </div>
            <div>
                <a href="{{ route('absensi.cuti') }}" style="background: #1e293b; color: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                    + Buat Pengajuan Cuti
                </a>
                <a href="{{ route('absensi.pengajuan-izin') }}" style="background: #1e293b; color: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; margin-left: 6px;">
                    + Buat Pengajuan Izin
                </a>
            </div>
        </div>

        @if($leaves->isEmpty())
            <div style="text-align: center; padding: 48px 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <h4 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 6px;">Belum Ada Riwayat Pengajuan</h4>
                <p style="font-size: 14px; color: #64748b; margin: 0; max-width: 450px; margin-left: auto; margin-right: auto;">Anda belum pernah mengajukan permohonan izin ataupun cuti pada sistem.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table-laporan" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Pengajuan</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Kategori</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Detail</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Periode Waktu</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Keterangan / Alasan</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Lampiran</th>
                            <th style="padding: 14px 18px; text-align: left; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Status Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; color: #475569; font-weight: 500;">{{ $leave->created_at->format('d M Y') }}</td>
                            <td style="padding: 14px 18px;">
                                @if($leave->jenis == 'izin')
                                    <span style="background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Izin</span>
                                @else
                                    <span style="background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Cuti</span>
                                @endif
                            </td>
                            <td style="padding: 14px 18px; color: #1e293b; font-weight: 600;">{{ ucwords(str_replace('_', ' ', $leave->jenis_detail)) }}</td>
                            <td style="padding: 14px 18px; color: #334155;">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                @if($leave->end_date && $leave->end_date != $leave->start_date)
                                    &ndash; {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                @endif
                            </td>
                            <td style="padding: 14px 18px; color: #475569; max-width: 250px;">{{ $leave->keterangan }}</td>
                            <td style="padding: 14px 18px;">
                                @if($leave->file_path)
                                    <a href="{{ asset('storage/' . $leave->file_path) }}" target="_blank" style="color: #2563eb; font-weight: 500; text-decoration: underline;">Lihat Dokumen</a>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 14px 18px;">
                                @if($leave->status == 'approved')
                                    <span style="background: #dcfce7; color: #166534; border: 1px solid #86efac; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Disetujui</span>
                                @elseif($leave->status == 'rejected')
                                    <span style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Ditolak</span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; border: 1px solid #fde047; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            <div class="pagination-container" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
