@extends('layouts.superadmin')

@section('title', 'Edit Data Karyawan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active">
        <div class="content-title">Edit Data Karyawan</div>
        <p class="content-description">Ubah data karyawan perusahaan</p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container-karyawan">
            <form action="{{ route('superadmin.karyawan.update', $karyawan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ old('name', $karyawan->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" class="form-control"
                               value="{{ old('nik', $karyawan->nik) }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{ old('email', $karyawan->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="departemen">Departemen <span class="required">*</span></label>
                        <select id="departemen" name="departemen" class="form-control" required>
                            <option value="">-- Pilih Departemen --</option>
                            <option value="ADMIN RESI" {{ old('departemen', $karyawan->departemen) == 'ADMIN RESI' ? 'selected' : '' }}>ADMIN RESI</option>
                            <option value="ADMIN CC" {{ old('departemen', $karyawan->departemen) == 'ADMIN CC' ? 'selected' : '' }}>ADMIN CC</option>
                            <option value="OUTGOING" {{ old('departemen', $karyawan->departemen) == 'OUTGOING' ? 'selected' : '' }}>OUTGOING</option>
                            <option value="INCOMING" {{ old('departemen', $karyawan->departemen) == 'INCOMING' ? 'selected' : '' }}>INCOMING</option>
                            <option value="RETUR" {{ old('departemen', $karyawan->departemen) == 'RETUR' ? 'selected' : '' }}>RETUR</option>
                            <option value="TRANSPORTER" {{ old('departemen', $karyawan->departemen) == 'TRANSPORTER' ? 'selected' : '' }}>TRANSPORTER</option>
                            <option value="PROCESSING" {{ old('departemen', $karyawan->departemen) == 'PROCESSING' ? 'selected' : '' }}>PROCESSING</option>
                            <option value="LAINNYA" {{ old('departemen', $karyawan->departemen) == 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" class="form-control"
                               value="{{ old('jabatan', $karyawan->jabatan) }}">
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="superadmin" {{ old('role', $karyawan->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role', $karyawan->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <!-- <option value="inventory" {{ old('role', $karyawan->role) == 'inventory' ? 'selected' : '' }}>Inventory</option> -->
                            <option value="pic" {{ old('role', $karyawan->role) == 'pic' ? 'selected' : '' }}>PIC</option>
                            <option value="karyawan" {{ old('role', $karyawan->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $karyawan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Kosongkan jika tidak ingin ganti password">
                        <small class="text-muted">Isi hanya jika ingin mengganti password. Min. 8 karakter, kombinasi huruf besar &amp; kecil, angka, dan simbol.</small>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-container-karyawan {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-top: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    outline: none;
    transition: 0.3s;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-top: 16px;
}

.alert-success {
    background: #d1e7dd;
    color: #0f5132;
}

.alert-danger {
    background: #f8d7da;
    color: #842029;
}

.text-muted {
    font-size: 13px;
    color: #6c757d;
    margin-top: 6px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection