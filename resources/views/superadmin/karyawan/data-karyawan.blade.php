@extends('layouts.superadmin')

@section('title', 'Data Karyawan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="data-karyawan">
        <div class="content-title">Data Karyawan</div>
        <p class="content-description">Kelola data seluruh karyawan perusahaan</p>

        <!-- Header Action - DIPERBAIKI -->
        <div class="action-header">
            <a href="{{ route('superadmin.karyawan.create') }}" class="btn btn-primary btn-small">
                + Tambah Karyawan
            </a>
            
            <form action="{{ route('superadmin.karyawan.index') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search">Cari</button>
                </div>
            </form>
        </div>

        <!-- Tabel Karyawan - DIPERBAIKI -->
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
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ route('superadmin.karyawan.show', $item->id) }}" class="link-detail">
                                {{ $item->name }}
                            </a>
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
                            <a href="{{ route('superadmin.karyawan.show', $item->id) }}" class="btn-action btn-view" title="Detail">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
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
                    @endforelse <!-- INI DIPERBAIKI -->
                </tbody>
            </table>
        </div>

        <!-- Pagination - DIPERBAIKI -->
        <div class="pagination-container">
            @if(isset($karyawan) && $karyawan->hasPages())
                <div class="pagination-wrapper">
                    {{-- Mobile Pagination --}}
                    <div class="pagination-mobile">
                        <div class="pagination-info">
                            Menampilkan {{ $karyawan->firstItem() ?? 0 }} - {{ $karyawan->lastItem() ?? 0 }} 
                            dari {{ $karyawan->total() }} data
                        </div>
                        <div class="pagination-nav">
                            @if($karyawan->onFirstPage())
                                <span class="page-link disabled">‹</span>
                            @else
                                <a href="{{ $karyawan->previousPageUrl() }}" class="page-link">‹</a>
                            @endif

                            <span class="page-current">{{ $karyawan->currentPage() }} / {{ $karyawan->lastPage() }}</span>

                            @if($karyawan->hasMorePages())
                                <a href="{{ $karyawan->nextPageUrl() }}" class="page-link">›</a>
                            @else
                                <span class="page-link disabled">›</span>
                            @endif
                        </div>
                    </div>

                    {{-- Desktop Pagination --}}
                    <div class="pagination-desktop">
                        {{ $karyawan->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @elseif(isset($karyawan) && $karyawan->total() > 0)
                <div class="pagination-info">
                    Menampilkan {{ $karyawan->firstItem() ?? 0 }} - {{ $karyawan->lastItem() ?? 0 }} 
                    dari {{ $karyawan->total() }} data
                </div>
            @endif
        </div>
    </div>
</div>
@endsection