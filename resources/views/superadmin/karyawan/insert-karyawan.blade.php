@extends('layouts.superadmin')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="insert-karyawan">
        <div class="content-title">Tambah Karyawan Baru</div>
        <p class="content-description">Isi data karyawan baru dengan lengkap</p>

        <!-- Form Card -->
        <div class="form-card">
            <form method="POST" action="{{ route('superadmin.karyawan.store') }}">
                @csrf

                <!-- Data Pribadi -->
                <div class="form-section">
                    <h3 class="form-section-title">Data Pribadi</h3>
                    <div class="form-row-grid">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>NIK <span class="required">*</span></label>
                            <input type="text" class="form-control" name="nik" placeholder="Nomor Induk Karyawan" required>
                        </div>
                        <div class="form-group">
                            <label>Email Aktif <span class="required">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="contoh@perusahaan.com" required>
                        </div>
                        <div class="form-group">
                            <label>No. HP <span class="required">*</span></label>
                            <input type="tel" class="form-control" name="no_hp" placeholder="081234567890" required>
                        </div>
                        <div class="form-group">
                            <label>Password <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>

                <!-- Data Perusahaan -->
                <div class="form-section">
                    <h3 class="form-section-title">Data Perusahaan</h3>
                    <div class="form-row-grid">
                        <div class="form-group">
                            <label>Departemen</label>
                            <select class="form-control" name="departemen">
                                <option value="">-- Pilih Departemen --</option>
                                <option value="IT">IT</option>
                                <option value="HRD">HRD</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Produksi">Produksi</option>
                                <option value="Inventory">Inventory</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" name="jabatan" placeholder="Contoh: Staff IT, Manager, dll">
                        </div>
                        <div class="form-group">
                            <label>Role / Hak Akses <span class="required">*</span></label>
                            <select class="form-control" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="karyawan">Karyawan Regular</option>
                                <option value="inventory">Inventory</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Bergabung</label>
                            <input type="date" class="form-control" name="tgl_bergabung" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Data Alamat -->
                <div class="form-section">
                    <h3 class="form-section-title">Data Alamat</h3>
                    <div class="form-row-grid">
                        <div class="form-group full-width">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-secondary btn-small">Batal</a>
                    <button type="submit" class="btn btn-primary btn-small">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection