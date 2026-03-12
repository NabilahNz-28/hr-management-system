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
}
