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
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>NIK <span class="required">*</span></label>
                            <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email Aktif <span class="required">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label>No. HP <span class="required">*</span></label>
                            <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Password Awal <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Data Karyawan</h3>
                    <div class="form-row-grid">
                        <div class="form-group">
                            <label>Departemen <span class="required">*</span></label>
                            <select class="form-control" name="departemen" required>
                                <option value="">-- Pilih Departemen --</option>
                                <option value="ADMIN RESI">ADMIN RESI</option>
                                <option value="ADMIN CC">ADMIN CC</option>
                                <option value="OUTGOING">OUTGOING</option>
                                <option value="INCOMING">INCOMING</option>
                                <option value="RETUR">RETUR</option>
                                <option value="TRANSPORTER">TRANSPORTER</option>
                                <option value="PROCESSING">PROCESSING</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jabatan <span class="required">*</span></label>
                            <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Admin Resi" required>
                        </div>

                        <div class="form-group">
                            <label>Role / Hak Akses <span class="required">*</span></label>
                            <select class="form-control" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="pic">PIC / Inventory</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <select class="form-control" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Bergabung</label>
                            <input type="date" class="form-control" name="tgl_bergabung" value="{{ old('tgl_bergabung', date('Y-m-d')) }}">
                        </div>

                        <div class="form-group full-width">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat" rows="3">{{ old('alamat') }}</textarea>
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
@endsection