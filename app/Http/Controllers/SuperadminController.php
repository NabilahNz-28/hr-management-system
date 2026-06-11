<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $pengajuan = \App\Models\Leave::with('karyawan')->orderBy('created_at', 'desc')->get();
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

        return view('superadmin.karyawan.data-karyawan', compact('karyawan'));
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
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:karyawan,pic,superadmin',
            'status'   => 'nullable|in:aktif,nonaktif',
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
        return view('superadmin.karyawan.data-karyawan', compact('user'));
    }

    public function karyawanEdit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.karyawan.insert-karyawan', compact('user'));
    }

    public function karyawanUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:karyawan,pic,superadmin',
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

    // ===== INVENTORY =====

    public function inventoryIndex()
    {
        return view('superadmin.inventory.data-inventory');
    }

    public function transferIndex()
    {
        return view('superadmin.inventory.transfer-stock');
    }

    // ===== PROFILE =====

    public function profile()
    {
        return view('superadmin.profile');
    }
}
