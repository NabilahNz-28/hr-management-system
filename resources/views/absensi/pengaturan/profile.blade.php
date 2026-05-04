<!-- Profile -->
<div class="page-content" id="profile">
    <div class="content-title">Profile Karyawan</div>
    <p class="content-description">Informasi data diri Anda</p>
    
    <div id="profile-container">
        <!-- Foto Profile -->
        <div>
            <div>
                <img id="foto-profile" src="default-avatar.png" alt="Foto Profile" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
            </div>
            <div>
                <button onclick="gantiFoto()">Ganti Foto</button>
                <input type="file" id="upload-foto" accept="image/*" onchange="uploadFoto(this)" style="display: none;">
            </div>
        </div>
        
        <br>
        
        <!-- Data Diri -->
        <div>
            <div>
                <p>Nama Lengkap</p>
                <p id="profile-nama">Ahmad Wijaya</p>
            </div>
            
            <div>
                <p>NIK</p>
                <p id="profile-nik">001</p>
            </div>
            
            <div>
                <p>Email</p>
                <p id="profile-email">ahmad.wijaya@company.com</p>
            </div>
            
            <div>
                <p>Departemen</p>
                <p id="profile-departemen">IT</p>
            </div>
            
            <div>
                <p>Jabatan</p>
                <p id="profile-jabatan">Staff IT</p>
            </div>
            
            <div>
                <p>No. HP</p>
                <div id="profile-hp-container">
                    <span id="profile-hp">081234567890</span>
                    <button id="btn-edit-hp" onclick="editNoHP()">Edit</button>
                </div>
                <div id="profile-hp-form" style="display: none;">
                    <input type="text" id="input-hp" placeholder="Masukkan nomor HP">
                    <button onclick="simpanNoHP()">Simpan</button>
                    <button onclick="batalEditHP()">Batal</button>
                    <p id="hp-error" style="color: red; display: none;">Nomor HP tidak valid!</p>
                </div>
            </div>
            
            <div>
                <p>Alamat</p>
                <div id="profile-alamat-container">
                    <span id="profile-alamat">-</span>
                    <button id="btn-edit-alamat" onclick="editAlamat()">Edit</button>
                </div>
                <div id="profile-alamat-form" style="display: none;">
                    <textarea id="input-alamat" rows="3" placeholder="Masukkan alamat lengkap Anda"></textarea>
                    <div>
                        <button onclick="simpanAlamat()">Simpan</button>
                        <button onclick="batalEditAlamat()">Batal</button>
                    </div>
                    <p id="alamat-error" style="color: red; display: none;">Alamat tidak boleh kosong!</p>
                </div>
            </div>

            <div>
                <p>Tanggal Bergabung</p>
                <p id="profile-tgl-gabung">01 Januari 2020</p>
            </div>
        </div>
        
        <br>
        
        <!-- Ubah Password -->
        <div>
            <div class="content-title" style="font-size: 16px;">Ubah Password</div>
            <div>
                <input type="password" id="password-lama" placeholder="Password lama">
            </div>
            <div>
                <input type="password" id="password-baru" placeholder="Password baru">
            </div>
            <div>
                <input type="password" id="password-konfirmasi" placeholder="Konfirmasi password baru">
            </div>
            <div>
                <button onclick="ubahPassword()">Ubah Password</button>
            </div>
        </div>
        
        <br>
        
        <!-- Tombol Logout -->
        <div>
            <button onclick="logout()">Keluar / Logout</button>
        </div>
    </div>
</div>

<script>
    // ==================== DATA PROFILE ====================
// Data dummy (nanti diganti dari database)
let profileData = {
    nama: 'Ahmad Wijaya',
    nik: '001',
    email: 'ahmad.wijaya@company.com',
    departemen: 'IT',
    jabatan: 'Staff IT',
    no_hp: '081234567890', // Bisa kosong '' kalau belum terdaftar
    alamat: 'Jl. Merdeka No. 123, Jakarta',
    tgl_gabung: '01 Januari 2020',
    foto: 'default-avatar.png'
};

// ==================== INISIALISASI PROFILE ====================
function initProfile() {
    // Isi data ke dalam elemen
    document.getElementById('profile-nama').textContent = profileData.nama;
    document.getElementById('profile-nik').textContent = profileData.nik;
    document.getElementById('profile-email').textContent = profileData.email;
    document.getElementById('profile-departemen').textContent = profileData.departemen;
    document.getElementById('profile-jabatan').textContent = profileData.jabatan;
    document.getElementById('profile-alamat').textContent = profileData.alamat || '-';
    document.getElementById('profile-tgl-gabung').textContent = profileData.tgl_gabung;
    
    // Cek apakah no HP sudah terdaftar
    if (profileData.no_hp && profileData.no_hp.trim() !== '') {
        // No HP sudah ada
        document.getElementById('profile-hp').textContent = profileData.no_hp;
        document.getElementById('profile-hp-container').style.display = 'block';
        document.getElementById('profile-hp-form').style.display = 'none';
        document.getElementById('btn-edit-hp').style.display = 'inline-block';
    } else {
        // No HP belum terdaftar
        document.getElementById('profile-hp').textContent = '-';
        document.getElementById('profile-hp-container').style.display = 'block';
        document.getElementById('profile-hp-form').style.display = 'none';
        document.getElementById('btn-edit-hp').textContent = 'Tambah No. HP';
        document.getElementById('btn-edit-hp').style.display = 'inline-block';
    }
    // ==================== INISIALISASI ALAMAT ====================
function initAlamat() {
    if (profileData.alamat && profileData.alamat.trim() !== '') {
        // Alamat sudah ada
        document.getElementById('profile-alamat').textContent = profileData.alamat;
        document.getElementById('profile-alamat-container').style.display = 'block';
        document.getElementById('profile-alamat-form').style.display = 'none';
        document.getElementById('btn-edit-alamat').textContent = 'Edit';
        document.getElementById('btn-edit-alamat').style.display = 'inline-block';
    } else {
        // Alamat belum terdaftar
        document.getElementById('profile-alamat').textContent = '-';
        document.getElementById('profile-alamat-container').style.display = 'block';
        document.getElementById('profile-alamat-form').style.display = 'none';
        document.getElementById('btn-edit-alamat').textContent = 'Tambah Alamat';
        document.getElementById('btn-edit-alamat').style.display = 'inline-block';
    }
}
    
    // Foto profile
    if (profileData.foto) {
        document.getElementById('foto-profile').src = profileData.foto;
    }
}

// ==================== EDIT ALAMAT ====================
function editAlamat() {
    // Tampilkan form input
    document.getElementById('profile-alamat-container').style.display = 'none';
    document.getElementById('profile-alamat-form').style.display = 'block';
    
    // Isi textarea dengan alamat yang sudah ada (kalau ada)
    if (profileData.alamat && profileData.alamat.trim() !== '') {
        document.getElementById('input-alamat').value = profileData.alamat;
    } else {
        document.getElementById('input-alamat').value = '';
        document.getElementById('input-alamat').placeholder = 'Masukkan alamat lengkap Anda';
    }
    
    // Sembunyikan error
    document.getElementById('alamat-error').style.display = 'none';
    
    // Focus ke textarea
    document.getElementById('input-alamat').focus();
}

function batalEditAlamat() {
    // Kembalikan ke tampilan normal
    document.getElementById('profile-alamat-container').style.display = 'block';
    document.getElementById('profile-alamat-form').style.display = 'none';
    document.getElementById('alamat-error').style.display = 'none';
    
    // Reset text button
    if (profileData.alamat && profileData.alamat.trim() !== '') {
        document.getElementById('btn-edit-alamat').textContent = 'Edit';
    } else {
        document.getElementById('btn-edit-alamat').textContent = 'Tambah Alamat';
    }
}

function simpanAlamat() {
    const alamat = document.getElementById('input-alamat').value.trim();
    
    // Validasi alamat tidak boleh kosong
    if (!alamat) {
        document.getElementById('alamat-error').textContent = 'Alamat tidak boleh kosong!';
        document.getElementById('alamat-error').style.display = 'block';
        return;
    }
    
    // Validasi minimal panjang alamat
    if (alamat.length < 10) {
        document.getElementById('alamat-error').textContent = 'Alamat terlalu pendek! Minimal 10 karakter.';
        document.getElementById('alamat-error').style.display = 'block';
        return;
    }
    
    // Simpan alamat
    profileData.alamat = alamat;
    document.getElementById('profile-alamat').textContent = alamat;
    document.getElementById('btn-edit-alamat').textContent = 'Edit';
    
    // Sembunyikan form, tampilkan alamat
    document.getElementById('profile-alamat-container').style.display = 'block';
    document.getElementById('profile-alamat-form').style.display = 'none';
    document.getElementById('alamat-error').style.display = 'none';
    
    // Simpan ke localStorage (nanti diganti API ke database)
    localStorage.setItem('profile_alamat', alamat);
    
    alert('Alamat berhasil disimpan!');
}

// ==================== EDIT NO HP ====================
function editNoHP() {
    // Tampilkan form input
    document.getElementById('profile-hp-container').style.display = 'none';
    document.getElementById('profile-hp-form').style.display = 'block';
    
    // Isi input dengan nomor HP yang sudah ada (kalau ada)
    if (profileData.no_hp && profileData.no_hp.trim() !== '') {
        document.getElementById('input-hp').value = profileData.no_hp;
    } else {
        document.getElementById('input-hp').value = '';
        document.getElementById('input-hp').placeholder = 'Masukkan nomor HP Anda';
    }
    
    // Sembunyikan error
    document.getElementById('hp-error').style.display = 'none';
    
    // Focus ke input
    document.getElementById('input-hp').focus();
}

function batalEditHP() {
    // Kembalikan ke tampilan normal
    document.getElementById('profile-hp-container').style.display = 'block';
    document.getElementById('profile-hp-form').style.display = 'none';
    document.getElementById('hp-error').style.display = 'none';
    
    // Reset text button
    if (profileData.no_hp && profileData.no_hp.trim() !== '') {
        document.getElementById('btn-edit-hp').textContent = 'Edit';
    } else {
        document.getElementById('btn-edit-hp').textContent = 'Tambah No. HP';
    }
}

function simpanNoHP() {
    const noHP = document.getElementById('input-hp').value.trim();
    
    // Validasi nomor HP (Indonesia: 08xx / +62xx)
    const regexHP = /^(\+62|62|0)8[1-9][0-9]{7,11}$/;
    
    if (!noHP) {
        document.getElementById('hp-error').textContent = 'Nomor HP tidak boleh kosong!';
        document.getElementById('hp-error').style.display = 'block';
        return;
    }
    
    if (!regexHP.test(noHP)) {
        document.getElementById('hp-error').textContent = 'Format nomor HP tidak valid! Contoh: 081234567890';
        document.getElementById('hp-error').style.display = 'block';
        return;
    }
    
    // Simpan nomor HP
    profileData.no_hp = noHP;
    document.getElementById('profile-hp').textContent = noHP;
    document.getElementById('btn-edit-hp').textContent = 'Edit';
    
    // Sembunyikan form, tampilkan nomor
    document.getElementById('profile-hp-container').style.display = 'block';
    document.getElementById('profile-hp-form').style.display = 'none';
    document.getElementById('hp-error').style.display = 'none';
    
    // Simpan ke localStorage (nanti diganti API ke database)
    localStorage.setItem('profile_no_hp', noHP);
    
    alert('Nomor HP berhasil disimpan!');
}

// ==================== GANTI FOTO ====================
function gantiFoto() {
    document.getElementById('upload-foto').click();
}

function uploadFoto(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi tipe file
    if (!file.type.match('image.*')) {
        alert('File harus berupa gambar!');
        return;
    }
    
    // Validasi ukuran (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5MB!');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        // Update tampilan
        document.getElementById('foto-profile').src = e.target.result;
        
        // Simpan (nanti dikirim ke server)
        profileData.foto = e.target.result;
        localStorage.setItem('profile_foto', e.target.result);
        
        alert('Foto profile berhasil diperbarui!');
    };
    reader.readAsDataURL(file);
}

// ==================== UBAH PASSWORD ====================
function ubahPassword() {
    const passwordLama = document.getElementById('password-lama').value;
    const passwordBaru = document.getElementById('password-baru').value;
    const passwordKonfirmasi = document.getElementById('password-konfirmasi').value;
    
    // Validasi
    if (!passwordLama) {
        alert('Password lama harus diisi!');
        return;
    }
    
    if (!passwordBaru) {
        alert('Password baru harus diisi!');
        return;
    }
    
    if (passwordBaru.length < 6) {
        alert('Password baru minimal 6 karakter!');
        return;
    }
    
    if (passwordBaru !== passwordKonfirmasi) {
        alert('Konfirmasi password tidak cocok!');
        return;
    }
    
    if (passwordLama === passwordBaru) {
        alert('Password baru tidak boleh sama dengan password lama!');
        return;
    }
    
    // Nanti kirim ke server
    // Untuk sekarang simulasi
    alert('Password berhasil diubah! Silakan login ulang.');
    
    // Kosongkan field
    document.getElementById('password-lama').value = '';
    document.getElementById('password-baru').value = '';
    document.getElementById('password-konfirmasi').value = '';
}

// ==================== LOGOUT ====================
function logout() {
    const konfirmasi = confirm('Apakah Anda yakin ingin keluar?');
    
    if (konfirmasi) {
        // Hapus session
        sessionStorage.clear();
        localStorage.removeItem('is_logged_in');
        
        alert('Anda telah logout.');
        
        // Redirect ke halaman login (nanti disesuaikan)
        // window.location.href = '/login';
    }
}

// ==================== LOAD DATA DARI LOCAL STORAGE ====================
function loadProfileFromStorage() {
    const savedHP = localStorage.getItem('profile_no_hp');
    const savedFoto = localStorage.getItem('profile_foto');
    
    if (savedHP) {
        profileData.no_hp = savedHP;
    }
    
    if (savedFoto) {
        profileData.foto = savedFoto;
    }
}

// ==================== INISIALISASI ====================
document.addEventListener('DOMContentLoaded', function() {
    loadProfileFromStorage();
    initProfile();
});
    </script>
