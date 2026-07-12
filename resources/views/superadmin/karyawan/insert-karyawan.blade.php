@extends('layouts.superadmin')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="insert-karyawan">
        <div class="content-title">Tambah Karyawan Baru</div>
        <p class="content-description">Superadmin mengisi data awal akun karyawan</p>

        <div class="form-card">
            <form method="POST" action="{{ route('superadmin.karyawan.store') }}">
                @csrf

                <div class="form-section">
                    <h3 class="form-section-title">Data Akun</h3>
                    <div class="form-row-grid">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>NIK <span class="required">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" required>
                            @error('nik')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email Aktif <span class="required">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>No. HP <span class="required">*</span></label>
                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp') }}" required>
                            @error('no_hp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password Awal <span class="required">*</span></label>
                            <div class="password-input-group">
                                <input type="password" id="insert_password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('insert_password', this)" title="Tampilkan/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min. 8 karakter, kombinasi huruf besar &amp; kecil, angka, dan simbol.</small>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password <span class="required">*</span></label>
                            <div class="password-input-group">
                                <input type="password" id="insert_password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('insert_password_confirmation', this)" title="Tampilkan/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Data Karyawan</h3>
                    <div class="form-row-grid">
                        <div class="form-group">
                            <label>Departemen <span class="required">*</span></label>
                            <select class="form-control @error('departemen') is-invalid @enderror" name="departemen" required>
                                <option value="">-- Pilih Departemen --</option>
                                <option value="ADMIN RESI" @if(old('departemen') === 'ADMIN RESI') selected @endif>ADMIN RESI</option>
                                <option value="ADMIN CC" @if(old('departemen') === 'ADMIN CC') selected @endif>ADMIN CC</option>
                                <option value="OUTGOING" @if(old('departemen') === 'OUTGOING') selected @endif>OUTGOING</option>
                                <option value="INCOMING" @if(old('departemen') === 'INCOMING') selected @endif>INCOMING</option>
                                <option value="RETUR" @if(old('departemen') === 'RETUR') selected @endif>RETUR</option>
                                <option value="TRANSPORTER" @if(old('departemen') === 'TRANSPORTER') selected @endif>TRANSPORTER</option>
                                <option value="PROCESSING" @if(old('departemen') === 'PROCESSING') selected @endif>PROCESSING</option>
                                <option value="LAINNYA" @if(old('departemen') === 'LAINNYA') selected @endif>LAINNYA</option>
                            </select>
                            @error('departemen')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jabatan <span class="required">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Admin Resi" required>
                            @error('jabatan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role / Hak Akses <span class="required">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="karyawan" @if(old('role') === 'karyawan') selected @endif>Karyawan</option>
                                <option value="pic" @if(old('role') === 'pic') selected @endif>PIC / Inventory</option>
                                <option value="superadmin" @if(old('role') === 'superadmin') selected @endif>Superadmin</option>
                            </select>
                            @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="aktif" @if(old('status', 'aktif') === 'aktif') selected @endif>Aktif</option>
                                <option value="nonaktif" @if(old('status') === 'nonaktif') selected @endif>Nonaktif</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Bergabung</label>
                            <input type="date" class="form-control @error('tgl_bergabung') is-invalid @enderror" name="tgl_bergabung" value="{{ old('tgl_bergabung', date('Y-m-d')) }}">
                            @error('tgl_bergabung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-secondary btn-small">Batal</a>
                    <button type="submit" class="btn btn-primary btn-small">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    #insert-karyawan .form-card {
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
    }
    #insert-karyawan .form-actions {
        flex-direction: column;
        gap: 10px;
    }
    #insert-karyawan .form-actions .btn {
        width: 100%;
        text-align: center;
        margin: 0 !important;
    }
}
</style>
@endsection