<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Tampilkan halaman face recognition attendance
    public function index()
    {
        return view('attendance');
    }

    // Tampilkan halaman list attendance
    public function show()
    {
        $attendances = Attendance::orderBy('created_at', 'desc')->get();
        return view('attendance-list', compact('attendances'));
    }

    // Simpan attendance dari face recognition
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'attendance_type' => 'required|in:masuk,pulang',
                'photo'           => 'required|string',
                'latitude'        => 'nullable|numeric',
                'longitude'       => 'nullable|numeric',
                'address'         => 'nullable|string',
            ]);

            $user = Auth::user();

            // 1. Cek apakah ada absen masuk aktif dalam 36 jam terakhir yang BELUM dipulangkan (sesi kerja terbuka)
            $activeMasuk = Attendance::where('user_id', $user?->id)
                ->where('attendance_time', '>=', now()->subHours(36))
                ->where('attendance_type', 'masuk')
                ->orderByDesc('attendance_time')
                ->first();

            $activePulang = null;
            if ($activeMasuk) {
                $activePulang = Attendance::where('user_id', $user?->id)
                    ->where('attendance_time', '>', $activeMasuk->attendance_time)
                    ->where('attendance_type', 'pulang')
                    ->orderByDesc('attendance_time')
                    ->first();
            }

            $isCurrentlyWorking = !is_null($activeMasuk) && is_null($activePulang);

            // 2. Cek absensi khusus untuk tanggal hari ini
            $hasMasukToday = Attendance::where('user_id', $user?->id)
                ->whereDate('attendance_time', now()->toDateString())
                ->where('attendance_type', 'masuk')
                ->exists();

            $hasPulangToday = Attendance::where('user_id', $user?->id)
                ->whereDate('attendance_time', now()->toDateString())
                ->where('attendance_type', 'pulang')
                ->exists();

            // Masuk hanya boleh jika tidak sedang bekerja DAN belum pernah absen masuk/pulang hari ini
            if ($request->attendance_type === 'masuk') {
                if ($isCurrentlyWorking) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda masih memiliki sesi absen masuk yang belum dipulangkan. Silakan absen pulang terlebih dahulu.',
                    ], 422);
                }
                if ($hasMasukToday || $hasPulangToday) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan absensi hari ini dan tidak bisa absen masuk kembali.',
                    ], 422);
                }
            }

            // Pulang hanya boleh jika sedang dalam sesi kerja aktif (atau jika ada absen masuk hari ini yang belum dipulangkan)
            if ($request->attendance_type === 'pulang') {
                if (!$isCurrentlyWorking) {
                    if ($hasPulangToday || (!is_null($activeMasuk) && !is_null($activePulang))) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda sudah absen pulang untuk sesi kerja Anda. Tidak bisa melakukan absen pulang kembali.',
                        ], 422);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum absen masuk, jadi tidak bisa absen pulang.',
                    ], 422);
                }
            }

            // Generate nama file unik
            $fileName = 'attendance_' . time() . '_' . rand(1000, 9999) . '.jpg';

            // Path untuk menyimpan
            $folderPath = public_path('photos');

            // Buat folder jika belum ada
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $filePath = $folderPath . '/' . $fileName;

            // Process base64 image
            $imageData = $request->photo;

            // Cek format base64
            if (strpos($imageData, 'data:image/jpeg;base64,') === 0) {
                $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            } elseif (strpos($imageData, 'data:image/png;base64,') === 0) {
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
            }

            $imageData = str_replace(' ', '+', $imageData);

            // Decode dan simpan
            $imageBinary = base64_decode($imageData);

            if ($imageBinary === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data'
                ], 400);
            }

            // Simpan file
            file_put_contents($filePath, $imageBinary);

            // Simpan ke database
            $attendance = Attendance::create([
                'user_id'         => $user?->id,
                'employee_name'   => $user?->name ?? 'Unknown',
                'attendance_type' => $request->attendance_type,
                'photo'           => $fileName,
                'latitude'        => $request->latitude,
                'longitude'       => $request->longitude,
                'address'         => $request->address,
                'attendance_time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi ' . $attendance->attendance_type . ' berhasil disimpan!',
                'data' => [
                    'id'    => $attendance->id,
                    'name'  => $attendance->employee_name,
                    'type'  => $attendance->attendance_type,
                    'time'  => $attendance->attendance_time->format('H:i:s'),
                    'date'  => $attendance->attendance_time->format('Y-m-d'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tambah fungsi untuk menghapus attendance (opsional)
    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);

            // Hapus file foto
            $filePath = public_path('photos/' . $attendance->photo);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $attendance->delete();

            return redirect()->route('attendance.list')
                ->with('success', 'Attendance record deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('attendance.list')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

    // Cek STATUS absensi user hari ini berdasarkan record TERAKHIR (stateful).
    // - sedang bekerja  : record terakhir hari ini = 'masuk'  (sesi terbuka)
    // - sudah pulang     : record terakhir hari ini = 'pulang'
    // - belum absen      : tidak ada record hari ini
    public function checkTodayAttendance(Request $request)
    {
        $this->autoCleanOldPhotosIfNeeded();

        $userId = Auth::id();
        $today  = now()->toDateString();

        // 1. Cek sesi kerja terbuka dari 36 jam terakhir (misalnya shift malam/overnight yang belum dipulangkan)
        $activeMasuk = Attendance::where('user_id', $userId)
            ->where('attendance_time', '>=', now()->subHours(36))
            ->where('attendance_type', 'masuk')
            ->orderByDesc('attendance_time')
            ->first();

        $activePulang = null;
        if ($activeMasuk) {
            $activePulang = Attendance::where('user_id', $userId)
                ->where('attendance_time', '>', $activeMasuk->attendance_time)
                ->where('attendance_type', 'pulang')
                ->orderByDesc('attendance_time')
                ->first();
        }

        $working = !is_null($activeMasuk) && is_null($activePulang);

        // 2. Cek absensi khusus untuk tanggal hari ini
        $masukToday = Attendance::where('user_id', $userId)
            ->whereDate('attendance_time', $today)
            ->where('attendance_type', 'masuk')
            ->orderByDesc('attendance_time')
            ->first();

        $pulangToday = Attendance::where('user_id', $userId)
            ->whereDate('attendance_time', $today)
            ->where('attendance_type', 'pulang')
            ->orderByDesc('attendance_time')
            ->first();

        // status has_masuk: true jika sedang dalam sesi kerja terbuka ($working) ATAU sudah absen masuk hari ini
        $hasMasuk  = $working || !is_null($masukToday) || !is_null($pulangToday);

        // status has_pulang: true jika hari ini sudah selesai absen pulang dan tidak sedang bekerja
        $hasPulang = !$working && (!is_null($pulangToday) || (!is_null($masukToday) && !is_null($pulangToday) && $pulangToday->attendance_time > $masukToday->attendance_time));

        // Tentukan record referensi waktu masuk
        $refMasuk = $working ? $activeMasuk : $masukToday;

        return response()->json([
            'working'      => $working,
            'masuk_iso'    => $refMasuk ? $refMasuk->attendance_time->toIso8601String() : null,
            'masuk_time'   => $refMasuk ? $refMasuk->attendance_time->format('H:i:s') : null,
            'has_masuk'    => $hasMasuk,
            'has_pulang'   => $hasPulang,
            'has_attended' => $working || !is_null($masukToday),
            'time'         => $refMasuk ? $refMasuk->attendance_time->format('H:i:s') : null,
        ]);
    }

    public function simpanAbsensi(Request $request)
    {
        return $this->store($request);
    }

    public function getRiwayat()
    {
        $this->autoCleanOldPhotosIfNeeded();

        $attendances = Attendance::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    /**
     * Otomatis membersihkan foto absensi > 1 tahun (maksimal jalan 1x sehari via cache)
     */
    private function autoCleanOldPhotosIfNeeded()
    {
        try {
            if (!\Illuminate\Support\Facades\Cache::has('absensi_old_photos_cleaned')) {
                \Illuminate\Support\Facades\Artisan::call('absensi:clean-photos');
                \Illuminate\Support\Facades\Cache::put('absensi_old_photos_cleaned', true, now()->addHours(24));
            }
        } catch (\Exception $e) {
            // Abaikan jika error cache/artisan saat background check
        }
    }
}
