@extends('layouts.absen')

@section('title', 'Profile Super Administrator')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="profile-superadmin">
        <div class="content-title">Profile Super Administrator</div>
        <p class="content-description">Kelola data diri Anda</p>

        <!-- Card Profile -->
        <div class="profile-card-superadmin">
            <!-- Foto Profile -->
            <div class="avatar-section-superadmin">
                <div class="avatar-wrapper-superadmin">
                    <img id="foto-profile" src="{{ asset('default-avatar.png') }}" alt="Foto Profile">
                    <button type="button" class="btn-avatar-superadmin" onclick="gantiFoto()">Ganti Foto</button>
                </div>
                <input type="file" id="upload-foto" accept="image/*" style="display: none;" onchange="uploadFoto(this)">
            </div>

            <!-- Badge Role -->
            <div class="role-badge">
                <span class="role-badge-text">Super Administrator</span>
            </div>

            <!-- Form Data Diri (Grid 2 kolom) -->
            <form id="form-profile" class="profile-form-superadmin">
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>Nama Lengkap</label>
                        <input type="text" id="profile-nama" class="form-control" value="Admin Utama">
                    </div>
                    <div class="form-group-superadmin">
                        <label>Username</label>
                        <input type="text" id="profile-username" class="form-control" value="superadmin" readonly>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>Email</label>
                        <input type="email" id="profile-email" class="form-control" value="admin@company.com" readonly>
                    </div>
                    <div class="form-group-superadmin">
                        <label>Role</label>
                        <input type="text" id="profile-role" class="form-control" value="Super Administrator" readonly>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>No. HP</label>
                        <input type="tel" id="profile-hp" class="form-control" value="081234567890" placeholder="Masukkan nomor HP">
                    </div>
                    <div class="form-group-superadmin">
                        <label>Alamat</label>
                        <textarea id="profile-alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap">Jl. Sudirman No. 45, Jakarta Pusat</textarea>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>Tanggal Bergabung</label>
                        <input type="text" id="profile-tgl-gabung" class="form-control" value="01 Januari 2020" readonly>
                    </div>
                    <div class="form-group-superadmin">
                        <label>Terakhir Login</label>
                        <input type="text" id="profile-last-login" class="form-control" value="24 Mei 2026, 08:30 WIB" readonly>
                    </div>
                </div>

                <div class="form-actions-superadmin">
                    <button type="button" class="btn btn-primary btn-small" onclick="simpanProfile()">Simpan Perubahan</button>
                </div>
            </form>

            <!-- Ubah Password -->
            <div class="password-section-superadmin">
                <h3>Ubah Password</h3>
                <div class="password-form-superadmin">
                    <input type="password" id="password-lama" class="form-control" placeholder="Password lama">
                    <input type="password" id="password-baru" class="form-control" placeholder="Password baru">
                    <input type="password" id="password-konfirmasi" class="form-control" placeholder="Konfirmasi password baru">
                    <button type="button" class="btn btn-primary btn-small" onclick="ubahPassword()">Ubah Password</button>
                </div>
            </div>

            <!-- Logout -->
            <div class="logout-section-superadmin">
                <button type="button" class="btn btn-danger btn-small" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data profile superadmin (bisa diisi dari database nanti)
    let profileData = {
        nama: 'Admin Utama',
        username: 'superadmin',
        email: 'admin@company.com',
        role: 'Super Administrator',
        no_hp: '081234567890',
        alamat: 'Jl. Sudirman No. 45, Jakarta Pusat',
        tgl_gabung: '01 Januari 2020',
        last_login: '24 Mei 2026, 08:30 WIB',
        foto: '{{ asset("default-avatar.png") }}'
    };

    // Load dari localStorage jika ada
    function loadFromStorage() {
        const saved = localStorage.getItem('profile_superadmin_data');
        if (saved) {
            try {
                const data = JSON.parse(saved);
                Object.assign(profileData, data);
            } catch(e) {}
        }
        // Isi ke form
        document.getElementById('profile-nama').value = profileData.nama;
        document.getElementById('profile-username').value = profileData.username;
        document.getElementById('profile-email').value = profileData.email;
        document.getElementById('profile-role').value = profileData.role;
        document.getElementById('profile-hp').value = profileData.no_hp || '';
        document.getElementById('profile-alamat').value = profileData.alamat || '';
        document.getElementById('profile-tgl-gabung').value = profileData.tgl_gabung;
        document.getElementById('profile-last-login').value = profileData.last_login;
        document.getElementById('foto-profile').src = profileData.foto;
    }

    function simpanProfile() {
        // Ambil nilai dari form
        const newData = {
            nama: document.getElementById('profile-nama').value,
            username: document.getElementById('profile-username').value,
            email: document.getElementById('profile-email').value,
            role: document.getElementById('profile-role').value,
            no_hp: document.getElementById('profile-hp').value,
            alamat: document.getElementById('profile-alamat').value,
            tgl_gabung: document.getElementById('profile-tgl-gabung').value,
            last_login: document.getElementById('profile-last-login').value,
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
        localStorage.setItem('profile_superadmin_data', JSON.stringify(profileData));

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
            localStorage.setItem('profile_superadmin_data', JSON.stringify(profileData));
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