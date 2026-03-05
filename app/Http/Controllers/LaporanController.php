<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $leaves = Leave::with(['leaveType', 'documents'])
            ->where('employee_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $permissions = Permission::where('employee_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('laporan-izin-cuti', compact('leaves', 'permissions'));
    }
}