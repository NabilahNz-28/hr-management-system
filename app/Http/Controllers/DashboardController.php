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
        $aktivitasList = []; // sementara (atau ambil dari DB nanti)

        return view('dashboard.dashboard-pic', compact('aktivitasList'));
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

        $terlambat = $masukPerHari->filter(function ($items) {
            $pertama = $items->sortBy('attendance_time')->first();
            return \Carbon\Carbon::parse($pertama->attendance_time)->format('H:i:s') > '08:00:00';
        })->count();

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

        // Libur = jumlah hari Sabtu/Minggu dalam bulan tsb.
        $libur = 0;
        for ($d = $awal->copy(); $d->lte($akhir); $d->addDay()) {
            if ($d->isWeekend()) {
                $libur++;
            }
        }

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
