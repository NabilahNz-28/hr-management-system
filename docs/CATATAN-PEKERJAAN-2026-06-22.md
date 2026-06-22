# Catatan Pekerjaan — 22 Juni 2026

Ringkasan semua perubahan yang dikerjakan hari ini, supaya mudah dibaca kembali.

---

## 1. Halaman Login dipermak lebih sederhana + LOGO
- Layout dua kolom (panel biru "HR System ERP") dihapus → jadi **satu kartu terpusat**.
- Menambahkan **LOGO.jpeg** di atas judul (`asset('photos/LOGO.jpeg')`).
- File: `resources/views/auth/login.blade.php`.

## 2. Pagination Data Karyawan
- View aktif: `resources/views/superadmin/karyawan/data.blade.php` (sudah `paginate(10)`).
- Perbaikan:
  - Nomor urut **berlanjut antar halaman** (halaman 2 mulai dari 11).
  - Pagination kustom yang rapi: info "Menampilkan x–y dari N" + tombol prev/nomor/next.
- Menambahkan **10 data karyawan dummy** ke DB supaya jumlah ≥ 10 (pagination terlihat).

## 3. Badge Role PIC
- Sebelumnya badge **PIC** tidak punya warna.
- Menambahkan style `.role-pic` (gradient oranye) di `public/css/style.css`.

## 4. Halaman Data Inventory
- Tampilan diseragamkan dengan Data Karyawan (`content-title`, tabel `data-table-laporan`, filter bar rapi).
- Controller `inventoryIndex` diubah ke `->paginate(10)->withQueryString()` (filter terbawa antar halaman).
- Nomor urut berlanjut + pagination kustom.
- Menambahkan **5 item inventory** + **14 record stock opname** untuk uji pagination.
- File: `resources/views/superadmin/inventory/data-inventory.blade.php`, `SuperadminController.php`.

## 5. Halaman Transfer Stock
- Diseragamkan sama seperti Data Inventory/Karyawan.
- Controller `transferIndex` → `->paginate(10)->withQueryString()`.
- Badge status berwarna (Selesai hijau, Pending kuning, Dibatalkan merah).
- Menambahkan **13 record transfer** untuk uji pagination.
- File: `resources/views/superadmin/inventory/transfer-stock.blade.php`, `SuperadminController.php`.

---

## 6. Setup Email (untuk fitur lupa/reset password)
- `.env` dikonfigurasi untuk **SMTP Gmail** (host `smtp.gmail.com`, port 587, TLS), beserta blok alternatif Mailtrap.
- **Catatan penting:** jaringan kantor/ISP saat ini **memblokir semua port SMTP** (25/465/587/2525) — terbukti dari tes koneksi (hanya port 443/HTTPS yang terbuka).
  - Akibatnya Gmail SMTP **timeout** di jaringan ini.
  - Solusi: pakai jaringan tanpa blokir (**hotspot HP / VPN**), lalu `php artisan config:clear`.
  - Untuk testing tanpa kirim email asli, sementara bisa set `MAIL_MAILER=log` (email tertulis ke `storage/logs/laravel.log`).
- `.env` TIDAK di-commit (gitignored) — kredensial aman.

## 7. Fitur Lupa Password & Reset Password (baru)
- Sebelumnya belum ada sama sekali. Dibuat memakai password broker bawaan Laravel.
- Method baru di `AuthController`: `showForgotPassword`, `sendResetLink`, `showResetPassword`, `resetPassword`.
- Route baru: `password.request`, `password.email`, `password.reset`, `password.update`.
- View baru: `resources/views/auth/forgot-password.blade.php`, `reset-password.blade.php` (gaya senada login + LOGO).
- Link "Lupa password?" di halaman login kini aktif.

## 8. Keamanan Login (anti brute-force) + Rate Limiting
- **Throttle login per email+IP** di `AuthController@login`: maks **5 percobaan gagal**, lalu dikunci **60 detik**. Counter reset saat login berhasil. Ada pesan sisa percobaan & sisa waktu kunci.
- **Backstop rate limit per-IP** di route:
  - `POST /login` → 30/menit
  - `POST /forgot-password` & `POST /reset-password` → 6/menit
- RateLimiter pakai cache `file` (sudah aktif).

## 9. Validasi Kekuatan Password
- Aturan: **min. 8 karakter + huruf besar & kecil + angka + simbol**.
- Diterapkan di tempat akun benar-benar dibuat (oleh Superadmin):
  - `SuperadminController@karyawanStore` (Insert Karyawan) — sebelumnya hanya `min:6`.
  - `SuperadminController@karyawanUpdate` (Edit Karyawan) — opsional, hanya jika diisi.
  - `AuthController@resetPassword` (Reset Password).
- Hint persyaratan ditambahkan di form insert & edit karyawan.

## 10. Registrasi publik dinonaktifkan
- Klarifikasi: pendaftaran akun **hanya** lewat Superadmin → Data Karyawan, tidak ada self-register publik.
- `POST /register` dihapus; `GET /register` dipertahankan tapi **redirect ke login** dengan pesan "Pendaftaran hanya melalui HRD / Superadmin" (agar link `route('register')` yang sudah ada tidak rusak).

---

## 11. Bug Absensi: data bocor & jam kerja salah antar akun
Masalah yang dilaporkan: absen pakai paranditha, ganti akun, status jam kerja ikut terbawa / hilang.

### Penyebab
- Status jam kerja awalnya disimpan di **browser** (`sessionStorage`/`localStorage`), tidak terikat user & tidak dibersihkan saat logout → bocor antar akun.
- Logika lama hanya cek "ada masuk hari ini?" — tidak melihat status sesi sebenarnya.

### Perbaikan
1. **Guard multi-user** di halaman absen masuk & pulang: data absensi di browser ditandai milik user ID yang login; jika ganti akun, otomatis dibersihkan.
2. **Status absensi diambil dari server/DB**, bukan storage browser (endpoint baru `absensi/cek-hari-ini`).
3. **Logika stateful** berdasarkan record TERAKHIR hari ini (`AttendanceController`):
   - terakhir `masuk` → **sedang bekerja** (jam kerja jalan, boleh pulang).
   - terakhir `pulang` → **sesi selesai** (tidak boleh pulang lagi sebelum masuk baru).
   - tidak ada record → **belum absen**.
4. **Penjagaan di server** (`store()`):
   - Pulang ditolak jika tidak sedang bekerja.
   - Masuk ditolak jika sedang bekerja (cegah double-masuk).
5. **Alert/popup**: tidak bisa absen pulang jika belum absen masuk (cek ke server + enforce server-side).
- File: `AttendanceController.php`, `routes/web.php`, `absen-masuk.blade.php`, `absen-pulang.blade.php`.

### Pembersihan data
- Menghapus 11 record absensi test tanggal 2026-06-22 agar bisa tes dari kondisi bersih.

---

## Cara test cepat absensi (setelah perbaikan)
1. paranditha → absen masuk → jam kerja jalan.
2. Logout → karyawan → coba absen pulang langsung → **ditolak** ("belum absen masuk").
3. karyawan → absen masuk → absen pulang → sukses.
4. Login paranditha lagi → jam kerja **tetap jalan** (record terakhir masih `masuk`).

## Catatan tindak lanjut
- Saat di jaringan tanpa blokir SMTP: `MAIL_MAILER=smtp` lalu `php artisan config:clear` untuk kirim email reset password asli.
- Foto absensi hasil testing di `public/photos/attendance_*.jpg` tidak di-commit (artefak test).
