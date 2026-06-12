<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('karyawan_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('absensi.laporan.laporan-izin-cuti', compact('leaves'));
    }
}
