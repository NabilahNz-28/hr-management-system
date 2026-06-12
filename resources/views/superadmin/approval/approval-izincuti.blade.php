@extends('layouts.superadmin')

@section('title', 'Approval Izin Cuti')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Approval Data Izin Cuti</h1>
            <p class="mb-0 text-muted">Daftar pengajuan izin dan cuti yang menunggu persetujuan.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">Pengajuan Menunggu Persetujuan</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3 py-3">Tanggal Pengajuan</th>
                            <th class="px-3 py-3">Nama Karyawan</th>
                            <th class="px-3 py-3">Tipe</th>
                            <th class="px-3 py-3">Keterangan / Alasan</th>
                            <th class="px-3 py-3 text-center">File Lampiran</th>
                            <th class="px-3 py-3 text-center">Status</th>
                            <th class="px-3 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $data)
                            <tr>
                                <td class="px-3 py-3">
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
                                </td>

                                <td class="px-3 py-3 font-weight-semibold text-dark">
                                    {{ $data->karyawan->name }}
                                </td>

                                <td class="px-3 py-3">
                                    @if($data->jenis == 'izin')
                                        <span class="badge badge-warning px-2 py-1">Izin</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1">Cuti</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-wrap" style="max-width: 280px;">
                                    {{ $data->keterangan }}
                                </td>

                                <td class="px-3 py-3 text-center">
                                    @if($data->filepath)
                                        <a href="{{ asset('storage/' . $data->filepath) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-secondary">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada lampiran</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-center">
                                    <span class="badge badge-secondary px-2 py-1">Pending</span>
                                </td>

                                <td class="px-3 py-3 text-center">
                                    <div class="d-inline-flex gap-2">
                                        <form action="{{ route('superadmin.approval.approve', $data->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('superadmin.approval.reject', $data->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Belum ada pengajuan izin atau cuti yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection