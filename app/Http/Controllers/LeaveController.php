<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    public function createCuti()
    {
        // Ambil data dari db yang max_days > 0 (kategori cuti)
        $leaveTypes = LeaveType::where('max_days', '>', 0)->where('is_active', true)->get();
        return view('absensi.absensi.pengajuan-cuti', compact('leaveTypes'));
    }

    public function createIzin()
    {
        // Ambil data dari db yang max_days == 0 (kategori izin)
        $leaveTypes = LeaveType::where('max_days', 0)->where('is_active', true)->get();
        return view('absensi.absensi.pengajuan-izin', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'jenis' => 'required|in:izin,cuti',
            'jenis_detail' => 'required|string',
            'keterangan' => 'required|string|min:10|max:2000',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $path = null;
            if ($request->hasFile('document')) {
                $filename = 'lampiran_' . time() . '_' . Str::random(10) . '.' . $request->file('document')->getClientOriginalExtension();
                $path = $request->file('document')->storeAs('lampiran_izin_cuti', $filename, 'public');
            }

            Leave::create([
                'karyawan_id' => Auth::id() ?? 1, // Fallback if no auth during testing
                'jenis' => $request->jenis,
                'jenis_detail' => $request->jenis_detail,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'keterangan' => $request->keterangan,
                'file_path' => $path,
                'status' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('laporan.cuti')
                ->with('success', 'Pengajuan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error submitting leave: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal mengajukan izin/cuti. Silakan coba lagi.');
        }
    }
}