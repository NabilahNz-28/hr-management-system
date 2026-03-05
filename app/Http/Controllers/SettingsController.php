<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function locationSettings()
    {
        return view('settings.lokasi');
    }
    
    public function updateLocation(Request $request)
    {
        return redirect()->back()->with('success', 'Lokasi berhasil diperbarui');
    }
    
    public function workHoursSettings()
    {
        return view('settings.jam-kerja');
    }
    
    public function updateWorkHours(Request $request)
    {
        return redirect()->back()->with('success', 'Jam kerja berhasil diperbarui');
    }
}