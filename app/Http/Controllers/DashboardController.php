<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */

        // Dashboard PIC - Inventory
    public function pic()
    {
        $userId = auth()->id();
        $now    = \Carbon\Carbon::now();

        // Statistik
        $totalBarang = \App\Models\Inventory::count();

        $totalOpname = \App\Models\StockOpname::where('user_id', $userId)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count();

        $totalTransfer = \App\Models\TransferStock::where('user_id', $userId)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count();

        // Aktivitas terbaru (gabungan opname + transfer milik PIC ini)
        $opnames = \App\Models\StockOpname::with('inventory')
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->take(10)
            ->get()
            ->map(fn ($o) => (object) [
                'tanggal'     => $o->tanggal,
                'jenis'       => 'Stock Opname',
                'nama_barang' => $o->inventory->nama_barang ?? '-',
                'jumlah'      => $o->stok_sesudah . ' pcs',
            ]);

        $transfers = \App\Models\TransferStock::with('barang')
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->take(10)
            ->get()
            ->map(fn ($t) => (object) [
                'tanggal'     => $t->tanggal,
                'jenis'       => 'Transfer Stock',
                'nama_barang' => $t->barang->nama_barang ?? '-',
                'jumlah'      => $t->jumlah . ' ' . $t->satuan,
            ]);

        $aktivitasList = $opnames->concat($transfers)
            ->sortByDesc('tanggal')
            ->take(10)
            ->values();

        return view('dashboard.dashboard-pic', compact(
            'totalBarang', 'totalOpname', 'totalTransfer', 'aktivitasList'
        ));
    }

    // Dashboard Absensi
    public function absensi()
    {
        $user = auth()->user();
        $now = \Carbon\Carbon::now();
        $bulan = $now->month;
        $tahun = $now->year;

        $awal  = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        // Ambil semua absensi masuk pada bulan tsb, dikelompokkan per tanggal.
        $masukPerHari = \App\Models\Attendance::where('user_id', $user->id)
            ->where('attendance_type', 'masuk')
            ->whereBetween('attendance_time', [$awal, $akhir->copy()->endOfDay()])
            ->get()
            ->groupBy(fn ($a) => \Carbon\Carbon::parse($a->attendance_time)->toDateString());

        $hadir = $masukPerHari->count();
        $terlambat = 0; // Logika keterlambatan dihapus

        // Izin & cuti dari tabel leaves yang sudah disetujui dan beririsan dengan bulan ini.
        $leaves = \App\Models\Leave::where('karyawan_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', $awal->toDateString());
            })
            ->get();

        $izin = $leaves->where('jenis', 'izin')->count();
        $cuti = $leaves->where('jenis', 'cuti')->count();

        // Libur: jika tidak ada data absensi dalam 1 bulan maka dihitung jumlah hari libur (sisa hari dalam bulan)
        $libur = max(0, $awal->daysInMonth - $hadir - $cuti - $izin);

        $stats = [
            'hadir'     => $hadir,
            'terlambat' => $terlambat,
            'izin'      => $izin,
            'cuti'      => $cuti,
            'libur'     => $libur,
            'total_hari_kerja' => $awal->daysInMonth,
        ];

        return view('dashboard.dashboard-absensiFix', compact('stats'));
    }

    // Dashboard Selection
    public function selection()
    {
        return view('dashboard.dashboard-selection');
    }

    public function superadmin()
    {
        $today = \Carbon\Carbon::today();

        $totalKaryawan = \App\Models\User::where('role', '!=', 'superadmin')->count();

        $jumlahHadir = \App\Models\Attendance::whereDate('attendance_time', $today)
            ->where('attendance_type', 'masuk')
            ->distinct('user_id')
            ->count('user_id');

        $jumlahLibur = \App\Models\Leave::where('status', 'approved')
            ->where(function($q) use ($today) {
                $q->where(function($q2) use ($today) {
                    $q2->whereDate('start_date', '<=', $today)
                       ->whereDate('end_date', '>=', $today);
                })->orWhere(function($q2) use ($today) {
                    $q2->whereDate('start_date', $today)
                       ->whereNull('end_date');
                });
            })
            ->distinct('karyawan_id')
            ->count('karyawan_id');

        return view('dashboard.dashboard-superadmin', compact('totalKaryawan', 'jumlahHadir', 'jumlahLibur'));
    }

    /**
     * Attendance Report
     */
    public function attendanceReport()
    {
        return view('reports.attendance');
    }

    /**
     * Salary Report
     */
    public function salaryReport()
    {
        return view('reports.salary');
    }

    /**
     * Inventory Report
     */
    public function inventoryReport()
    {
        return view('reports.inventory');
    }

}
