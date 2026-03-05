<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        return view('dashboard.index');
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