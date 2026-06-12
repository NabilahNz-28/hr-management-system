# Catatan Integrasi: Migrasi SPA ke Laravel Blade

Dokumen ini berisi catatan mengenai hal-hal yang sering terlewatkan (tertinggal) saat melakukan migrasi antarmuka dari format _Single Page Application_ (SPA) statis ke format _Multi-Page Application_ dinamis menggunakan Laravel Blade & Controller.

Baca panduan ini agar terhindar dari _error_ serupa saat mengembangkan fitur baru.

---

## 1. Routing Harus Menggunakan Controller (Jangan *Bypass*)
**Kasus:** Saat menekan tombol *sidebar*, halaman berhasil dimuat namun tidak ada data yang muncul (tabel kosong / _error undefined variable_).
**Penyebab:** *Route* di `routes/web.php` ditulis menggunakan Closure (`fn () => view(...)`). Cara ini akan langsung menampilkan tampilan (HTML) **tanpa mengeksekusi** logika *backend* untuk mengambil data dari *database*.
**Solusi:**
Selalu arahkan *route* ke *Controller* yang bersangkutan.
❌ **Salah:**
```php
Route::get('/stock-opname', fn () => view('inventories.inventory.stock-opname'));
```
✅ **Benar:**
```php
Route::get('/stock-opname', [InventoryController::class, 'stockOpname']);
```

## 2. Pindahkan Logika JavaScript ke Sintaks Blade
**Kasus:** Tabel data kosong karena *JavaScript* perender data hilang saat dipisah ke *file* berbeda.
**Penyebab:** Pada file HTML mentah, data dummy biasanya di-_render_ menggunakan Vanilla JavaScript (`document.getElementById().innerHTML = ...`). Saat beralih ke Laravel, file JS tersebut sering kali tertinggal di file lama.
**Solusi:**
Hapus logika render JS lama dan ganti dengan perulangan bawaan Laravel (`@forelse` atau `@foreach`) untuk menampilkan data asli dari *database*.
✅ **Contoh:**
```blade
<tbody id="stock-table-body">
    @forelse($barang as $item)
        <tr>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->stok_fisik }}</td>
        </tr>
    @empty
        <tr><td colspan="2">Data tidak tersedia</td></tr>
    @endforelse
</tbody>
```

## 3. Pastikan Path View di Controller Sesuai Struktur Folder Asli
**Kasus:** Muncul pesan `View [inventories.inventory.laporan-opname] not found.`
**Penyebab:** Terjadi *typo* (salah ketik) saat memanggil alamat _view_ di *Controller*.
**Solusi:** Selalu sesuaikan string nama *view* dengan struktur folder aktual di dalam folder `resources/views/`. Titik (`.`) mewakili garis miring (`/`).
❌ **Salah:** `view('inventories.inventory.laporan-opname')` (padahal file ada di dalam folder `laporan`)
✅ **Benar:** `view('inventories.laporan.laporan-opname')`

## 4. Pastikan Nama Kolom Sesuai dengan Struktur Database
**Kasus:** Muncul layar merah `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pic_id' in 'where clause'`.
**Penyebab:** *Query builder* Laravel mencari data menggunakan nama kolom yang salah / tidak pernah didefinisikan di tabel *database*.
**Solusi:** Buka kembali file *migration* Anda dan cek nama kolom pastinya.
Misalnya, daripada menggunakan `->where('pic_id', auth()->id())`, gunakan `->where('user_id', auth()->id())` karena kolom di *database* bernama `user_id`.

## 5. Tambahkan Pengaman untuk Data Kosong (Fallback)
**Kasus:** Muncul layar merah `Undefined variable $barang`.
**Penyebab:** Tampilan mencoba melooping variabel (`$barang`) yang belum pernah dikirimkan (dipassing) oleh *Controller*.
**Solusi:** Gunakan _null coalescing operator_ (`?? []`) saat menggunakan variabel di dalam perulangan Blade untuk mencegah _crash_ ketika data benar-benar kosong atau *Controller* belum sepenuhnya selesai dibuat.
✅ **Benar:** `@foreach($barang ?? [] as $item)`

---

## 6. Alur Lengkap Menghubungkan UI dengan Database (Contoh: Stock Opname)

Jika Anda membuat halaman baru dan ingin menampilkannya dengan data dari *database*, Anda **wajib** mengikuti urutan 3 langkah (Model/DB -> Controller -> Route -> View) berikut agar datanya benar-benar terhubung. 

Sebagai contoh, jika Anda ingin agar halaman **Stock Opname** (`stock-opname.blade.php`) bisa menampilkan data asli dari *database*:

### Langkah A: Siapkan Pengambilan Data di `Controller`
Buka `app/Http/Controllers/InventoryController.php`, pastikan Anda memiliki sebuah *method* yang memanggil Model (contoh `Inventory`) lalu mengirimkan data tersebut (`compact()`) ke *View*.

```php
// InventoryController.php
use App\Models\Inventory; // Pastikan model dipanggil di atas

public function stockOpname()
{
    // 1. Ambil data dari database (misal diurutkan berdasarkan nama)
    $barang = Inventory::orderBy('nama_barang')->get();

    // 2. Kirim data '$barang' ke view 'stock-opname'
    return view('inventories.inventory.stock-opname', compact('barang'));
}
```

### Langkah B: Hubungkan Route ke Controller
Buka `routes/web.php`. Jangan lagi menggunakan `fn () => view(...)`. Panggil *method* `stockOpname` yang baru saja kita buat di *Controller*.

```php
// routes/web.php
use App\Http\Controllers\InventoryController;

Route::get('/stock-opname', [InventoryController::class, 'stockOpname'])->name('inventory.stock-opname');
```

### Langkah C: Tampilkan Data di `View` (Blade)
Buka `resources/views/inventories/inventory/stock-opname.blade.php`. Tangkap variabel `$barang` yang dikirim dari *Controller* menggunakan `@foreach`.

```blade
<!-- stock-opname.blade.php -->
<tbody id="stock-table-body">
    {{-- Lakukan perulangan untuk setiap data barang dari database --}}
    @forelse($barang ?? [] as $item)
    <tr>
        <td>{{ $item->nama_barang }}</td>
        <td>{{ $item->kategori }}</td>
        <td>{{ $item->stok_fisik }} pcs</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">Belum ada barang di inventory.</td>
    </tr>
    @endforelse
</tbody>
```

**Kesimpulan:** 
Setiap kali Anda membuat menu baru di sidebar, **jangan langsung mengarahkan `Route` ke `View`** jika halamannya membutuhkan data. Pastikan selalu melewati **Controller** terlebih dahulu agar *Controller* bisa mengambil data dari **Database**, lalu data tersebut disuntikkan ke dalam **View**.

---

## 7. Tips Merapihkan Tampilan Mobile (Responsivitas)

Seringkali tampilan yang sudah rapi di laptop menjadi berantakan saat dibuka di *smartphone*. Berikut adalah tips dan kode yang bisa diterapkan untuk menjaga tampilan tetap rapi di layar kecil:

### A. Gunakan Grid/Flexbox dengan Fitur *Wrap*
Jika Anda membuat form input berserbelahan (seperti form *Filter* atau form *Tambah Barang*), pastikan Anda menambahkan *margin bottom* pada versi mobile agar input atas dan bawah tidak saling berdempetan.
Gunakan _class utility_ dari Bootstrap 5:
- `mb-3 mb-md-0`: Artinya akan ada jarak bawah (_margin bottom_) sebesar `3` pada tampilan *mobile*, namun pada ukuran menengah (tablet/laptop) _margin bottom_-nya menjadi `0` (karena letaknya sudah sejajar menyamping).
```html
<!-- Contoh Form Input Sebelahan -->
<div class="row">
    <div class="col-md-6 mb-3 mb-md-0">
        <label>Input Kiri</label>
        <input type="text" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Input Kanan</label>
        <input type="text" class="form-control">
    </div>
</div>
```

### B. Manfaatkan `table-responsive`
Untuk tabel data yang kolomnya sangat banyak, tabel akan hancur dan terpotong di HP jika tidak dibungkus dengan *wrapper responsive*. Selalu bungkus tag `<table>` dengan `<div class="table-responsive">`.
```html
<div class="card-body">
    <div class="table-responsive"> <!-- WAJIB -->
        <table class="table table-bordered" width="100%">
            <!-- Isi Tabel -->
        </table>
    </div>
</div>
```

### C. Responsif Tombol Aksi (Buttons)
Jika ada beberapa tombol aksi berjajar, pastikan di mobile tombol tersebut tidak saling menumpuk. Gunakan `d-flex` dipadukan dengan `gap-2`, dan `flex-wrap` bila tombolnya panjang.
```html
<div class="d-flex gap-2 flex-wrap">
    <button class="btn btn-primary">Simpan</button>
    <button class="btn btn-secondary">Batal</button>
</div>
```

### D. Penyesuaian *Media Query* (CSS Khusus Mobile)
Jika *class* bawaan tidak cukup membantu, Anda bisa menaruh CSS khusus mobile di bagian `@media (max-width: 768px)` pada *file* `pic.css` atau `sidebar.css`.
```css
@media (max-width: 768px) {
    /* Aturan CSS ini hanya akan berjalan jika layar HP < 768px */
    .page-title {
        font-size: 18px; /* Kecilkan ukuran font judul di HP */
    }
    
    .card-body {
        padding: 15px; /* Kurangi jarak ruang kosong (padding) di HP */
    }
}
```

---

## 8. Tombol Logout Tidak Bereaksi (Silent JavaScript Error)

**Kasus:** Tombol **Logout** di sidebar PIC diklik, namun tidak ada konfirmasi yang muncul dan tidak terjadi apa-apa.

**Penyebab:** Atribut `onclick` pada elemen `<a>` logout memanggil fungsi JavaScript yang **tidak pernah didefinisikan**. Dalam hal ini, fungsi `showLogoutModal()` dipanggil, padahal yang terdefinisi di bagian bawah file adalah `confirmLogout()`. Browser mengalami `ReferenceError` secara diam-diam (silent), sehingga tidak ada tindakan yang dijalankan.

**Lokasi File:** `resources/views/layouts/sidebar-pic.blade.php`

**Solusi:**
Pastikan nama fungsi yang dipanggil di `onclick` **benar-benar sama persis** dengan nama fungsi yang didefinisikan di blok `<script>` dalam file yang sama.

```diff
- onclick="event.preventDefault(); showLogoutModal();"
+ onclick="event.preventDefault(); confirmLogout();"
```

> **Tips:** Sebelum deploy, buka DevTools browser (F12 → tab Console) dan klik tombol yang bermasalah. Jika ada pesan `ReferenceError: showLogoutModal is not defined`, berarti fungsi yang dipanggil tidak ada.

---

## 9. Login Superadmin Gagal: `View [layouts.superadmin] not found`

**Kasus:** Setelah login sebagai superadmin, muncul layar merah dengan pesan `InvalidArgumentException: View [layouts.superadmin] not found.`

**Penyebab:** Semua halaman superadmin (mis. `dashboard-superadmin.blade.php`) menggunakan `@extends('layouts.superadmin')`, namun file layout tersebut (`resources/views/layouts/superadmin.blade.php`) **belum pernah dibuat**. Layout untuk superadmin terlewat dibuat saat migrasi.

**Solusi:**
Buat file layout `resources/views/layouts/superadmin.blade.php` yang mengikutkan sidebar dan topbar superadmin:

```blade
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Superadmin')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @yield('styles')
</head>
<body>
  @include('layouts.sidebar-superadmin')
  <div id="main-content" class="main-content">
    @include('layouts.topbar-absen')
    <div class="dashboard-content">
      @yield('content')
    </div>
  </div>
  <script src="{{ asset('js/script.js') }}"></script>
  @yield('scripts')
</body>
</html>
```

> **Aturan:** Setiap role (karyawan, PIC, superadmin) **wajib memiliki file layout-nya sendiri** di folder `resources/views/layouts/`. Jika file layout tidak ada, semua halaman yang menggunakan `@extends()` akan langsung error.

---

## 10. Login Superadmin Gagal: `Route [superadmin.dashboard] not defined`

**Kasus:** Setelah layout dibuat dan login berhasil, muncul layar merah baru: `RouteNotFoundException: Route [superadmin.dashboard] not defined.` Error ini muncul dari `sidebar-superadmin.blade.php`.

**Penyebab (berlapis-lapis):** Ada tiga masalah sekaligus:

1. **Home redirect menggunakan nama route lama.** Di `routes/web.php`, saat superadmin login redirect awalnya mengarah ke `superadmin.dashboard`, namun di commit sebelumnya route itu sudah **diganti namanya** menjadi `dashboard.superadmin`.

2. **Sidebar menggunakan route yang belum terdefinisi.** `sidebar-superadmin.blade.php` memanggil 7 route sekaligus yang tidak ada di `routes/web.php`:
   - `superadmin.dashboard` (sudah diubah namanya)
   - `superadmin.karyawan.index`, `.create`, `.store`, `.show`, `.edit`, `.update`, `.destroy`
   - `superadmin.approval.index`
   - `superadmin.inventory.index`
   - `superadmin.transfer.index`
   - `superadmin.profile`

3. **`ProfileController` tidak di-import** di `routes/web.php`, padahal dipakai di route inventory profile PIC.

**Solusi:**

**A. Perbaiki redirect di `routes/web.php`:**
```diff
- 'superadmin' => redirect()->route('superadmin.dashboard'),
+ 'superadmin' => redirect()->route('dashboard.superadmin'),
```

**B. Tambahkan alias dan semua route yang hilang di `routes/web.php`:**
```php
// Alias agar sidebar bisa pakai route('superadmin.dashboard')
Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])
    ->name('superadmin.dashboard');

// Karyawan
Route::get('/superadmin/karyawan', [SuperadminController::class, 'karyawanIndex'])
    ->name('superadmin.karyawan.index');
// ... (beserta route create, store, show, edit, update, destroy)

// Approval
Route::get('/superadmin/approval', [SuperadminController::class, 'approvalIzinCuti'])
    ->name('superadmin.approval.index');

// Inventory & Transfer
Route::get('/superadmin/inventory', [SuperadminController::class, 'inventoryIndex'])
    ->name('superadmin.inventory.index');
Route::get('/superadmin/transfer', [SuperadminController::class, 'transferIndex'])
    ->name('superadmin.transfer.index');

// Profile
Route::get('/superadmin/profile', [SuperadminController::class, 'profile'])
    ->name('superadmin.profile');
```

**C. Tambahkan import `ProfileController` di bagian atas `routes/web.php`:**
```php
use App\Http\Controllers\ProfileController;
```

**D. Tambahkan semua method yang hilang di `SuperadminController.php`** (karyawanIndex, karyawanCreate, karyawanStore, karyawanShow, karyawanEdit, karyawanUpdate, karyawanDestroy, inventoryIndex, transferIndex, profile).

> **Pelajaran Penting:** Jika sebuah commit mengubah nama route (mis. dari `superadmin.dashboard` menjadi `dashboard.superadmin`), **semua tempat yang memanggil route lama harus ikut diperbarui** — termasuk sidebar, layout, dan home redirect. Gunakan perintah `php artisan route:list` untuk memverifikasi semua route sudah terdaftar dengan benar sebelum mencoba login.

---

## 11. Error Undefined Variable saat Menampilkan View Detail
**Kasus:** Mengakses halaman detail karyawan (`/superadmin/karyawan/5`) memicu error `Undefined variable $karyawan` di view `data-karyawan.blade.php`.
**Penyebab:** Controller `karyawanShow` memanggil view `data-karyawan` (yang didesain untuk menampilkan tabel semua karyawan dengan variabel `$karyawan`), namun controller hanya mengirimkan data detail satu user (`compact('user')`).
**Solusi:** 
Ubah alur detail karyawan. Alih-alih membuat halaman detail tersendiri, detail karyawan kini ditampilkan menggunakan modal popup di halaman index (`data.blade.php`). Controller `karyawanShow` cukup me-redirect ke halaman index dengan membawa flash session `show_karyawan`.
```php
public function karyawanShow($id)
{
    $user = User::findOrFail($id);
    return redirect()->route('superadmin.karyawan.index')->with('show_karyawan', $user);
}
```
Latu di bagian script `data.blade.php`, tangkap session tersebut untuk menampilkan modal secara otomatis:
```javascript
@if(session('show_karyawan'))
const showUser = @json(session('show_karyawan'));
if (showUser) {
    // Isi field modal dan tampilkan modal...
    modal.classList.add('show');
}
@endif
```

---

## 12. Menyesuaikan View Hasil Git Pull di Controller
**Kasus:** Halaman edit karyawan error atau tampilan data karyawan tidak berubah setelah melakukan `git pull` dari repositori.
**Penyebab:** Branch baru menambahkan view terpisah seperti `data.blade.php` dan `edit.blade.php` di bawah folder `resources/views/superadmin/karyawan/`, namun controller masih memanggil view lama (`data-karyawan` dan `insert-karyawan` untuk edit).
**Solusi:**
Selalu periksa file view baru setelah melakukan merge/pull, dan sesuaikan pemanggilan view di controller agar mengarah ke file yang tepat beserta nama variabel yang diharapkan oleh view tersebut.
❌ **Salah (Controller):**
```php
return view('superadmin.karyawan.insert-karyawan', compact('user'));
```
✅ **Benar (Controller):**
```php
$karyawan = User::findOrFail($id);
return view('superadmin.karyawan.edit', compact('karyawan'));
```

---

## 13. Kolom Status dan Aksi pada Halaman Approval Bersifat Statis
**Kasus:** Notifikasi sukses muncul setelah menekan tombol "Setujui" / "Tolak", namun kolom status tetap bertuliskan "Pending" dan tombol aksi masih muncul. Selain itu, tombol "Lihat File" lampiran rusak.
**Penyebab:** 
1. Teks status di-hardcode langsung sebagai `"Pending"` di file blade.
2. Form aksi tidak menyaring status data, sehingga tombol persetujuan selalu dirender meskipun data sudah disetujui/ditolak.
3. Menggunakan field `$data->filepath` padahal kolom pada database bernama `file_path`.
**Solusi:**
Sajikan status secara dinamis berdasarkan nilai kolom status database, sembunyikan tombol aksi jika status sudah selesai diproses (`approved` atau `rejected`), dan gunakan nama kolom database yang benar (`file_path`):
```blade
<td class="text-center">
    @if($data->status == 'approved')
        <span class="badge-custom badge-approved">Disetujui</span>
    @elseif($data->status == 'rejected')
        <span class="badge-custom badge-rejected">Ditolak</span>
    @else
        <span class="badge-custom badge-pending">Pending</span>
    @endif
</td>
<td class="text-center">
    @if($data->status == 'pending')
        <!-- Render form Setujui & Tolak -->
    @else
        <span>Selesai</span>
    @endif
</td>
```

---

## 14. Grafik/Statistik di Dashboard Absensi Karyawan Bersifat Dummy (Hardcoded JS)
**Kasus:** Statistik tingkat kehadiran bulanan di dashboard personal karyawan tidak berubah dan selalu mengikuti angka dummy bawaan template JavaScript.
**Penyebab:** Data bulanan dihitung di sisi client menggunakan variabel array JavaScript statis (`dataDashboard = { '2025': ... }`).
**Solusi:**
1. Hitung seluruh data kehadiran secara real-time di controller (absen masuk, keterlambatan di atas pukul `08:00:00`, izin/cuti yang disetujui, dan jumlah hari libur akhir pekan) pada bulan berjalan.
2. Kirim data tersebut dari controller ke view.
3. Di dalam view, gantikan object JS dummy dengan data JSON dari backend:
```javascript
// Di dalam script blade
const data = @json($stats);
// Lakukan kalkulasi persentase dan render ke elemen DOM
```
