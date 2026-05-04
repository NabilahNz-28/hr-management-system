<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */

        // Dashboard PIC - Inventory
    public function pic()
    {
        $aktivitasList = []; // sementara (atau ambil dari DB nanti)

        return view('dashboard.dashboard-pic', compact('aktivitasList'));
    }

    // Dashboard Absensi
    public function absensi()
    {
        return view('absensi.absensi.absen-masuk');
    }

    // Dashboard Selection
    public function selection()
    {
        return view('dashboard.dashboard-selection');
    }

    public function superadmin()
    {
        return view('dashboard.dashboard-superadmin');
    }

    /**
     * Attendance Report
     */
    public function attendanceReport()
    {
        return view('reports.attendance');
    }

    /**
     * Salary Report
     */
    public function salaryReport()
    {
        return view('reports.salary');
    }

    /**
     * Inventory Report
     */
    public function inventoryReport()
    {
        return view('reports.inventory');
    }

}
