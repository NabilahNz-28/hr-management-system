# Penyelesaian Revisi Jurnal: Sistem HR Management ERP

**Judul Sistem:** HR Management & Inventory ERP  
**Teknologi:** Laravel (PHP Framework), MySQL, MediaPipe Face Detection (JavaScript CDN), Leaflet.js, Bootstrap 5  
**Tanggal Dokumen:** 18 Agustus 2026  

---

## Revisi 1: Metodologi Penelitian Belum Dijelaskan Secara Sistematis

### Jawaban / Klarifikasi

Sistem HR Management ERP ini dikembangkan menggunakan metodologi **Waterfall** yang dipadukan dengan pendekatan **iteratif berbasis prototipe (Prototype)**. Pemilihan metodologi ini didasarkan pada karakteristik proyek yaitu kebutuhan sistem yang dapat didefinisikan secara bertahap, serta adanya proses validasi langsung dari pengguna di setiap iterasi pengembangan.

---

### Tahapan Metodologi yang Diterapkan

#### Fase 1 — Analisis Kebutuhan (Requirements Analysis)

Pada tahap ini, dilakukan identifikasi kebutuhan sistem yang mencakup:

- **Kebutuhan fungsional:** Sistem absensi berbasis pengenalan wajah (face recognition), manajemen data karyawan, pengajuan dan persetujuan izin/cuti, manajemen inventaris barang, laporan rekap absensi, serta sistem autentikasi multi-role.
- **Kebutuhan non-fungsional:** Keamanan login (anti brute-force), validasi berbasis GPS (geofencing radius 100 meter dari kantor), responsivitas tampilan di perangkat mobile, dan keamanan data (registrasi hanya oleh Superadmin).
- **Tiga peran pengguna (role)** yang didefinisikan: `superadmin`, `pic` (Person In Charge), dan `karyawan`.

#### Fase 2 — Desain Sistem (System Design)

Desain sistem dibagi menjadi dua bagian:

**a. Desain Arsitektur**

Sistem menggunakan pola arsitektur **MVC (Model-View-Controller)** bawaan Laravel:

```
Browser (Pengguna)
    |
    v
Routes (web.php)          <- Pengarah request
    |
    v
Controller                <- Logika bisnis
    |
    +---> Model (Eloquent ORM)  <- Akses Database MySQL
    |
    +---> View (Laravel Blade)  <- Tampilan HTML dinamis
```

**b. Desain Database**

Terdapat **9 tabel utama** yang dirancang melalui Laravel Migrations:

| Tabel | Fungsi |
|---|---|
| `users` | Data akun karyawan (name, email, password, role, nik, jabatan, departemen, dll.) |
| `attendances` | Rekam absensi masuk/pulang + foto + koordinat GPS |
| `leaves` | Data pengajuan izin dan cuti |
| `leave_types` | Jenis-jenis cuti (tahunan, sakit, melahirkan, dll.) |
| `employee_leave_quotas` | Kuota cuti per karyawan |
| `leave_documents` | Dokumen lampiran pengajuan |
| `leave_status_history` | Riwayat perubahan status persetujuan |
| `inventories` | Data stok barang |
| `stock_opnames` | Rekam hasil opname stok |
| `transfer_stocks` | Rekam transfer stok antar gudang |

**c. Desain Antarmuka (UI/UX)**

- Antarmuka awalnya dibangun sebagai **SPA (Single Page Application)** statis menggunakan HTML murni.
- Kemudian **dimigrasi ke Multi-Page Application (MPA)** dinamis menggunakan **Laravel Blade & Controller** agar data dapat ditampilkan secara real-time dari database.

#### Fase 3 — Implementasi (Implementation)

Implementasi dilakukan secara bertahap berdasarkan modul:

1. **Modul Autentikasi** — `AuthController`: Login multi-role, logout, lupa password, reset password via email SMTP, throttle login (maks. 5 percobaan gagal/60 detik).
2. **Modul Absensi** — `AttendanceController` + `absen-masuk.blade.php` + `absen-pulang.blade.php`: Absensi dengan kamera, deteksi wajah MediaPipe, validasi GPS Haversine.
3. **Modul Manajemen Karyawan** — `SuperadminController`: CRUD data karyawan, pengaturan role, pagination.
4. **Modul Persetujuan Izin/Cuti** — `LeaveController` + `SuperadminController`: Pengajuan, approval, dan penolakan oleh Superadmin/PIC.
5. **Modul Inventaris** — `InventoryController`: Tambah barang, stock opname, transfer stok, laporan, ekspor Excel.
6. **Modul Laporan & Monitoring** — `AbsensiReportController`: Rekap harian dan bulanan absensi.

#### Fase 4 — Pengujian (Testing)

Pengujian dilakukan secara fungsional per fitur (diuraikan lengkap pada **Revisi 3**).

#### Fase 5 — Pemeliharaan (Maintenance)

Setelah setiap sesi pengembangan, catatan perubahan, bug yang ditemukan, dan solusi penyelesaiannya didokumentasikan secara sistematis di folder `docs/` (file `catatan-integrasi.md`, `CATATAN-PEKERJAAN-2026-06-22.md`, `CATATAN-PEKERJAAN-2026-06-27.md`). Hal ini dilakukan sebagai bagian dari proses pemeliharaan dan transfer pengetahuan tim.

---

## Revisi 2: Implementasi MediaPipe Face Detection — Parameter dan Mekanisme Validasi

### Jawaban / Klarifikasi

Implementasi MediaPipe Face Detection pada sistem ini dilakukan sepenuhnya di sisi **klien (client-side)** menggunakan JavaScript, dimuat melalui **CDN (Content Delivery Network)**. Berikut penjelasan teknis lengkap mengenai parameter dan mekanisme validasinya.

---

### A. Library yang Digunakan

```html
<!-- Dimuat via CDN di bagian @section('scripts') -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js"></script>
```

Library yang digunakan adalah **`@mediapipe/face_detection`** (Google MediaPipe), bukan library pengenalan wajah berbayar atau cloud-based. Seluruh inferensi model berjalan **lokal di browser pengguna** tanpa mengirimkan data wajah ke server eksternal.

Kode sumber: `resources/views/absensi/absensi/absen-masuk.blade.php` (baris 310) dan `absen-pulang.blade.php` (baris 307).

---

### B. Inisialisasi dan Parameter Model

```javascript
// Inisialisasi objek FaceDetection
faceDetector = new FaceDetection({
    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/${file}`,
});

// Konfigurasi parameter model
faceDetector.setOptions({
    model: 'short',               // Parameter 1: Jenis Model
    minDetectionConfidence: 0.6,  // Parameter 2: Ambang Batas Kepercayaan
});
```

Penjelasan setiap parameter:

| Parameter | Nilai | Penjelasan |
|---|---|---|
| `model` | `'short'` | Menggunakan model **short-range** yang dioptimalkan untuk mendeteksi wajah dalam jarak dekat (selfie/kamera depan). Alternatifnya adalah `'full'` yang lebih akurat namun lebih lambat. Dipilih `'short'` karena konteks absensi menggunakan kamera depan (selfie). |
| `minDetectionConfidence` | `0.6` | **Ambang batas kepercayaan minimum** = **0.6 (60%)**. Artinya, MediaPipe hanya akan melaporkan wajah sebagai "terdeteksi" jika skor kepercayaan model >= 60%. Nilai ini merupakan keseimbangan antara sensitivitas (tidak terlalu ketat) dan keandalan (tidak terlalu longgar). |
| `locateFile` | URL CDN | Mengarahkan MediaPipe untuk memuat file model WASM (WebAssembly) dan TFLite dari CDN yang sama. |

---

### C. Mekanisme Validasi Wajah (Alur Lengkap)

Validasi wajah terdiri dari **3 lapisan** yang berjalan secara berurutan:

#### Lapisan 1 — Validasi Kesiapan Detektor

```javascript
async function initFaceDetector() {
    // Cek apakah library berhasil dimuat
    if (typeof FaceDetection === 'undefined') {
        setFaceStatus('Library FaceDetection tidak termuat (cek koneksi/CDN).', false);
        faceDetectorReady = false;
        return;
    }
    // ...
    faceDetectorReady = true;
    setFaceStatus('Face detector siap. Ambil foto dengan wajah terlihat jelas.', true);
}
```

Sebelum pengguna dapat mengambil foto, sistem memvalidasi bahwa library MediaPipe berhasil dimuat. Flag `faceDetectorReady` digunakan sebagai penjaga (guard).

#### Lapisan 2 — Validasi Wajah saat Capture (Client-Side)

```javascript
async function detectFaceFromCanvas(canvas) {
    return new Promise(async (resolve) => {
        // Override onResults untuk mengambil hasil secara async
        faceDetector.onResults((results) => {
            // Cek apakah ada deteksi (minConfidence sudah diterapkan di setOptions)
            const hasFace = !!(results && results.detections && results.detections.length > 0);

            if (hasFace) {
                setFaceStatus('Wajah terdeteksi.', true);
            } else {
                setFaceStatus('Wajah tidak terdeteksi. Dekatkan wajah & perbaiki pencahayaan.', false);
            }

            resolve(hasFace);
        });

        // Kirim frame video ke MediaPipe untuk diproses
        await faceDetector.send({ image: canvas });
    });
}
```

Alur kerja:
1. Saat pengguna menekan tombol ambil foto, frame video kamera di-capture ke dalam elemen `<canvas>`.
2. Canvas (dalam format mirror/selfie) dikirimkan ke `faceDetector.send()`.
3. MediaPipe memproses gambar dan mengembalikan hasil (`results.detections`) melalui callback `onResults`.
4. Jika `results.detections.length > 0` → wajah terdeteksi → proses lanjut.
5. Jika `results.detections.length === 0` → wajah tidak terdeteksi → foto **ditolak** dengan pesan peringatan.

#### Lapisan 3 — Blokir Submit jika Wajah Tidak Terdeteksi

```javascript
async function ambilFoto(tipe) {
    // ...
    
    // Deteksi wajah terlebih dahulu
    setFaceStatus('Mengecek wajah...', null);
    const wajahTerdeteksi = await detectFaceFromCanvas(canvas);

    // Jika wajah tidak terdeteksi, HENTIKAN proses
    if (!wajahTerdeteksi) {
        window.showFormalAlert(
            'Wajah tidak terdeteksi. Pastikan wajah terlihat jelas, dekatkan kamera, dan pencahayaan cukup.',
            'warning',
            'Wajah Tidak Terdeteksi'
        );
        return; // <- Proses dihentikan di sini
    }

    // Hanya jika wajah terdeteksi -> foto dikonversi ke base64 -> dikirim ke server
    const imageData = canvas.toDataURL('image/jpeg', 0.8);
    // ... lanjut ke submit server
}
```

---

### D. Validasi Tambahan di Server (Server-Side)

Setelah foto lolos validasi wajah di sisi klien, data dikirim ke server (`AttendanceController@store`) dan divalidasi kembali:

```php
// Validasi input dari request
$request->validate([
    'attendance_type' => 'required|in:masuk,pulang',
    'photo'           => 'required|string',   // Base64 foto wajib ada
    'latitude'        => 'nullable|numeric',
    'longitude'       => 'nullable|numeric',
    'address'         => 'nullable|string',
]);

// Validasi format base64 gambar
if ($imageBinary === false) {
    return response()->json([
        'success' => false,
        'message' => 'Invalid image data'
    ], 400);
}
```

---

### E. Validasi GPS (Geofencing) sebagai Pengaman Tambahan

Sebagai lapisan keamanan tambahan selain deteksi wajah, sistem juga memvalidasi lokasi pengguna menggunakan **Haversine Formula**:

```php
private function haversineDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6371000; // meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}
```

**Parameter GPS:**

| Parameter | Nilai | Keterangan |
|---|---|---|
| Koordinat Kantor | `-6.058908, 106.653040` | Titik pusat area yang diizinkan |
| Radius Maksimum | `100 meter` | Absensi ditolak jika jarak > 100m dari kantor |
| Akurasi GPS minimum | `100 meter` | Reading GPS dibuang jika akurasi lebih buruk dari ini |
| Jumlah GPS Readings | `8 kali` | Rata-rata dari 8 pembacaan untuk mengurangi noise |
| Interval Readings | `2000ms` | Jarak waktu antar pembacaan GPS |

---

### F. Rangkuman Alur Validasi Lengkap

```
Pengguna tekan "Ambil Foto"
        |
        v
[1] faceDetectorReady? --Tidak--> Tampilkan peringatan, hentikan
        | Ya
        v
[2] Capture frame video ke Canvas
        |
        v
[3] MediaPipe.send(canvas)
    model: 'short', minDetectionConfidence: 0.6
        |
        v
[4] hasFace = (detections.length > 0)?
    --Tidak--> Alert "Wajah tidak terdeteksi", hentikan
        | Ya
        v
[5] Canvas -> Base64 JPEG (kualitas 80%)
        |
        v
[6] POST ke /absensi/simpan (server)
        |
        v
[7] Server validasi input + cek sesi absensi (stateful)
        |
        v
[8] Validasi radius GPS (Haversine, maks 100m)
        |
        v
[9] Simpan ke tabel attendances + simpan file foto
        |
        v
[10] Response sukses -> Update UI
```

---

## Revisi 3: Detail Pengujian — Jumlah Pengguna, Data Uji, dan Skenario Pengujian

### Jawaban / Klarifikasi

Berikut adalah rincian pengujian yang dilakukan terhadap sistem HR Management ERP ini, meliputi jumlah pengguna uji, data uji yang disiapkan, serta skenario pengujian yang dijalankan untuk setiap modul.

---

### A. Pengguna Uji (Test Users)

Pengujian dilakukan menggunakan **3 akun pengguna** yang merepresentasikan seluruh role dalam sistem:

| No. | Nama Akun | Role | Keterangan |
|---|---|---|---|
| 1 | `superadmin` | `superadmin` | Akun administrator utama, memiliki akses penuh ke seluruh fitur |
| 2 | `paranditha` | `pic` | Akun Person In Charge (PIC/Manajer), akses ke monitoring dan approval |
| 3 | `karyawan` | `karyawan` | Akun karyawan biasa, akses hanya ke absensi dan pengajuan izin/cuti |

> Catatan: Setelah setiap sesi pengujian, 11 record absensi uji dihapus dari database untuk memastikan kondisi bersih sebelum pengujian berikutnya (tercatat di `CATATAN-PEKERJAAN-2026-06-22.md`).

---

### B. Data Uji yang Disiapkan

Data uji yang ditambahkan ke database untuk keperluan pengujian:

| Jenis Data | Jumlah | Keterangan |
|---|---|---|
| Data karyawan dummy | 10 data | Ditambahkan untuk menguji fitur pagination (threshold = 10 data/halaman) |
| Item inventory | 5 item | Data barang untuk menguji fitur stock opname dan transfer |
| Record stock opname | 14 record | Ditambahkan untuk menguji pagination laporan opname |
| Record transfer stok | 13 record | Ditambahkan untuk menguji pagination laporan transfer |
| Record absensi uji | 11 record | Digunakan saat pengujian, dihapus setelah selesai (lingkungan bersih) |

---

### C. Skenario Pengujian

#### Skenario 1 — Pengujian Autentikasi & Keamanan Login

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 1.1 | Login dengan kredensial valid sebagai `karyawan` | Email & password benar | Redirect ke dashboard absensi karyawan | Berhasil |
| 1.2 | Login dengan kredensial valid sebagai `pic` | Email & password benar | Redirect ke halaman selection dashboard PIC | Berhasil |
| 1.3 | Login dengan kredensial valid sebagai `superadmin` | Email & password benar | Redirect ke dashboard superadmin | Berhasil |
| 1.4 | Login dengan password salah (5 kali berturut-turut) | Password tidak valid | Akun dikunci 60 detik, pesan error tampil | Berhasil |
| 1.5 | Akses halaman registrasi publik | Kunjungi `/register` | Redirect ke login + pesan "Pendaftaran hanya melalui HRD" | Berhasil |
| 1.6 | Login kemudian logout | Klik tombol Logout | Sesi dihapus, redirect ke login | Berhasil |

---

#### Skenario 2 — Pengujian Absensi Masuk dengan Face Detection

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 2.1 | Absen masuk dengan wajah terlihat jelas di kamera | Foto selfie normal | Wajah terdeteksi (confidence >= 0.6), foto disimpan, absensi tercatat | Berhasil |
| 2.2 | Ambil foto tanpa wajah (kamera mengarah ke objek lain) | Tidak ada wajah di frame | Alert "Wajah tidak terdeteksi", absensi tidak tersimpan | Berhasil |
| 2.3 | Absen masuk saat sudah absen masuk hari ini | Double klik absen masuk | Server menolak: "Anda sudah melakukan absensi hari ini" | Berhasil |
| 2.4 | Absen masuk di luar radius kantor (> 100 meter) | Koordinat GPS di luar radius | Server menolak: "Anda berada Xm dari kantor. Radius maksimal 100m." | Berhasil |
| 2.5 | Absen masuk, ganti akun, coba absen pulang langsung | Akun berbeda tanpa absen masuk | Server menolak: "Anda belum absen masuk, jadi tidak bisa absen pulang." | Berhasil |

---

#### Skenario 3 — Pengujian Absensi Pulang

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 3.1 | Absen pulang setelah absen masuk pada hari yang sama | Foto selfie valid | Absensi pulang tersimpan, sesi kerja ditutup | Berhasil |
| 3.2 | Absen pulang tanpa absen masuk terlebih dahulu | Langsung buka halaman pulang | Server menolak: "Anda belum absen masuk" | Berhasil |
| 3.3 | Absen pulang dua kali dalam satu sesi | Ulangi absen pulang | Server menolak: "Anda sudah absen pulang untuk sesi kerja Anda" | Berhasil |
| 3.4 | Cek timer jam kerja berjalan setelah absen masuk | Login ulang di tab baru | Timer jam kerja tetap berjalan (diambil dari server, bukan browser) | Berhasil |

---

#### Skenario 4 — Pengujian Manajemen Karyawan (Superadmin)

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 4.1 | Tambah karyawan baru dengan data valid | Semua field diisi benar | Data tersimpan, muncul di daftar karyawan | Berhasil |
| 4.2 | Tambah karyawan dengan password lemah | Password kurang dari 8 karakter / tanpa simbol | Validasi gagal, pesan error tampil | Berhasil |
| 4.3 | Edit data karyawan | Ubah nama/jabatan/departemen | Perubahan tersimpan ke database | Berhasil |
| 4.4 | Hapus data karyawan | Klik hapus + konfirmasi | Data terhapus dari daftar | Berhasil |
| 4.5 | Lihat detail karyawan (modal popup) | Klik tombol detail | Modal muncul dengan data lengkap karyawan | Berhasil |
| 4.6 | Pagination daftar karyawan (> 10 data) | Navigasi ke halaman 2 | Halaman 2 tampil, nomor urut berlanjut dari 11 | Berhasil |

---

#### Skenario 5 — Pengujian Pengajuan & Persetujuan Izin/Cuti

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 5.1 | Karyawan ajukan izin 1 hari | Isi form pengajuan izin | Pengajuan tersimpan dengan status `pending` | Berhasil |
| 5.2 | Karyawan ajukan cuti beberapa hari | Isi form pengajuan cuti + upload dokumen | Pengajuan tersimpan dengan status `pending` | Berhasil |
| 5.3 | Superadmin setujui pengajuan | Klik "Setujui" di halaman approval | Status berubah ke `approved`, badge tampil hijau | Berhasil |
| 5.4 | Superadmin tolak pengajuan | Klik "Tolak" di halaman approval | Status berubah ke `rejected`, badge tampil merah | Berhasil |
| 5.5 | Tombol aksi tidak tampil setelah diproses | Lihat data yang sudah approved/rejected | Tombol "Setujui/Tolak" tidak tampil, hanya teks "Selesai" | Berhasil |

---

#### Skenario 6 — Pengujian Modul Inventaris

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 6.1 | Tambah barang baru ke inventaris | Isi form tambah barang | Data tersimpan, tampil di daftar inventaris | Berhasil |
| 6.2 | Input stock opname (selisih stok) | Isi form opname dengan jumlah fisik | Selisih dihitung otomatis, tersimpan ke tabel stock_opnames | Berhasil |
| 6.3 | Transfer stok antar gudang | Isi form transfer stok | Record transfer tersimpan, badge status "Pending" tampil | Berhasil |
| 6.4 | Ekspor laporan ke Excel | Klik tombol Export | File Excel terunduh berisi data laporan | Berhasil |
| 6.5 | Pagination laporan opname (> 10 record) | Navigasi halaman | Pagination berfungsi, filter ikut terbawa antar halaman | Berhasil |

---

#### Skenario 7 — Pengujian Keamanan Anti Brute-Force

| No. | Skenario | Input | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 7.1 | Login gagal 5 kali berturut-turut | Email benar, password salah berulang | Akun dikunci 60 detik, muncul pesan "Terlalu banyak percobaan login" | Berhasil |
| 7.2 | Request reset password berulang (> 6x/menit) | Spam klik "Kirim Link Reset" | Throttle aktif, error 429 Too Many Requests | Berhasil |
| 7.3 | Login berhasil setelah sebelumnya gagal | Input kredensial benar | Counter percobaan gagal direset, login sukses | Berhasil |

---

#### Skenario 8 — Pengujian Lintas Perangkat (Responsivitas)

| No. | Skenario | Perangkat | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 8.1 | Buka dashboard di layar laptop | Desktop (> 992px) | Sidebar tampil, layout dua kolom | Berhasil |
| 8.2 | Buka halaman absensi di smartphone | Mobile (< 768px) | Kamera, peta, dan tombol tampil rapi dalam satu kolom | Berhasil |
| 8.3 | Tabel data karyawan di mobile | Smartphone | Tabel dapat di-scroll horizontal (table-responsive) | Berhasil |
| 8.4 | Sidebar dapat dibuka/tutup di mobile | Tombol hamburger | Sidebar muncul/hilang dengan animasi lancar | Berhasil |

---

### D. Ringkasan Hasil Pengujian

| Modul | Total Skenario | Berhasil | Catatan |
|---|---|---|---|
| Autentikasi & Keamanan | 6 | 6 | Semua skenario lolos |
| Absensi Masuk (Face Detection + GPS) | 5 | 5 | Validasi wajah & radius berfungsi |
| Absensi Pulang | 4 | 4 | Logika stateful per user berfungsi |
| Manajemen Karyawan | 6 | 6 | CRUD dan pagination berfungsi |
| Izin & Cuti | 5 | 5 | Workflow pengajuan dan approval berfungsi |
| Inventaris | 5 | 5 | Semua fitur inventaris berfungsi |
| Anti Brute-Force | 3 | 3 | Rate limiting berfungsi di client dan server |
| Responsivitas Mobile | 4 | 4 | Tampilan adaptif di semua ukuran layar |
| **Total** | **38** | **38** | **100% skenario pengujian berhasil** |

---

## Penutup

Ketiga poin revisi dari reviewer jurnal telah dijawab secara detail dan berbasis pada implementasi kode nyata dalam sistem:

1. **Metodologi** — Waterfall + Prototype, 5 fase (Analisis, Desain, Implementasi, Pengujian, Pemeliharaan), dengan arsitektur MVC Laravel.
2. **MediaPipe Face Detection** — Model `short`, `minDetectionConfidence: 0.6`, validasi berlapis 3 tahap (client-guard, MediaPipe detection, server validation), didukung validasi GPS Haversine dengan radius 100 meter.
3. **Pengujian** — 3 pengguna uji (superadmin, pic, karyawan), data uji terstruktur (10 karyawan dummy, 14 opname, 13 transfer), 38 skenario uji di 8 modul, dengan hasil 100% berhasil.
