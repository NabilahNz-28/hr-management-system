# 📓 JURNAL PROJECT — HR Management System

> **Tanggal dibuat:** 29 Juni 2026  
> **Framework:** Laravel 10 (PHP) + Blade Template + JavaScript (Frontend)  
> **Database:** MySQL (`hr-management`) via XAMPP  
> **Server Lokal:** Apache (XAMPP) — `http://localhost/hr-management-system`

---

## 📌 1. GAMBARAN UMUM PROJECT

Project ini adalah **Sistem Manajemen HR (Human Resource)** berbasis web yang mencakup:

| No | Modul | Deskripsi |
|----|-------|-----------|
| 1 | **Autentikasi** | Login, Logout, Lupa Password, Reset Password (dengan rate-limiting) |
| 2 | **Absensi (Face Recognition)** | Absen masuk & pulang menggunakan kamera + deteksi wajah + GPS |
| 3 | **Pengajuan Izin & Cuti** | Karyawan bisa mengajukan izin/cuti, dilengkapi upload dokumen |
| 4 | **Approval Izin/Cuti** | Superadmin menyetujui/menolak pengajuan karyawan |
| 5 | **Monitoring & Laporan Absensi** | Rekap harian, bulanan, riwayat keterlambatan |
| 6 | **Manajemen Karyawan** | CRUD data karyawan oleh Superadmin |
| 7 | **Inventory (Gudang)** | Stock Opname, Tambah Barang, Transfer Stock, Laporan |
| 8 | **Profile** | Edit profil & ganti password per user |

### Tiga Role User:
- **`superadmin`** — Akses penuh: kelola karyawan, approval izin/cuti, lihat semua data, inventaris
- **`pic`** — Penanggung jawab inventory: opname, transfer, laporan gudang + absensi
- **`karyawan`** — Absensi, pengajuan izin/cuti, lihat riwayat sendiri

---

## 📌 2. METODE & LOGIC YANG DIGUNAKAN

### 2.1 Arsitektur — MVC (Model-View-Controller)

Project ini mengikuti pola **MVC** bawaan Laravel:

```
app/
├── Models/           → Representasi tabel database (Eloquent ORM)
│   ├── User.php
│   ├── Attendance.php
│   ├── Leave.php
│   ├── LeaveType.php
│   ├── Inventory.php
│   ├── StockOpname.php
│   └── TransferStock.php
├── Http/
│   ├── Controllers/  → Logic bisnis & handler request
│   │   ├── AuthController.php
│   │   ├── AttendanceController.php
│   │   ├── DashboardController.php
│   │   ├── LeaveController.php
│   │   ├── SuperadminController.php
│   │   ├── InventoryController.php
│   │   ├── AbsensiReportController.php
│   │   ├── ProfileController.php
│   │   └── ...
│   └── Middleware/    → Filter request (auth, guest, throttle)
resources/
└── views/            → Blade templates (frontend)
    ├── absensi/
    ├── auth/
    ├── dashboard/
    ├── inventories/
    ├── superadmin/
    └── layouts/
routes/
└── web.php           → Definisi semua route/URL
```

### 2.2 Logic Autentikasi

**File:** `AuthController.php`

- **Login** menggunakan `Auth::attempt()` dari Laravel
- **Rate Limiting** (brute-force protection):
  - Maksimal **5 percobaan gagal** per kombinasi email + IP
  - Setelah limit → dikunci selama **60 detik**
  - Key throttle: `email|ip_address` (unik per user per perangkat)
  - Menggunakan `Illuminate\Support\Facades\RateLimiter`
- **Registrasi dinonaktifkan** — akun hanya bisa dibuat oleh Superadmin
- **Role-based redirect** setelah login:
  ```php
  'superadmin' → dashboard.superadmin
  'pic'        → dashboard.selection
  'karyawan'   → dashboard.absensi
  ```
- **Lupa Password** → kirim email reset link via `Password::sendResetLink()`
- **Reset Password** → validasi token + password strength rules:
  - Min 8 karakter, huruf besar+kecil, angka, simbol

### 2.3 Logic Absensi

**File:** `AttendanceController.php`

Absensi menggunakan **sistem sesi kerja stateful**:

```
[Tidak Ada Record] → Absen Masuk → [Sedang Bekerja] → Absen Pulang → [Selesai]
```

- Untuk menentukan status, sistem mengecek **record TERAKHIR hari ini** dari user:
  ```php
  $lastToday = Attendance::where('user_id', $user->id)
      ->whereDate('attendance_time', now()->toDateString())
      ->orderByDesc('attendance_time')
      ->orderByDesc('id')
      ->first();
  
  $sedangBekerja = $lastToday && $lastToday->attendance_type === 'masuk';
  ```
- **Validasi:**
  - Tidak bisa absen masuk kalau sudah dalam sesi kerja (cegah double masuk)
  - Tidak bisa absen pulang kalau belum absen masuk
- **Foto** dikirim sebagai Base64 dari frontend → di-decode → disimpan sebagai file `.jpg` di `public/photos/`
- **GPS** (latitude, longitude) + alamat dikirim bersamaan dari browser

### 2.4 Logic Izin & Cuti

**File:** `LeaveController.php`

- **Izin** vs **Cuti** dibedakan berdasarkan field `jenis` (`izin` / `cuti`)
- Jenis detail diambil dari tabel `leave_types`:
  - **Izin** (max_days = 0): sakit, urusan keluarga, urusan pribadi, lainnya
  - **Cuti** (max_days > 0): tahunan, melahirkan, besar, sakit, penting
- Submit disimpan dengan status **`pending`** → butuh approval Superadmin
- Upload dokumen (surat dokter, dll) disimpan di `storage/lampiran_izin_cuti/`
- Menggunakan **DB Transaction** (`DB::beginTransaction()`) untuk konsistensi data

### 2.5 Logic Inventory

**File:** `InventoryController.php`

- **Otorisasi:** Hanya role `pic` yang bisa akses (cek di helper `authorizeOnlyPic()`)
- **Stock Opname:** Input stok fisik terbaru → otomatis hitung selisih dengan stok sebelumnya
- **Transfer Stock:** Pindahkan barang antar gudang → stok otomatis dikurangi (`decrement`)
- **Validasi stok:** Transfer ditolak jika stok tidak mencukupi

### 2.6 Logic Statistik Dashboard

**File:** `DashboardController.php`

Setiap role mendapat dashboard berbeda:

**Dashboard Karyawan (Absensi):**
- Hitung kehadiran bulan ini: groupBy tanggal dari record `masuk`
- Terlambat = absen masuk pertama hari itu setelah pukul 08:00
- Izin & Cuti = dari tabel `leaves` yang status `approved` dan beririsan dengan bulan ini
- Libur = hitung hari Sabtu & Minggu dalam bulan

**Dashboard PIC:**
- Statistik inventory: total barang, total opname bulan ini, total transfer bulan ini
- Aktivitas terbaru (gabungan opname + transfer)

**Dashboard Superadmin:**
- Total karyawan (exclude superadmin)
- Jumlah hadir hari ini (distinct user_id yang masuk hari ini)
- Jumlah libur/cuti hari ini (leaves yang sedang aktif)

---

## 📌 3. CARA GET DATABASE (Akses Data dari DB)

### 3.1 Konfigurasi Database

**File:** `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr-management
DB_USERNAME=root
DB_PASSWORD=
```

Database berjalan di **MySQL** melalui XAMPP, nama database: `hr-management`.

### 3.2 Metode Akses: Eloquent ORM

Semua akses database menggunakan **Eloquent ORM** (bukan raw SQL). Eloquent memetakan tabel ke class Model:

| Model | Tabel | Deskripsi |
|-------|-------|-----------|
| `User` | `users` | Data karyawan & akun login |
| `Attendance` | `attendances` | Record absensi masuk/pulang |
| `Leave` | `leaves` | Pengajuan izin & cuti |
| `LeaveType` | `leave_types` | Master jenis izin/cuti |
| `Inventory` | `inventories` | Data barang gudang |
| `StockOpname` | `stock_opnames` | Riwayat stock opname |
| `TransferStock` | `transfer_stocks` | Riwayat transfer barang |

### 3.3 Contoh-Contoh Query yang Dipakai

**a) Ambil absensi user hari ini:**
```php
Attendance::where('user_id', $userId)
    ->whereDate('attendance_time', now()->toDateString())
    ->orderByDesc('attendance_time')
    ->first();
```

**b) Hitung kehadiran bulanan (group per hari):**
```php
Attendance::where('user_id', $userId)
    ->where('attendance_type', 'masuk')
    ->whereBetween('attendance_time', [$awal, $akhir])
    ->get()
    ->groupBy(fn ($a) => Carbon::parse($a->attendance_time)->toDateString());
```

**c) Ambil izin/cuti yang approved dan beririsan bulan ini:**
```php
Leave::where('karyawan_id', $userId)
    ->where('status', 'approved')
    ->whereDate('start_date', '<=', $akhir)
    ->where(function ($q) use ($awal) {
        $q->whereDate('end_date', '>=', $awal)
          ->orWhere(function ($q2) use ($awal) {
              $q2->whereNull('end_date')
                 ->whereDate('start_date', '>=', $awal);
          });
    })
    ->get();
```

**d) Simpan data absensi baru:**
```php
Attendance::create([
    'user_id'         => $user->id,
    'employee_name'   => $user->name,
    'attendance_type' => $request->attendance_type,
    'photo'           => $fileName,
    'latitude'        => $request->latitude,
    'longitude'       => $request->longitude,
    'address'         => $request->address,
    'attendance_time' => now(),
]);
```

**e) Relasi antar model:**
```php
// User memiliki banyak Attendance
$this->hasMany(Attendance::class, 'user_id');

// Attendance milik satu User
$this->belongsTo(User::class, 'user_id');

// User memiliki banyak Leave
$this->hasMany(Leave::class, 'karyawan_id');
```

### 3.4 Migrasi (Struktur Tabel)

Migrasi mendefinisikan struktur tabel. Contoh tabel `attendances`:

```php
Schema::create('attendances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('employee_name');
    $table->enum('attendance_type', ['masuk', 'pulang']);
    $table->string('photo')->nullable();
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->text('address')->nullable();
    $table->dateTime('attendance_time');
    $table->timestamp('created_at')->useCurrent();
});
```

---

## 📌 4. CARA GET FACE RECOGNITION UNTUK ABSENSI

### 4.1 Library yang Digunakan

Face Recognition menggunakan **MediaPipe Face Detection** dari Google, dimuat via CDN:

```html
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js"></script>
```

> **Catatan Penting:** Ini adalah **Face Detection** (mendeteksi ada/tidaknya wajah), bukan **Face Recognition** (mengenali siapa orangnya). Sistem memastikan bahwa orang yang absen memang menunjukkan wajah asli di depan kamera, bukan foto kosong.

### 4.2 Inisialisasi Face Detector

```javascript
faceDetector = new FaceDetection({
    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/${file}`,
});

faceDetector.setOptions({
    model: 'short',              // model ringan untuk kecepatan
    minDetectionConfidence: 0.6, // confidence minimal 60%
});
```

- **Model `short`** dipilih karena lebih cepat (cocok untuk real-time)
- **Confidence 0.6** berarti sistem minimal 60% yakin bahwa yang terdeteksi adalah wajah

### 4.3 Proses Deteksi Wajah

Deteksi dilakukan saat user klik tombol **"Ambil Foto"**:

```javascript
async function detectFaceFromCanvas(canvas) {
    return new Promise(async (resolve) => {
        faceDetector.onResults((results) => {
            const hasFace = !!(results && results.detections && results.detections.length > 0);
            resolve(hasFace);
        });
        await faceDetector.send({ image: canvas });
    });
}
```

**Alurnya:**
1. Canvas menangkap frame dari video webcam
2. Canvas dikirim ke MediaPipe untuk dianalisis
3. MediaPipe mengembalikan `results.detections` (array wajah terdeteksi)
4. Jika `detections.length > 0` → wajah terdeteksi → foto diterima
5. Jika kosong → tolak, minta user perbaiki posisi/pencahayaan

### 4.4 Proses Pengambilan Foto (Capture)

```javascript
async function ambilFoto(tipe) {
    // 1. Buat canvas dari frame video webcam
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');

    // 2. Mirror effect (selfie)
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0);

    // 3. Deteksi wajah dengan MediaPipe
    const wajahTerdeteksi = await detectFaceFromCanvas(canvas);
    if (!wajahTerdeteksi) {
        alert('Wajah tidak terdeteksi!');
        return;
    }

    // 4. Tambah watermark (tanggal + waktu)
    ctx.fillText(`Absensi Masuk - ${tanggal} ${waktu}`, 10, canvas.height - 20);

    // 5. Tampilkan preview & mulai countdown timer (30 detik)
    document.getElementById('capturedPhoto').src = canvas.toDataURL('image/jpeg', 0.9);
    mulaiTimer(tipe);
}
```

---

## 📌 5. ALUR LENGKAP SISTEM ABSENSI (End-to-End Flow)

### 5.1 Flowchart Absensi Masuk

```
┌──────────────────────────────────────────────────────────────┐
│                    USER BUKA HALAMAN                         │
│                   /absensi/masuk                             │
└──────────────────────┬───────────────────────────────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │  1. INISIALISASI OTOMATIS    │
        │  ─────────────────────────── │
        │  • Akses kamera (getUserMedia)│
        │  • Load peta Leaflet (OSM)   │
        │  • Init MediaPipe Face Det.  │
        │  • Deteksi GPS (geolocation) │
        │  • Cek status absensi hari   │
        │    ini via API /absensi/cek  │
        └──────────────┬───────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │  2. CEK STATUS DI SERVER     │
        │  ─────────────────────────── │
        │  GET /absensi/cek-hari-ini   │
        │                              │
        │  Response:                   │
        │  • has_masuk: true/false     │
        │  • has_pulang: true/false    │
        │  • masuk_iso: timestamp      │
        └──────────────┬───────────────┘
                       │
            ┌──────────┴──────────┐
            │                     │
     Sudah masuk &           Belum absen
     belum pulang            hari ini
            │                     │
            ▼                     ▼
    ┌───────────────┐   ┌───────────────────┐
    │ Tampilkan jam │   │ 3. USER KLIK      │
    │ kerja berjalan│   │    TOMBOL CAPTURE  │
    │ (timer live)  │   │    (Ambil Foto)    │
    └───────────────┘   └─────────┬─────────┘
                                  │
                                  ▼
                    ┌───────────────────────────┐
                    │ 4. FACE DETECTION         │
                    │ ───────────────────────── │
                    │ Canvas capture → MediaPipe│
                    │ Cek: Ada wajah?           │
                    └─────────┬─────────────────┘
                              │
                   ┌──────────┴──────────┐
                   │                     │
              Wajah TIDAK            Wajah
              terdeteksi            terdeteksi
                   │                     │
                   ▼                     ▼
           ┌──────────────┐   ┌───────────────────┐
           │ Alert: wajah │   │ 5. TAMPILKAN      │
           │ tidak valid  │   │    PREVIEW FOTO    │
           │ (tolak)      │   │  + Watermark waktu │
           └──────────────┘   │  + Timer 30 detik  │
                              └─────────┬─────────┘
                                        │
                                        ▼
                          ┌───────────────────────────┐
                          │ 6. USER KLIK SUBMIT       │
                          │ ───────────────────────── │
                          │ Konfirmasi dialog:        │
                          │ • Tanggal & Waktu         │
                          │ • Koordinat GPS           │
                          │ • Alamat                  │
                          └─────────┬─────────────────┘
                                    │
                                    ▼
                      ┌───────────────────────────┐
                      │ 7. KIRIM KE SERVER        │
                      │ ───────────────────────── │
                      │ POST /absensi/simpan      │
                      │ Body (JSON):              │
                      │ • attendance_type: 'masuk' │
                      │ • photo: base64 string    │
                      │ • latitude, longitude     │
                      │ • address                 │
                      └─────────┬─────────────────┘
                                │
                                ▼
                  ┌───────────────────────────────┐
                  │ 8. SERVER MEMPROSES           │
                  │ (AttendanceController@store)  │
                  │ ─────────────────────────────│
                  │ a. Validasi input             │
                  │ b. Cek status sesi kerja:     │
                  │    - Sudah masuk? → tolak     │
                  │    - Belum masuk? → lanjut    │
                  │ c. Decode base64 → .jpg file  │
                  │ d. Simpan file ke public/photos│
                  │ e. INSERT ke tabel attendances│
                  │ f. Return JSON success        │
                  └─────────┬─────────────────────┘
                            │
                            ▼
                  ┌───────────────────────────┐
                  │ 9. FRONTEND MENERIMA      │
                  │ ───────────────────────── │
                  │ • Simpan ke sessionStorage │
                  │ • Tampilkan jam kerja      │
                  │   berjalan (timer live)    │
                  │ • Notifikasi sukses ✅     │
                  └───────────────────────────┘
```

### 5.2 Flowchart Absensi Pulang

Alurnya **sama persis** dengan absensi masuk, bedanya:
- `attendance_type` = `'pulang'`
- Server mengecek apakah user **sudah absen masuk** (record terakhir = masuk)
- Jika belum masuk → tolak dengan pesan error

### 5.3 Alur GPS & Peta

```
Browser → navigator.geolocation.getCurrentPosition()
       → Dapat latitude + longitude + accuracy
       → Update peta Leaflet (marker posisi user)
       → Reverse Geocoding via Nominatim (OpenStreetMap)
       → Dapat alamat lengkap (jalan, kecamatan, kota)
       → Tampilkan di UI
       → Auto-refresh setiap 30 detik
```

### 5.4 Alur Pengajuan Izin/Cuti

```
Karyawan               Server                  Superadmin
────────               ──────                  ──────────
Isi form izin/cuti  →  Validasi input
                       Simpan ke DB
                       status = 'pending'
                                            ←  Lihat daftar pengajuan
                                                (/superadmin/approval)
                                                
                                                Klik Approve / Reject
                                            →  Update status di DB
                                                ('approved'/'rejected')
                                                
Terlihat di laporan ←  Status berubah
(sudah dihitung di
dashboard & rekap)
```

### 5.5 Alur Inventory

```
PIC                     Server                  Superadmin
───                     ──────                  ──────────
Tambah Barang Baru  →   INSERT ke inventories
                        
Input Stock Opname  →   Loop per produk:
                        - Cari inventory by nama
                        - Catat stok_sebelum
                        - Update stok_fisik
                        - INSERT ke stock_opnames
                        
Transfer Stock      →   Validasi stok cukup?
                        - Ya: decrement stok
                              INSERT ke transfer_stocks
                        - Tidak: tolak dengan error
                        
Lihat Laporan       →   Query dengan filter      Lihat Laporan
                        (tanggal, kategori,      (/superadmin/inventory)
                         status)                 (/superadmin/transfer)
```

---

## 📌 6. TEKNOLOGI & LIBRARY EKSTERNAL

| Teknologi | Kegunaan |
|-----------|----------|
| **Laravel 10** | Backend framework (PHP) |
| **Blade** | Template engine untuk frontend |
| **Eloquent ORM** | Akses database (tanpa raw SQL) |
| **MySQL** | Database utama |
| **MediaPipe Face Detection** | Deteksi wajah di browser (Google AI) |
| **Leaflet.js** | Peta interaktif (OpenStreetMap) |
| **Nominatim** | Reverse geocoding (koordinat → alamat) |
| **Geolocation API** | Ambil GPS dari browser |
| **getUserMedia API** | Akses kamera browser |
| **Laravel Sanctum** | Token-based API authentication |
| **Vite** | Build tool untuk CSS & JS assets |
| **Carbon** | Library manipulasi tanggal/waktu (PHP) |

---

## 📌 7. STRUKTUR DATABASE (ERD Ringkas)

```
┌─────────────┐       ┌──────────────────┐
│    users     │───┐   │   attendances     │
├─────────────┤   │   ├──────────────────┤
│ id          │   ├──→│ user_id (FK)     │
│ name        │   │   │ employee_name    │
│ email       │   │   │ attendance_type  │
│ password    │   │   │ photo            │
│ role        │   │   │ latitude         │
│ status      │   │   │ longitude        │
│ nik         │   │   │ address          │
│ departemen  │   │   │ attendance_time  │
│ jabatan     │   │   └──────────────────┘
│ no_hp       │   │
│ alamat      │   │   ┌──────────────────┐
│ tgl_bergabung│  ├──→│     leaves        │
│ foto_profile│   │   ├──────────────────┤
└─────────────┘   │   │ karyawan_id (FK) │
                  │   │ jenis            │
                  │   │ jenis_detail     │
                  │   │ start_date       │
                  │   │ end_date         │
                  │   │ keterangan       │
                  │   │ file_path        │
                  │   │ status           │
                  │   └──────────────────┘
                  │
                  │   ┌──────────────────┐
                  ├──→│  stock_opnames    │
                  │   ├──────────────────┤
                  │   │ inventory_id (FK)│
                  │   │ user_id (FK)     │
                  │   │ tanggal          │
                  │   │ stok_sebelum     │
                  │   │ stok_sesudah     │
                  │   │ selisih          │
                  │   │ catatan          │
                  │   └──────────────────┘
                  │
                  │   ┌──────────────────┐
                  └──→│ transfer_stocks   │
                      ├──────────────────┤
                      │ user_id (FK)     │
                      │ barang_id (FK)   │
                      │ tanggal          │
                      │ ke_gudang        │
                      │ jumlah           │
                      │ satuan           │
                      │ status           │
                      │ catatan          │
                      └──────────────────┘

┌──────────────────┐     ┌──────────────────┐
│  inventories     │────→│  stock_opnames    │
├──────────────────┤     │  transfer_stocks  │
│ nama_barang      │     └──────────────────┘
│ kategori         │
│ stok_fisik       │     ┌──────────────────┐
│ stok_carton      │     │   leave_types     │
│ catatan          │     ├──────────────────┤
└──────────────────┘     │ type_code        │
                         │ name             │
                         │ max_days         │
                         │ requires_document│
                         │ is_active        │
                         └──────────────────┘
```

---

## 📌 8. KEAMANAN YANG DITERAPKAN

| Aspek | Implementasi |
|-------|-------------|
| **CSRF Protection** | Token CSRF di setiap form POST (bawaan Laravel) |
| **Rate Limiting Login** | Max 5x gagal → kunci 60 detik (per email+IP) |
| **Password Hashing** | Menggunakan `Hash::make()` (bcrypt) |
| **Password Strength** | Min 8 char, huruf besar+kecil, angka, simbol |
| **Route Middleware** | `auth` untuk halaman yang butuh login, `guest` untuk halaman publik |
| **Role Authorization** | Cek role di controller (contoh: `authorizeOnlyPic()`) |
| **Session Regeneration** | `$request->session()->regenerate()` setelah login |
| **Session Invalidation** | `$request->session()->invalidate()` saat logout |
| **Input Validation** | Setiap request divalidasi (`$request->validate()`) |
| **Multi-User Guard** | SessionStorage dibersihkan jika user berganti (di frontend) |

---

## 📌 9. CATATAN TAMBAHAN

### Mode Uji Coba
- Saat ini GPS dalam **mode uji coba**: absensi bisa dilakukan dari lokasi mana pun
- Di produksi, akan ditambahkan validasi radius maksimal **100m dari kantor**
- Koordinat kantor: `lat: -6.058908, lng: 106.653040`

### Timer Foto
- Setelah foto diambil, ada **countdown 30 detik** untuk submit
- Jika tidak submit dalam 30 detik → foto expired, harus ambil ulang
- Ini untuk memastikan foto diambil secara real-time

### Foto Absensi
- Disimpan di `public/photos/` dengan format: `attendance_{timestamp}_{random}.jpg`
- Foto ditambahi **watermark** berisi tanggal, waktu, dan tipe absensi
- Base64 dari frontend → di-decode di server → disimpan sebagai file

---

> **Dibuat otomatis berdasarkan analisis source code project HR Management System.**
