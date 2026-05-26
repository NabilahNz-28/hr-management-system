@extends('layouts.superadmin')

@section('title', 'Data Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 600;">Data Karyawan</h1>
        <a href="{{ route('superadmin.karyawan.create') }}" class="btn btn-primary shadow-sm" style="background-color: #3b82f6; border-color: #3b82f6;">
            + Tambah Karyawan
        </a>
    </div>

    <div class="card shadow mb-4" style="border: none; border-radius: 8px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #fff; border-bottom: 1px solid #e2e8f0;">
            <h6 class="m-0 font-weight-bold" style="color: #3b82f6;">Daftar Karyawan Terdaftar</h6>
            
            <!-- Search Form -->
            <form action="{{ route('superadmin.karyawan.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0" style="color: #334155;">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Lengkap</th>
                            <th width="25%">Email</th>
                            <th width="15%">Role</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <!-- Link untuk melihat detail rincian absensi/cuti per karyawan -->
                                <a href="{{ route('superadmin.karyawan.show', $item->id) }}" style="color: #1e293b; text-decoration: none; font-weight: 500;">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <span class="badge" style="background-color: #e2e8f0; color: #475569; padding: 6px 10px; font-weight: 500;">
                                    {{ $item->role }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('superadmin.karyawan.show', $item->id) }}" class="btn btn-sm btn-info" style="font-size: 12px;">Detail & Absen</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Data karyawan tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (1-10, 11-20 dst) -->
            <div class="mt-3">
                {{ $karyawan->links('pagination::bootstrap-4') ?? '' }}
            </div>
        </div>
    </div>
</div>
@endsection