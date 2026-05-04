<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\DashboardController;

// 1. SMART HOME REDIRECT
Route::get('/', function () {
    if (Auth::check()) {
        $userRole = Auth::user()->role;

        if ($userRole === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        elseif ($userRole === 'pic') {
            // NAMA ROUTE HARUS SAMA DENGAN YANG DI DEFINISIKAN DI BAWAH
            return redirect()->route('dashboard.selection');
        }
        elseif ($userRole === 'karyawan') {
            return redirect()->route('dashboard.absensi');
        }
    }
    return redirect()->route('login');
})->name('home');


// 2. GUEST ONLY (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


// 3. AUTHENTICATED ONLY (Wajib Login)
Route::middleware('auth')->group(function () {

    // --- ROUTE SUPERADMIN ---
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::post('/superadmin/store-user', [SuperadminController::class, 'storeUser'])->name('superadmin.storeUser');


    // --- ROUTE SELECTION (KHUSUS PIC) ---
    Route::get('/selection', [DashboardController::class, 'selection'])->name('dashboard.selection');


    // --- ROUTE INVENTORY (KHUSUS PIC) ---
    Route::get('/inventory', [DashboardController::class, 'pic'])->name('dashboard.pic');


    // --- ROUTE ABSENSI (KARYAWAN & PIC) ---
    Route::get('/dashboard', [DashboardController::class, 'absensi'])->name('dashboard.absensi');


    // --- MODUL ABSENSI & CUTI ---
    Route::prefix('absensi')->group(function () {
        // Asumsi file ini ada di folder "absensi/", kalau ada di folder lain tolong disesuaikan juga
        Route::get('/masuk', function () { return view('absensi.absen-masuk'); })->name('absensi.masuk');
        Route::get('/pulang', function () { return view('absensi.absen-pulang'); })->name('absensi.pulang');
        Route::get('/pengajuan-izin', function () { return view('absensi.pengajuan-izin'); })->name('absensi.pengajuan-izin');

        Route::get('/cuti', [LeaveController::class, 'create'])->name('absensi.cuti');
        Route::post('/cuti', [LeaveController::class, 'store'])->name('absensi.cuti.post');

        Route::post('/simpan', [AttendanceController::class, 'simpanAbsensi'])->name('absensi.simpan');
        Route::get('/riwayat', [AttendanceController::class, 'getRiwayat'])->name('absensi.riwayat');
    });

    // --- LAPORAN ---
    Route::get('/laporan/izin-cuti', [LaporanController::class, 'index'])->name('laporan.cuti');

    // --- LOGOUT ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
