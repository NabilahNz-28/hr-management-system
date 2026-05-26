@extends('layouts.superadmin')

@section('title', 'Profile Superadmin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 600;">Profile Superadmin</h1>
    </div>

    <div class="row">
        <!-- Foto Profile Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow" style="border: none; border-radius: 8px;">
                <div class="card-body text-center py-5">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; background-color: #e0e7ff; color: #3b82f6; font-size: 36px; font-weight: bold;">
                        {{ substr(auth()->user()->name ?? 'SA', 0, 2) }}
                    </div>
                    <h5 class="font-weight-bold" style="color: #1e293b; margin-bottom: 5px;">{{ auth()->user()->name ?? 'Super Administrator' }}</h5>
                    <p class="text-muted" style="font-size: 14px; margin-bottom: 0;">{{ auth()->user()->role ?? 'System Owner' }}</p>
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="col-md-8">
            <div class="card shadow mb-4" style="border: none; border-radius: 8px;">
                <div class="card-header py-3" style="background-color: #fff; border-bottom: 1px solid #e2e8f0;">
                    <h6 class="m-0 font-weight-bold" style="color: #3b82f6;">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label style="font-weight: 500; color: #475569;">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name ?? 'Super Administrator' }}" style="border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label style="font-weight: 500; color: #475569;">Alamat Email</label>
                            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email ?? 'admin@perusahaan.com' }}" style="border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>

                        <hr style="border-color: #e2e8f0; margin: 24px 0;">
                        <h6 class="mb-3" style="font-weight: 600; color: #334155;">Ubah Password (Opsional)</h6>
                        
                        <div class="form-group mb-3">
                            <label style="font-weight: 500; color: #475569;">Password Baru</label>
                            <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah" style="border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        
                        <div class="form-group mb-4">
                            <label style="font-weight: 500; color: #475569;">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru" style="border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" style="background-color: #3b82f6; border-color: #3b82f6; padding: 8px 24px;">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection