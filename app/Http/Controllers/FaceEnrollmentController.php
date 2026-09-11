<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FaceEnrollmentController extends Controller
{
    /**
     * Tampilkan halaman daftar karyawan + status wajah mereka.
     * Hanya bisa diakses superadmin.
     */
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $karyawan = User::where('role', 'karyawan')
            ->orderBy('name')
            ->get(['id', 'name', 'nik', 'jabatan', 'departemen', 'foto_profile', 'face_descriptor']);

        return view('absensi.pengaturan.face-enrollment', compact('karyawan'));
    }

    /**
     * Simpan face descriptor untuk satu karyawan.
     * Dipanggil via AJAX dari halaman enrollment.
     */
    public function store(Request $request, $id)
    {
        if (Auth::user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'descriptor' => 'required|array|min:128|max:128',
            'descriptor.*' => 'required|numeric',
        ]);

        $user = User::findOrFail($id);
        $user->face_descriptor = $request->descriptor;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Wajah {$user->name} berhasil didaftarkan.",
        ]);
    }

    /**
     * Hapus data wajah karyawan.
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $user = User::findOrFail($id);
        $user->face_descriptor = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Data wajah {$user->name} berhasil dihapus.",
        ]);
    }

    /**
     * Return semua face descriptor karyawan aktif dalam format JSON.
     * Digunakan oleh halaman absen-masuk & absen-pulang untuk pencocokan client-side.
     * Hanya return user yang sudah punya descriptor.
     */
    public function descriptors()
    {
        $users = User::whereNotNull('face_descriptor')
            ->where('status', '!=', 'nonaktif')
            ->get(['id', 'name', 'face_descriptor']);

        $data = $users->map(fn ($u) => [
            'id'         => $u->id,
            'name'       => $u->name,
            'descriptor' => $u->face_descriptor, // sudah di-cast jadi array
        ]);

        return response()->json($data);
    }

    /**
     * Cek apakah user yang sedang login sudah punya face descriptor.
     * Dipakai oleh halaman absen untuk blokir/izinkan tombol foto.
     */
    public function checkOwn()
    {
        $user = Auth::user();
        return response()->json([
            'enrolled' => !is_null($user->face_descriptor),
            'name'     => $user->name,
        ]);
    }
}
