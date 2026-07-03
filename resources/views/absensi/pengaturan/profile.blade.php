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

        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 14px; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 14px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

                    <input type="password" name="current_password" class="form-control" placeholder="Password lama" required>
                    <input type="password" name="password" class="form-control" placeholder="Password baru" required>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>

                    <button type="submit" class="btn btn-primary btn-small">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection