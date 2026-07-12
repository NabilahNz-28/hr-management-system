<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// use App\Models\Attendance; // Buka comment ini jika model sudah ada
// use App\Models\Inventory; // Buka comment ini jika model sudah ada

class SuperadminController extends Controller
{
    public function dashboard()
    {
        $bulanIni = Carbon::now()->month;
        
        // --- 1. MOCK DATA KARYAWAN & ABSENSI ---
        // Nanti ganti dengan: $karyawans = User::whereIn('role', ['karyawan', 'pic'])->withCount([...])->get();
        $karyawans = [
            (object)['id'=>1, 'name'=>'Ahmad Wijaya', 'role'=>'karyawan', 'email'=>'ahmad@mail.com', 'hari_kerja'=>22, 'cuti'=>1, 'izin'=>0, 'lembur_hours'=>10],
            (object)['id'=>2, 'name'=>'Siti Rahma', 'role'=>'pic', 'email'=>'siti@mail.com', 'hari_kerja'=>20, 'cuti'=>0, 'izin'=>2, 'lembur_hours'=>5],
            (object)['id'=>3, 'name'=>'Budi Santoso', 'role'=>'karyawan', 'email'=>'budi@mail.com', 'hari_kerja'=>24, 'cuti'=>0, 'izin'=>0, 'lembur_hours'=>15],
        ];

        // --- 2. MOCK DATA INVENTORY ---
        $inventories = [
            (object)['id'=>101, 'tanggal'=>'12 Mar 2026', 'nama_transaksi'=>'Opname Gudang Utama', 'jumlah_item'=>2, 'items'=>[['nama'=>'Box Eco 250ml', 'selisih'=>120], ['nama'=>'Paper Bowl', 'selisih'=>-20]]],
            (object)['id'=>102, 'tanggal'=>'10 Mar 2026', 'nama_transaksi'=>'Transfer Cabang Bandung', 'jumlah_item'=>1, 'items'=>[['nama'=>'Gelas Plastik', 'selisih'=>50]]],
        ];

        // --- 3. SETTING GAJI GLOBAL ---
        // Nanti ini bisa di-query dari tabel Settings.
        $settings = [
            'uang_pokok' => 4000000,
            'uang_makan' => 50000,
            'uang_bensin' => 20000,
            'rate_lembur' => 25000
        ];

        // --- 4. KALKULASI STATISTIK ---
        $totalKaryawan = count($karyawans);
        $hadirHariIni = 42; // Nanti query: Attendance::whereDate('created_at', Carbon::today())->where('status','hadir')->count();
        $estimasiGaji = 0;
        
        foreach($karyawans as $k) {
            $gajiKaryawan = $settings['uang_pokok'] 
                + ($k->hari_kerja * $settings['uang_makan']) 
                + ($k->hari_kerja * $settings['uang_bensin']) 
                + ($k->lembur_hours * $settings['rate_lembur']);
            $estimasiGaji += $gajiKaryawan;
        }

        return view('superadmin.dashboard', compact('karyawans', 'inventories', 'settings', 'totalKaryawan', 'hadirHariIni', 'estimasiGaji'));
    }

    // Fungsi untuk nyimpen user baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        /* 
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        */

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function approvalIzinCuti()
    {
        $pengajuan = \App\Models\Leave::with('karyawan')->orderBy('created_at', 'desc')->paginate(10);
        return view('superadmin.approval.approval-izincuti', compact('pengajuan'));
    }

    public function approve($id)
    {
        $leave = \App\Models\Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $leave = \App\Models\Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    // ===== KARYAWAN =====

    public function karyawanIndex(Request $request)
    {
        $query = User::where('role', '!=', 'superadmin');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $karyawan = $query->orderBy('name')->paginate(10);

        return view('superadmin.karyawan.data', compact('karyawan'));
    }

    public function karyawanCreate()
    {
        return view('superadmin.karyawan.insert-karyawan');
    }

    public function karyawanStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'nullable|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'no_hp'    => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()->symbols()],
            'role'     => 'required|in:karyawan,pic,superadmin',
            'status'   => 'nullable|in:aktif,nonaktif',
        ], [
            'name.required'      => 'Nama karyawan wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.letters'   => 'Password wajib mengandung huruf.',
            'password.mixed'     => 'Password wajib mengandung minimal 1 huruf kapital (besar) dan 1 huruf kecil.',
            'password.numbers'   => 'Password wajib mengandung minimal 1 angka.',
            'password.symbols'   => 'Password wajib mengandung minimal 1 simbol.',
        ]);

        User::create([
            'name'         => $request->name,
            'nik'          => $request->nik,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'status'       => $request->status ?? 'aktif',
            'departemen'   => $request->departemen,
            'jabatan'      => $request->jabatan,
            'tgl_bergabung'=> $request->tgl_bergabung,
            'alamat'       => $request->alamat,
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function karyawanShow($id)
    {
        $user = User::findOrFail($id);
        return redirect()->route('superadmin.karyawan.index')->with('show_karyawan', $user);
    }

    public function karyawanEdit($id)
    {
        $karyawan = User::findOrFail($id);
        return view('superadmin.karyawan.edit', compact('karyawan'));
    }

    public function karyawanUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:karyawan,pic,superadmin',
            // Password opsional saat edit; jika diisi wajib kuat
            'password' => ['nullable', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'name.required'      => 'Nama karyawan wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan karyawan lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.letters'   => 'Password wajib mengandung huruf.',
            'password.mixed'     => 'Password wajib mengandung minimal 1 huruf kapital (besar) dan 1 huruf kecil.',
            'password.numbers'   => 'Password wajib mengandung minimal 1 angka.',
            'password.symbols'   => 'Password wajib mengandung minimal 1 simbol.',
        ]);

        $data = $request->only([
            'name', 'email', 'role', 'status', 'nik', 'departemen',
            'jabatan', 'no_hp', 'alamat', 'tgl_bergabung',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function karyawanDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    public function karyawanAttendanceData($id, Request $request)
    {
        $user = User::findOrFail($id);

        // Default: bulan ini. Format parameter: YYYY-MM
        try {
            $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth();
            $akhir = Carbon::now()->endOfMonth();
        }

        // Hitung hari masuk
        $hadir = $user->attendances()
            ->where('attendance_type', 'masuk')
            ->whereBetween('attendance_time', [$awal, $akhir])
            ->get()
            ->groupBy(fn ($a) => Carbon::parse($a->attendance_time)->toDateString())
            ->count();

        // Hitung terlambat (logika keterlambatan dihapus)
        $terlambat = 0;

        // Hitung izin
        $izinLeaves = $user->leaves()
            ->where('status', 'approved')
            ->where('jenis', 'izin')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereDate('end_date', '>=', $awal->toDateString())
                  ->orWhere(function ($q2) use ($awal) {
                      $q2->whereNull('end_date')
                         ->whereDate('start_date', '>=', $awal->toDateString());
                  });
            })
            ->get();

        $izin = $izinLeaves->sum(function ($item) use ($awal, $akhir) {
            $start = Carbon::parse($item->start_date)->max($awal);
            $end = $item->end_date ? Carbon::parse($item->end_date)->min($akhir) : $start;
            return max(1, $start->diffInDays($end) + 1);
        });

        // Hitung cuti
        $cutiLeaves = $user->leaves()
            ->where('status', 'approved')
            ->where('jenis', 'cuti')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereDate('end_date', '>=', $awal->toDateString())
                  ->orWhere(function ($q2) use ($awal) {
                      $q2->whereNull('end_date')
                         ->whereDate('start_date', '>=', $awal->toDateString());
                  });
            })
            ->get();

        $cuti = $cutiLeaves->sum(function ($item) use ($awal, $akhir) {
            $start = Carbon::parse($item->start_date)->max($awal);
            $end = $item->end_date ? Carbon::parse($item->end_date)->min($akhir) : $start;
            return max(1, $start->diffInDays($end) + 1);
        });

        return response()->json([
            'hadir'     => $hadir,
            'izin'      => $izin,
            'cuti'      => $cuti,
            'terlambat' => $terlambat,
        ]);
    }

    // ===== INVENTORY =====

    public function inventoryIndex(Request $request)
    {
        $raw = \App\Models\StockOpname::with(['inventory', 'user'])
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('inventory', fn ($q2) => $q2->where('kategori', $request->kategori)))
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $raw->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') . '_' . ($item->user_id ?? 0))
            ->map(function ($items, $key) {
                $first = $items->first();
                $dateKey = \Carbon\Carbon::parse($first->tanggal)->format('Y-m-d');
                $userId = $first->user_id ?? 0;
                return [
                    'invoice_no'    => 'INV-OPN-' . \Carbon\Carbon::parse($dateKey)->format('Ymd') . ($userId ? '-' . str_pad($userId, 2, '0', STR_PAD_LEFT) : ''),
                    'tanggal'       => $dateKey,
                    'user_id'       => $userId,
                    'petugas_name'  => $first->user->name ?? 'PIC / Unknown',
                    'items'         => $items,
                    'item_count'    => $items->count(),
                    'produk_names'  => $items->pluck('inventory.nama_barang')->filter()->unique()->implode(', '),
                    'kategori_list' => $items->pluck('inventory.kategori')->filter()->unique()->map(fn($k) => ucfirst($k))->implode(', '),
                    'total_selisih' => $items->sum('selisih'),
                    'catatan'       => $items->pluck('catatan')->filter()->unique()->implode('; '),
                ];
            })->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('superadmin.inventory.data-inventory', compact('laporan'));
    }

    public function batalkanInventory(Request $request)
    {
        $request->validate(['tanggal' => 'required|date']);
        $userId = $request->user_id;

        $query = \App\Models\StockOpname::with('inventory')->whereDate('tanggal', $request->tanggal);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $items = $query->get();

        foreach ($items as $item) {
            if ($item->inventory) {
                $item->inventory->update(['stok_fisik' => $item->stok_sebelum]);
            }
            $item->delete();
        }

        return redirect()->route('superadmin.inventory.index')
            ->with('success', 'Transaksi opname tanggal ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . ' berhasil dibatalkan dan stok dikembalikan.');
    }

    public function exportInventoryExcel(Request $request)
    {
        $items = \App\Models\StockOpname::with(['inventory', 'user'])
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('inventory', fn ($q2) => $q2->where('kategori', $request->kategori)))
            ->orderBy('tanggal', 'desc')
            ->get();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Rekap_Stock_Opname_Superadmin_' . date('Ymd_His') . '.xls"');

        echo '<table border="1">';
        echo '<tr><th>No</th><th>Tanggal</th><th>Petugas</th><th>Nama Barang</th><th>Kategori</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Selisih</th><th>Catatan</th></tr>';
        foreach ($items as $index => $row) {
            $tgl = \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d');
            $petugas = $row->user->name ?? '-';
            $nama = $row->inventory->nama_barang ?? '-';
            $kat = ucfirst($row->inventory->kategori ?? '-');
            echo "<tr><td>".($index+1)."</td><td>{$tgl}</td><td>{$petugas}</td><td>{$nama}</td><td>{$kat}</td><td>{$row->stok_sebelum}</td><td>{$row->stok_sesudah}</td><td>{$row->selisih}</td><td>{$row->catatan}</td></tr>";
        }
        echo '</table>';
        exit;
    }

    public function transferIndex(Request $request)
    {
        $raw = \App\Models\TransferStock::with(['barang', 'user'])
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $raw->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') . '_' . ($item->user_id ?? 0))
            ->map(function ($items, $key) {
                $first = $items->first();
                $dateKey = \Carbon\Carbon::parse($first->tanggal)->format('Y-m-d');
                $userId = $first->user_id ?? 0;
                $hasPending = $items->contains('status', 'Pending');
                $allBatal = $items->every(fn($i) => strtolower($i->status) === 'dibatalkan');
                $status = $allBatal ? 'Dibatalkan' : ($hasPending ? 'Pending' : 'Selesai');

                return [
                    'invoice_no'    => 'INV-TRF-' . \Carbon\Carbon::parse($dateKey)->format('Ymd') . ($userId ? '-' . str_pad($userId, 2, '0', STR_PAD_LEFT) : ''),
                    'tanggal'       => $dateKey,
                    'user_id'       => $userId,
                    'petugas_name'  => $first->user->name ?? 'PIC / Unknown',
                    'items'         => $items,
                    'item_count'    => $items->count(),
                    'produk_names'  => $items->pluck('barang.nama_barang')->filter()->unique()->implode(', '),
                    'gudang_tujuan' => $items->pluck('ke_gudang')->filter()->unique()->implode(', '),
                    'total_jumlah'  => $items->sum('jumlah'),
                    'status'        => $status,
                    'catatan'       => $items->pluck('catatan')->filter()->unique()->implode('; '),
                ];
            })->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('superadmin.inventory.transfer-stock', compact('laporan'));
    }

    public function batalkanTransfer(Request $request)
    {
        $request->validate(['tanggal' => 'required|date']);
        $userId = $request->user_id;

        $query = \App\Models\TransferStock::with('barang')
            ->whereDate('tanggal', $request->tanggal)
            ->where('status', '!=', 'Dibatalkan');
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $items = $query->get();

        foreach ($items as $item) {
            if ($item->barang) {
                $item->barang->increment('stok_fisik', $item->jumlah);
            }
            $item->update(['status' => 'Dibatalkan']);
        }

        return redirect()->route('superadmin.transfer.index')
            ->with('success', 'Transaksi transfer tanggal ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . ' berhasil dibatalkan dan stok dikembalikan.');
    }

    public function exportTransferExcel(Request $request)
    {
        $items = \App\Models\TransferStock::with(['barang', 'user'])
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('tanggal', 'desc')
            ->get();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Rekap_Transfer_Stock_Superadmin_' . date('Ymd_His') . '.xls"');

        echo '<table border="1">';
        echo '<tr><th>No</th><th>Tanggal</th><th>Petugas</th><th>Barang</th><th>Gudang Asal</th><th>Ke Gudang</th><th>Jumlah</th><th>Satuan</th><th>Status</th><th>Catatan</th></tr>';
        foreach ($items as $index => $row) {
            $tgl = \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d');
            $petugas = $row->user->name ?? '-';
            $nama = $row->barang->nama_barang ?? '-';
            $tujuan = $row->ke_gudang ?? '-';
            $st = ucfirst($row->status ?? 'Selesai');
            echo "<tr><td>".($index+1)."</td><td>{$tgl}</td><td>{$petugas}</td><td>{$nama}</td><td>Gudang Utama</td><td>{$tujuan}</td><td>{$row->jumlah}</td><td>{$row->satuan}</td><td>{$st}</td><td>{$row->catatan}</td></tr>";
        }
        echo '</table>';
        exit;
    }

    // ===== PROFILE =====

    public function profile()
    {
        $user = auth()->user();
        return view('superadmin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string|max:500',
            'foto_profile' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'no_hp', 'alamat']);

        if ($request->hasFile('foto_profile')) {
            $data['foto_profile'] = $request->file('foto_profile')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('superadmin.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.min'              => 'Password minimal 6 karakter.',
            'password.letters'          => 'Password wajib mengandung huruf.',
            'password.mixed'            => 'Password wajib mengandung minimal 1 huruf kapital (besar) dan 1 huruf kecil.',
            'password.numbers'          => 'Password wajib mengandung minimal 1 angka.',
            'password.symbols'          => 'Password wajib mengandung minimal 1 simbol.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('superadmin.profile')
            ->with('success', 'Password berhasil diubah.');
    }
}
