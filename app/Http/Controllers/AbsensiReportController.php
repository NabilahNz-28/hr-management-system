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
            $status = $masuk->attendance_time->format('H:i:s') > self::JAM_BATAS_MASUK
                ? 'Terlambat'
                : 'Hadir';
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

        // Ambil semua absensi masuk pada bulan tsb, dikelompokkan per tanggal.
        $masukPerHari = Attendance::where('user_id', $userId)
            ->where('attendance_type', 'masuk')
            ->whereBetween('attendance_time', [$awal, $akhir->copy()->endOfDay()])
            ->get()
            ->groupBy(fn ($a) => $a->attendance_time->toDateString());

        $hadir = $masukPerHari->count();

        $terlambat = $masukPerHari->filter(function ($items) {
            $pertama = $items->sortBy('attendance_time')->first();
            return $pertama->attendance_time->format('H:i:s') > self::JAM_BATAS_MASUK;
        })->count();

        // Izin & cuti dari tabel leaves yang sudah disetujui dan beririsan dengan bulan ini.
        $leaves = Leave::where('karyawan_id', $userId)
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

        return [
            'hadir'     => $hadir,
            'terlambat' => $terlambat,
            'izin'      => $izin,
            'cuti'      => $cuti,
            'libur'     => $libur,
            'totalHari' => $awal->daysInMonth,
        ];
    }
}
