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
