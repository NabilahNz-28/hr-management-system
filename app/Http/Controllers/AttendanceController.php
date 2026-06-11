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

    // Fungsi untuk cek apakah sudah absen hari ini (opsional)
    public function checkTodayAttendance(Request $request)
    {
        $userId = Auth::id();
        $type   = $request->input('attendance_type', 'masuk');

        $attendance = Attendance::where('user_id', $userId)
            ->where('attendance_type', $type)
            ->whereDate('attendance_time', now()->toDateString())
            ->first();

        return response()->json([
            'has_attended' => $attendance ? true : false,
            'time'         => $attendance ? $attendance->attendance_time->format('H:i:s') : null,
        ]);
    }

    public function simpanAbsensi(Request $request)
    {
        return $this->store($request);
    }

    public function getRiwayat()
    {
        $attendances = Attendance::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }
}
