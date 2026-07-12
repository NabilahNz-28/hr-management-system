<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiReportController extends Controller
{
    // Batas jam masuk; lebih dari ini dianggap terlambat.
    private const JAM_BATAS_MASUK = '08:00:00';

    /**
     * Rekap absensi harian personal (user yang login).
     */
    public function harian(Request $request)
    {
        $user    = Auth::user();
        $tanggal = $request->input('tanggal', now()->toDateString());

        $records = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_time', $tanggal)
            ->get();

        $masuk  = $records->firstWhere('attendance_type', 'masuk');
        $pulang = $records->firstWhere('attendance_type', 'pulang');

        $jamMasuk  = $masuk  ? $masuk->attendance_time->format('H:i')  : '-';
        $jamPulang = $pulang ? $pulang->attendance_time->format('H:i') : '-';

        if ($masuk) {
            $status = 'Hadir';
        } else {
            $status = 'Tidak Hadir';
        }

        return view('absensi.monitoring.rekap-harian', [
            'user'       => $user,
            'tanggal'    => $tanggal,
            'jamMasuk'   => $jamMasuk,
            'jamPulang'  => $jamPulang,
            'status'     => $status,
            'alamat'     => $masuk->address ?? ($pulang->address ?? '-'),
            'adaData'    => $records->isNotEmpty(),
        ]);
    }

    /**
     * Rekap absensi bulanan personal (statistik per bulan).
     */
    public function bulanan(Request $request)
    {
        $user  = Auth::user();
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $stats = $this->hitungRekapBulanan($user->id, $bulan, $tahun);

        return view('absensi.monitoring.rekap-bulanan', array_merge($stats, [
            'user'  => $user,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]));
    }

    /**
     * Laporan absensi: profil karyawan + rekap bulan terpilih.
     */
    public function laporan(Request $request)
    {
        $user  = Auth::user();
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $stats = $this->hitungRekapBulanan($user->id, $bulan, $tahun);

        return view('absensi.laporan.laporan-absensi', array_merge($stats, [
            'user'  => $user,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]));
    }

    /**
     * Hitung statistik kehadiran satu bulan dari tabel attendances + leaves.
     *
     * @return array{hadir:int,terlambat:int,izin:int,cuti:int,libur:int,totalHari:int}
     */
    private function hitungRekapBulanan(int $userId, int $bulan, int $tahun): array
    {
        $awal  = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();
        $totalHari = $awal->daysInMonth;

        // Ambil semua absensi masuk pada bulan tsb, dikelompokkan per tanggal.
        $masukPerHari = Attendance::where('user_id', $userId)
            ->where('attendance_type', 'masuk')
            ->whereBetween('attendance_time', [$awal, $akhir->copy()->endOfDay()])
            ->get()
            ->groupBy(fn ($a) => $a->attendance_time->toDateString());

        $hadir = $masukPerHari->count();
        $terlambat = 0; // Logika keterlambatan dihapus

        // Izin & cuti yang sudah disetujui dan benar-benar beririsan dengan bulan ini.
        $leaves = Leave::where('karyawan_id', $userId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereDate('end_date', '>=', $awal->toDateString())
                  ->orWhere(function ($q2) use ($awal) {
                      $q2->whereNull('end_date')
                         ->whereDate('start_date', '>=', $awal->toDateString());
                  });
            })
            ->get();

        $izin = 0;
        $cuti = 0;
        foreach ($leaves as $lv) {
            $s = Carbon::parse($lv->start_date)->max($awal);
            $e = $lv->end_date ? Carbon::parse($lv->end_date)->min($akhir) : $s->copy();
            $hari = (int) $s->diffInDays($e) + 1;
            if ($hari > 0) {
                if ($lv->jenis === 'izin') {
                    $izin += $hari;
                } else {
                    $cuti += $hari;
                }
            }
        }

        // Libur: jika tidak ada data absensi dalam 1 bulan maka dihitung jumlah hari libur (max dari sisa hari dalam bulan)
        $libur = max(0, $totalHari - $hadir - $cuti - $izin);

        return [
            'hadir'     => $hadir,
            'terlambat' => $terlambat,
            'izin'      => $izin,
            'cuti'      => $cuti,
            'libur'     => $libur,
            'totalHari' => $totalHari,
        ];
    }
}
