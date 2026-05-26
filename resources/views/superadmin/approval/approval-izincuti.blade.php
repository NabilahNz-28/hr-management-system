@extends('layouts.superadmin')

@section('title', 'Approval Izin & Cuti')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 600;">Approval Data Izin & Cuti</h1>
    </div>

    <div class="card shadow mb-4" style="border: none; border-radius: 8px;">
        <div class="card-header py-3" style="background-color: #fff; border-bottom: 1px solid #e2e8f0;">
            <h6 class="m-0 font-weight-bold" style="color: #3b82f6;">Daftar Pengajuan Menunggu Persetujuan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" style="color: #334155;">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Nama Karyawan</th>
                            <th>Tipe</th>
                            <th>Keterangan / Alasan</th>
                            <th>File Lampiran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan ?? [] as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</td>
                            <td style="font-weight: 500; color: #1e293b;">{{ $data->karyawan->name }}</td>
                            <td>
                                @if($data->jenis == 'izin')
                                    <span class="badge" style="background-color: #fef08a; color: #854d0e; padding: 5px 8px;">Izin</span>
                                @else
                                    <span class="badge" style="background-color: #bbf7d0; color: #166534; padding: 5px 8px;">Cuti</span>
                                @endif
                            </td>
                            <td>{{ $data->keterangan }}</td>
                            <td class="text-center">
                                @if($data->file_path)
                                    <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size: 12px;">
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">Tidak ada lampiran</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background-color: #e2e8f0; color: #475569; padding: 5px 8px;">Pending</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('superadmin.approval.approve', $data->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" style="font-size: 12px;">Setujui</button>
                                    </form>
                                    <form action="{{ route('superadmin.approval.reject', $data->id) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" style="font-size: 12px;">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan izin atau cuti yang masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection