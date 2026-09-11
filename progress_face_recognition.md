# Progress & Plan: Fitur Pengenalan Wajah (Face Recognition)

Karena limit API, ini adalah rangkuman dari apa yang sudah dikerjakan dan apa yang harus dilanjutkan pada sesi berikutnya.

## ✅ Yang Sudah Diselesaikan

1. **Download Library & Model:**
   - Model `face-api.js` (`tiny_face_detector`, `face_landmark_68`, `face_recognition`) sudah diunduh ke folder `public/models/`.
   - File JS utama `face-api.min.js` sudah diunduh ke folder `public/js/`.

2. **Database & Model:**
   - Dibuat file migration `2026_09_11_000001_add_face_descriptor_to_users_table.php` untuk menambah kolom `face_descriptor` di tabel `users`.
   - Model `User.php` sudah diperbarui (menambahkan `face_descriptor` ke `$fillable` dan `$casts` sebagai array).

3. **Controller & Routing:**
   - Dibuat `FaceEnrollmentController.php` untuk menangani proses simpan, hapus, dan mengambil descriptor wajah.
   - Ditambahkan route `/face-enrollment`, `/face-descriptors`, `/face-check-own` di `routes/web.php`.
   - Diperbarui `SidebarController.php` untuk menambahkan menu "Daftarkan Wajah Karyawan" di dashboard Superadmin (pada grup KONFIGURASI ABSENSI).

4. **Tampilan (View):**
   - Dibuat view baru `resources/views/absensi/pengaturan/face-enrollment.blade.php` untuk antarmuka Superadmin mendaftarkan wajah karyawan.
   - Diperbarui `absen-masuk.blade.php`: Mengganti FaceDetection (MediaPipe) dengan `face-api.js`, menambah verifikasi identitas `faceVerified`, dan memblokir absensi jika wajah tidak terdaftar atau tidak cocok. Reset `faceVerified = false;` di fungsi `retakePhoto` juga sudah dilakukan.
   - Diperbarui `absen-pulang.blade.php`: Menambahkan logika pengecekan wajah (sama seperti absen masuk) dengan `faceapi.nets` dan `FaceMatcher`. Tombol submit absen pulang baru aktif bila wajah diverifikasi (`faceVerified = true`).

## ⏳ Yang Belum Selesai / Next Steps (Untuk Sesi Berikutnya)

1. **Update `retakePhoto` di `absen-pulang.blade.php`:**
   - Pastikan menambahkan variabel `faceVerified = false;` ke dalam fungsi reset/ambil ulang foto di halaman absen pulang (sama seperti di absen masuk tadi), agar setiap kali user mencoba ambil ulang foto, status verifikasi keriset ulang.

2. **Jalankan Migration Database:**
   - Jalankan perintah: `php artisan migrate` di terminal untuk mengeksekusi penambahan kolom `face_descriptor` ke tabel `users`.

3. **Testing (Uji Coba):**
   - Login sebagai Superadmin, masuk ke menu **Konfigurasi Absensi -> Daftarkan Wajah Karyawan**.
   - Daftarkan wajah minimal satu akun percobaan.
   - Login menggunakan akun percobaan tersebut, lalu coba lakukan Absen Masuk dan Absen Pulang.
   - Pastikan bahwa model kamera di-load dengan benar, proses face recognition berhasil mendeteksi dan mengenali wajah, lalu absensi dapat di-submit ke database.
   - Coba juga skenario negatif: saat wajah tidak terdaftar, atau saat foto wajah tidak cocok dengan user yang sedang login (sistem harus menolak absensi).

---
*Silakan lampirkan file ini (atau beritahu saja lokasi file ini `progress_face_recognition.md`) di chat sesi berikutnya agar agen bisa melanjutkan progres tanpa mengulang dari awal.*
