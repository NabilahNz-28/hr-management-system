@extends('layouts.absen')

@section('title', 'Profile Karyawan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>Profile Karyawan</h1>
            <p>Anda hanya dapat mengubah alamat dan password</p>
        </div>

        <div class="profile-card">
            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->nik }}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Departemen</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->departemen }}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->jabatan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Bergabung</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->tgl_bergabung }}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->no_hp }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', auth()->user()->alamat) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-small">Simpan Alamat</button>
                </div>
            </form>

            <div class="password-section">
                <h3>Ganti Password</h3>
                <form method="POST" action="{{ route('profile.password.update') }}" class="password-form">
                    @csrf
                    @method('PUT')

                    <div class="password-input-group">
                        <input type="password" id="absen_current_password" name="current_password" class="form-control" placeholder="Password lama" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('absen_current_password', this)" title="Tampilkan/Sembunyikan Password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="password-input-group">
                        <input type="password" id="absen_password" name="password" class="form-control" placeholder="Password baru" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('absen_password', this)" title="Tampilkan/Sembunyikan Password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="password-input-group">
                        <input type="password" id="absen_password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('absen_password_confirmation', this)" title="Tampilkan/Sembunyikan Password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary btn-small">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection