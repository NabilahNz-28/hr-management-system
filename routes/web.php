<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home (public)
Route::get('/', function () {
    return "WELCOME HR SYSTEM - <a href='/login'>Login</a> | <a href='/register'>Register</a>";
})->name('home');

// Auth (public)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================
// ABSENSI (PUBLIC dulu buat test UI)
// URL: /absensi/masuk, /absensi/pulang, /absensi/pengajuan-izin
// =====================
Route::prefix('absensi')->group(function () {

    Route::get('/masuk', function () {
        return view('absensi.absen-masuk');
    })->name('absensi.masuk');

    Route::get('/pulang', function () {
        return view('absensi.absen-pulang');
    })->name('absensi.pulang');

    Route::get('/pengajuan-izin', function () {
        return view('absensi.absensi.pengajuan-izin');
    })->name('absensi.pengajuan-izin');

});

// Test (public)
Route::get('/test', function () {
    return "TEST PAGE WORKS!";
});

// =====================
// ROUTE BUTUH LOGIN
// =====================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Cuti (butuh login)
    Route::get('/absensi/cuti', [LeaveController::class, 'create'])->name('absensi.cuti');
    Route::post('/absensi/cuti', [LeaveController::class, 'store'])->name('absensi.cuti.post');

    // Attendance API (butuh login)
    Route::post('/absensi/simpan', [AttendanceController::class, 'simpanAbsensi'])->name('absensi.simpan');
    Route::get('/absensi/riwayat', [AttendanceController::class, 'getRiwayat'])->name('absensi.riwayat');

    // Laporan (butuh login)
    Route::get('/laporan/izin-cuti', [LaporanController::class, 'index'])->name('laporan.cuti');

});
