@extends('layouts.superadmin')

@section('title', 'Insert Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 600;">Registrasi Karyawan Baru</h1>
    </div>

    <div class="card shadow mb-4" style="border: none; border-radius: 8px; max-width: 800px;">
        <div class="card-header py-3" style="background-color: #fff; border-bottom: 1px solid #e2e8f0;">
            <h6 class="m-0 font-weight-bold" style="color: #3b82f6;">Form Insert Data</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.karyawan.store') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label style="font-weight: 500; color: #475569;">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required style="border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                
                <div class="form-group mb-3">
                    <label style="font-weight: 500; color: #475569;">Email Aktif <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" placeholder="contoh@perusahaan.com" required style="border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                
                <div class="form-group mb-3">
                    <label style="font-weight: 500; color: #475569;">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required style="border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                
                <div class="form-group mb-4">
                    <label style="font-weight: 500; color: #475569;">Role / Jabatan <span class="text-danger">*</span></label>
                    <select class="form-control" name="role" required style="border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="">-- Pilih Role --</option>
                        <option value="karyawan">Karyawan Regular</option>
                        <option value="inventory">Inventory</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                </div>
                
                <hr style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-light mr-2" style="border: 1px solid #cbd5e1; color: #475569;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="background-color: #3b82f6; border-color: #3b82f6; padding: 8px 24px;">
                        Submit Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection