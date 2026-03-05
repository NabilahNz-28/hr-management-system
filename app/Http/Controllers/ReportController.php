<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dailyReport()
    {
        return view('rekap.harian');
    }
    
    public function monthlyReport()
    {
        return view('rekap.bulanan');
    }
    
    public function attendanceReport()
    {
        return view('laporan.absensi');
    }
    
    public function lateReport()
    {
        return view('laporan.keterlambatan');
    }
    
    public function leavePermissionReport()
    {
        return view('laporan.izin-cuti');
    }
}