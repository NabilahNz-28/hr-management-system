<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengecek status login
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// 1. SMART HOME & REDIRECT
// =====================
Route::get('/', function () {
    // Mengecek apakah user sudah memiliki session login yang valid
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    // Jika belum login, arahkan ke form login
    return redirect()->route('login');
})->name('home');


// =====================
// 2. GUEST ONLY (Hanya bisa diakses kalau BELUM login)
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


// =====================
// 3. AUTHENTICATED ONLY (Wajib login untuk akses modul HR)
// =====================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // ABSENSI & CUTI (Modul Utama)
    Route::prefix('absensi')->group(function () {
        // Tampilan UI
        Route::get('/masuk', function () { return view('absensi.absen-masuk'); })->name('absensi.masuk');
        Route::get('/pulang', function () { return view('absensi.absen-pulang'); })->name('absensi.pulang');
        Route::get('/pengajuan-izin', function () { return view('absensi.pengajuan-izin'); })->name('absensi.pengajuan-izin');

        // Logic API & Database
        Route::get('/cuti', [LeaveController::class, 'create'])->name('absensi.cuti');
        Route::post('/cuti', [LeaveController::class, 'store'])->name('absensi.cuti.post');
        Route::post('/simpan', [AttendanceController::class, 'simpanAbsensi'])->name('absensi.simpan');
        Route::get('/riwayat', [AttendanceController::class, 'getRiwayat'])->name('absensi.riwayat');
    });

    // LAPORAN
    Route::get('/laporan/izin-cuti', [LaporanController::class, 'index'])->name('laporan.cuti');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// =====================
// TEST PAGE (Debugging)
// =====================
Route::get('/test', fn() => "TEST PAGE WORKS!");
