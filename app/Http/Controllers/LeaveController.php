<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    public function create()
    {
        $leaveTypes = LeaveType::where('type_code', 'like', 'leave_%')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
        
        return view('pengajuan-cuti', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_type_id' => 'required|exists:leave_types,id',
            'reason' => 'required|string|min:10|max:2000',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Cek jenis cuti
            $leaveType = LeaveType::findOrFail($request->leave_type_id);
            
            // Hitung total hari
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $totalDays = $startDate->diff($endDate)->days + 1;

            // Validasi maksimal hari
            if ($leaveType->max_days > 0 && $totalDays > $leaveType->max_days) {
                return back()->withInput()->withErrors([
                    'end_date' => "Jenis cuti {$leaveType->name} maksimal {$leaveType->max_days} hari"
                ]);
            }

            // Validasi dokumen untuk cuti tertentu
            if ($leaveType->requires_document && (!$request->hasFile('documents') || count($request->file('documents')) === 0)) {
                return back()->withInput()->withErrors([
                    'documents' => "Jenis cuti {$leaveType->name} membutuhkan dokumen pendukung"
                ]);
            }

            // Generate nomor cuti
            $year = date('Y');
            $count = Leave::whereYear('created_at', $year)->count() + 1;
            $leaveNumber = 'CUTI-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Simpan data cuti
            $leave = Leave::create([
                'leave_number' => $leaveNumber,
                'employee_id' => Auth::id(),
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            // Upload dokumen jika ada
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $filename = 'leave_' . $leave->id . '_' . time() . '_' . Str::random(10) . '.' . $document->getClientOriginalExtension();
                    $path = $document->storeAs('leave_documents', $filename, 'public');
                    
                    LeaveDocument::create([
                        'leave_id' => $leave->id,
                        'document_name' => $document->getClientOriginalName(),
                        'document_path' => $path,
                        'document_type' => $document->getMimeType(),
                        'file_size' => $document->getSize(),
                    ]);
                }
            }

            DB::commit();

            // Redirect ke halaman laporan dengan pesan sukses
            return redirect()->route('laporan.izin-cuti')
                ->with('success', 'Pengajuan cuti berhasil dikirim! Nomor cuti: ' . $leaveNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error submitting leave: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal mengajukan cuti. Silakan coba lagi.');
        }
    }
}