@extends('layouts.absen')

@section('title', 'Profile Karyawan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <h1>Profile Karyawan</h1>
            <p>Kelola data diri Anda</p>
        </div>

        <!-- Card Profile -->
        <div class="profile-card">
            <!-- Foto Profile -->
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img id="foto-profile" src="{{ asset('default-avatar.png') }}" alt="Foto Profile">
                    <button type="button" class="btn-avatar" onclick="gantiFoto()">Ganti Foto</button>
                </div>
                <input type="file" id="upload-foto" accept="image/*" style="display: none;" onchange="uploadFoto(this)">
            </div>

            <!-- Form Data Diri (Grid 2 kolom) -->
            <form id="form-profile" class="profile-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="profile-nama" class="form-control" value="Ahmad Wijaya">
                    </div>
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" id="profile-nik" class="form-control" value="001" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="profile-email" class="form-control" value="ahmad.wijaya@company.com" readonly>
                    </div>
                    <div class="form-group">
                        <label>Departemen</label>
                        <input type="text" id="profile-departemen" class="form-control" value="IT" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" id="profile-jabatan" class="form-control" value="Staff IT" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Bergabung</label>
                        <input type="text" id="profile-tgl-gabung" class="form-control" value="01 Januari 2020" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="tel" id="profile-hp" class="form-control" value="081234567890" placeholder="Masukkan nomor HP">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea id="profile-alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap">Jl. Merdeka No. 123, Jakarta</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-primary btn-small" onclick="simpanProfile()">Simpan</button>
                </div>
            </form>

            <!-- Ubah Password -->
            <div class="password-section">
                <h3>Ubah Password</h3>
                <div class="password-form">
                    <input type="password" id="password-lama" class="form-control" placeholder="Password lama">
                    <input type="password" id="password-baru" class="form-control" placeholder="Password baru">
                    <input type="password" id="password-konfirmasi" class="form-control" placeholder="Konfirmasi password baru">
                    <button type="button" class="btn btn-primary btn-small" onclick="ubahPassword()">Ubah Password</button>
                </div>
            </div>

            <!-- Logout -->
            <div class="logout-section">
                <button type="button" class="btn btn-danger btn-small" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data profile (bisa diisi dari database nanti)
    let profileData = {
        nama: 'Ahmad Wijaya',
        nik: '001',
        email: 'ahmad.wijaya@company.com',
        departemen: 'IT',
        jabatan: 'Staff IT',
        no_hp: '081234567890',
        alamat: 'Jl. Merdeka No. 123, Jakarta',
        tgl_gabung: '01 Januari 2020',
        foto: '{{ asset("default-avatar.png") }}'
    };

    // Load dari localStorage jika ada
    function loadFromStorage() {
        const saved = localStorage.getItem('profile_data');
        if (saved) {
            try {
                const data = JSON.parse(saved);
                Object.assign(profileData, data);
            } catch(e) {}
        }
        // Isi ke form
        document.getElementById('profile-nama').value = profileData.nama;
        document.getElementById('profile-nik').value = profileData.nik;
        document.getElementById('profile-email').value = profileData.email;
        document.getElementById('profile-departemen').value = profileData.departemen;
        document.getElementById('profile-jabatan').value = profileData.jabatan;
        document.getElementById('profile-tgl-gabung').value = profileData.tgl_gabung;
        document.getElementById('profile-hp').value = profileData.no_hp || '';
        document.getElementById('profile-alamat').value = profileData.alamat || '';
        document.getElementById('foto-profile').src = profileData.foto;
    }

    function simpanProfile() {
        // Ambil nilai dari form
        const newData = {
            nama: document.getElementById('profile-nama').value,
            nik: document.getElementById('profile-nik').value,
            email: document.getElementById('profile-email').value,
            departemen: document.getElementById('profile-departemen').value,
            jabatan: document.getElementById('profile-jabatan').value,
            no_hp: document.getElementById('profile-hp').value,
            alamat: document.getElementById('profile-alamat').value,
            tgl_gabung: document.getElementById('profile-tgl-gabung').value,
            foto: profileData.foto
        };

        // Validasi sederhana
        if (!newData.nama) return alert('Nama tidak boleh kosong');
        if (!newData.no_hp) return alert('No. HP tidak boleh kosong');
        const regexHP = /^(\+62|62|0)8[1-9][0-9]{7,11}$/;
        if (!regexHP.test(newData.no_hp)) return alert('Format nomor HP tidak valid (contoh: 081234567890)');
        if (!newData.alamat) return alert('Alamat tidak boleh kosong');
        if (newData.alamat.length < 10) return alert('Alamat minimal 10 karakter');

        // Simpan ke object dan localStorage
        Object.assign(profileData, newData);
        localStorage.setItem('profile_data', JSON.stringify(profileData));

        alert('Data profile berhasil disimpan!');
    }

    // Ganti foto
    function gantiFoto() {
        document.getElementById('upload-foto').click();
    }

    function uploadFoto(input) {
        const file = input.files[0];
        if (!file) return;
        if (!file.type.match('image.*')) return alert('File harus gambar');
        if (file.size > 5 * 1024 * 1024) return alert('Maksimal 5MB');
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-profile').src = e.target.result;
            profileData.foto = e.target.result;
            localStorage.setItem('profile_data', JSON.stringify(profileData));
            alert('Foto berhasil diupdate');
        };
        reader.readAsDataURL(file);
    }

    // Ubah password
    function ubahPassword() {
        const lama = document.getElementById('password-lama').value;
        const baru = document.getElementById('password-baru').value;
        const konfirmasi = document.getElementById('password-konfirmasi').value;
        if (!lama) return alert('Password lama harus diisi');
        if (!baru) return alert('Password baru harus diisi');
        if (baru.length < 6) return alert('Password baru minimal 6 karakter');
        if (baru !== konfirmasi) return alert('Konfirmasi password tidak cocok');
        if (lama === baru) return alert('Password baru tidak boleh sama dengan lama');
        alert('Password berhasil diubah. Silakan login ulang.');
        document.getElementById('password-lama').value = '';
        document.getElementById('password-baru').value = '';
        document.getElementById('password-konfirmasi').value = '';
    }

    // Logout
    function logout() {
        if (confirm('Apakah anda yakin ingin keluar?')) {
            sessionStorage.clear();
            localStorage.removeItem('is_logged_in');
            alert('Anda telah logout');
            // window.location.href = '/login';
        }
    }

    document.addEventListener('DOMContentLoaded', loadFromStorage);
</script>
@endsection