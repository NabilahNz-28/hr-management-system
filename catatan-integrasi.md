# Catatan Integrasi — HR Management System

Dokumentasi pekerjaan menghubungkan view ke database (Laravel 10) dan temuan untuk pembelajaran.
Tanggal: 2026-06-12 • Branch: `nabil`

---

## 1. Ringkasan Pekerjaan Hari Ini

Fokus: memastikan setiap menu benar-benar **tersimpan/terbaca dari database**, bukan sekadar
tampilan statis/dummy. Banyak halaman ternyata hanya UI palsu (simulasi `localStorage`,
`action="#"`, atau data hardcoded).

### A. Superadmin
- **Approval Izin & Cuti** — sudah terhubung (`Leave::with('karyawan')`). Tombol Setujui/Tolak update `status`.
- **Data Karyawan** — sudah terhubung. Diperbaiki:
  - Badge role `pic` salah tampil "Karyawan" → tambah `case 'pic'`.
  - Kolom **Status** selalu "Nonaktif" → tabel `users` tidak punya kolom `status`.
    Ditambah migration `status` enum(`aktif`,`nonaktif`) default `aktif`, masuk `$fillable`,
    serta disimpan di `karyawanStore`/`karyawanUpdate`.
- **Insert Karyawan** — opsi Role bernilai `inventory` tidak lolos validasi (`in:karyawan,pic,superadmin`)
  → diubah jadi `pic`.
- **/superadmin/inventory** & **/superadmin/profile** — render dengan layout salah
  (`layouts.pic` / `layouts.absen`) → diganti `layouts.superadmin`.
- **Data Inventory & Transfer Stock (superadmin)** — controller hanya `return view()` tanpa data,
  form filter mengarah ke route PIC. Diperbaiki: `inventoryIndex`/`transferIndex` query seluruh PIC
  (`StockOpname`/`TransferStock` + relasi `inventory`/`barang`/`user`) dengan filter
  (tanggal, kategori/status), form filter diarahkan ke route superadmin, tambah kolom **Petugas**.

### B. Karyawan (Absensi)
- **Absensi Masuk & Pulang** — frontend hanya simulasi `localStorage` (`simpanKeDatabase`).
  Disambungkan POST ke `absensi.simpan`. Backend `AttendanceController@store` diperbaiki agar
  sesuai skema tabel (`attendance_type`, `latitude`, `longitude`, `address`, `attendance_time`,
  `user_id`, `employee_name`) — sebelumnya pakai kolom `time_in`/`date` yang tidak ada.
  `checkTodayAttendance` ikut diperbaiki. Ditambah meta CSRF di `layouts.absen`.
- **Rekap Harian / Bulanan / Laporan Absensi** — semula JS dummy. Dibuat `AbsensiReportController`
  (render server-side dari `attendances` + `leaves`), route diubah dari closure → controller.
- **Laporan Keterlambatan** — dummy → `AbsensiReportController@keterlambatan` (jam masuk > 08:00).
- **Pengajuan Izin & Cuti** — form tidak terhubung (`<form id>` tanpa action, nama field salah,
  form cuti tanpa `name`, dan `script.js` meng-`preventDefault` lalu alert palsu).
  Disambungkan POST ke `absensi.cuti.post`, nama field disesuaikan controller, handler JS palsu dihapus.
- **Riwayat Izin & Cuti** — `LaporanController` salah kolom (`employee_id` → `karyawan_id`),
  merujuk relasi/model tidak ada (`leaveType`, `documents`, `Permission`), view belum dibuat.
  Ditulis ulang + view baru `absensi/laporan/laporan-izin-cuti`. Ditambah menu di sidebar.
- **Profile Karyawan** — route `profile.update` & `profile.password.update` salah tempat
  (di dalam grup `inventory` → ter-prefix), serta nama route password tidak cocok.
  Dipindah ke level atas dan disamakan namanya.

### C. PIC (Inventory)
- **Dashboard PIC** — statistik & aktivitas kosong (`aktivitasList=[]`).
  `DashboardController@pic` query nyata: total barang, opname/transfer bulan ini, aktivitas terbaru.
- **Tambah Barang** — `action="#"`, nama field salah (`item_name` vs `nama_barang`),
  redirect salah nama route. Diperbaiki semua.
- **Input Opname** — tombol Simpan hanya `console.log`/alert palsu, input tanpa `name`,
  "Pilih Produk" teks bebas. Disambungkan POST nyata, dropdown produk dari DB, hidden input
  `produk[i][nama|jumlah]`, redirect diperbaiki.
- **Transfer Stock** — `action="#"` + redirect salah nama route. Diperbaiki.
- **Laporan Opname & Transfer** — form filter sudah ada tapi controller mengabaikan parameter.
  Ditambah `when()` untuk filter tanggal + kategori/status.

### D. Lintas-peran
- **Timezone** — aplikasi `UTC`, sedangkan WIB (UTC+7) → jam absensi tampil mundur 7 jam.
  Diubah `config/app.php` → `Asia/Jakarta`; baris lama dikoreksi +7 jam.
- **Bug CSS `.badge`** — di `pic.css` & `style.css`, `.badge` didefinisikan sebagai dot notifikasi
  (`position:absolute`) sehingga menimpa `.badge` Bootstrap → badge status melayang ke pojok kartu.
  Di-scope ulang jadi `.notification-btn .badge`. Tambah cache-buster CSS di layout superadmin.

---

## 2. Temuan & Pembelajaran (Pola Berulang)

1. **"Berhasil" palsu.** Banyak form menampilkan alert sukses tapi tidak menyimpan:
   - `<form action="#">` atau tanpa `action`/`method`.
   - JavaScript `e.preventDefault()` lalu hanya `alert()`/`localStorage`/`console.log`.
   - **Pelajaran:** selalu verifikasi ke DB (cek jumlah baris), jangan percaya notifikasi UI.

2. **Mismatch nama field form ↔ validasi controller.** Contoh: `item_name` vs `nama_barang`,
   `tanggal_izin` vs `start_date`. Validasi gagal diam-diam → redirect back tanpa pesan jelas.
   - **Pelajaran:** samakan `name=""` dengan key di `$request->validate()`; tampilkan `$errors`.

3. **Nama route redirect salah** (mis. `inventories.inventory.stock.opname` vs `inventory.stock-opname`).
   Insert berhasil tapi error saat redirect. **Pelajaran:** `php artisan route:list` untuk verifikasi nama.

4. **Route salah tempat dalam grup prefix.** Route profil ter-prefix `inventory.` karena ditaruh
   di dalam grup inventory. **Pelajaran:** perhatikan konteks `Route::prefix()->name()->group()`.

5. **View extend layout yang salah** → tampil dengan sidebar peran lain (terlihat seperti "pindah halaman").

6. **Mismatch nama kolom DB.** `employee_id` vs `karyawan_id`; `time_in`/`date` tidak ada di skema.
   **Pelajaran:** cocokkan dengan migration/`$fillable`, bukan asumsi.

7. **CSS global menimpa komponen.** `.badge` kustom (dot) menimpa `.badge` Bootstrap.
   **Pelajaran:** scope selector spesifik; jangan pakai nama kelas umum framework untuk hal lain.

8. **Timezone.** Default `UTC`. Untuk aplikasi lokal set `Asia/Jakarta` agar `now()` benar.

9. **Logika rentang tanggal (overlap).** `end_date` NULL (izin 1 hari) jika tidak ditangani akan
   ikut terhitung di semua bulan. Perlakukan NULL sebagai `= start_date`.

10. **Cache.** File JS/CSS statis di-cache browser. Gunakan cache-buster (`?v=filemtime`) dan
    `php artisan view:clear` / `route:clear` setelah ubah route/config.

11. **⚠️ Hati-hati hapus data saat uji.** Pernah `Model::query()->delete()` saat smoke-test sehingga
    ikut menghapus data riil. **Pelajaran:** saat verifikasi, buat data uji lalu hapus **per-id**,
    jangan truncate/`delete()` seluruh tabel.

---

## 3. Catatan Teknis / TODO

- **Aturan jam masuk** di-hardcode `08:00` (`AbsensiReportController::JAM_BATAS_MASUK`).
  Idealnya dari tabel settings.
- **Libur** dihitung hanya Sabtu/Minggu; hari libur nasional belum ada.
- **Kolom "Alasan" keterlambatan** dihapus dari laporan karena `attendances` tidak menyimpannya.
- **Laporan transfer**: kolom JUMLAH masih hardcode "pcs" walau satuan bisa berbeda — perlu dirapikan.
- **Lampiran izin/cuti** disimpan di disk `public` → jalankan `php artisan storage:link` sekali.
- **Foto absensi** wajib (validasi) — kamera harus aktif (akses via `localhost`/HTTPS).
