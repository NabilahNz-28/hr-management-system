<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;

// HOME REDIRECT
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'pic'        => redirect()->route('dashboard.selection'),
            'karyawan'   => redirect()->route('dashboard.absensi'),
            default      => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
})->name('home');

// GUEST
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// AUTH
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'absensi'])
        ->name('dashboard.absensi');

    Route::get('/selection', [DashboardController::class, 'selection'])
        ->name('dashboard.selection');

    Route::get('/dashboard/pic', [DashboardController::class, 'pic'])
        ->name('dashboard.pic');

    // SUPERADMIN
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard'])
        ->name('superadmin.dashboard');

    Route::post('/superadmin/store-user', [SuperadminController::class, 'storeUser'])
        ->name('superadmin.storeUser');

    // ABSENSI
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/masuk', fn () => view('absensi.absensi.absen-masuk'))->name('masuk');
        Route::get('/pulang', fn () => view('absensi.absensi.absen-pulang'))->name('pulang');
        Route::get('/pengajuan-izin', fn () => view('absensi.absensi.pengajuan-izin'))->name('pengajuan-izin');

        Route::get('/cuti', [LeaveController::class, 'create'])->name('cuti');
        Route::post('/cuti', [LeaveController::class, 'store'])->name('cuti.post');

        Route::post('/simpan', [AttendanceController::class, 'simpanAbsensi'])->name('simpan');
        Route::get('/riwayat', [AttendanceController::class, 'getRiwayat'])->name('riwayat');
    });

    // LAPORAN ABSENSI
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/absensi', fn () => view('absensi.laporan.laporan-absensi'))->name('absensi');
        Route::get('/terlambat', fn () => view('absensi.laporan.laporan-terlambat'))->name('terlambat');
        Route::get('/izin-cuti', [LaporanController::class, 'index'])->name('cuti');
    });

    // INVENTORY
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');

        Route::get('/tambah-barang', fn () => view('inventories.inventory.tambah-barang'))->name('tambah-barang');
        Route::get('/input-opname', fn () => view('inventories.inventory.input-opname'))->name('input-opname');
        Route::get('/stock-opname', fn () => view('inventories.inventory.stock-opname'))->name('stock-opname');
        Route::get('/transfer-stock', fn () => view('inventories.inventory.transfer-stock'))->name('transfer-stock');

        Route::get('/laporan-opname', fn () => view('inventories.laporan.laporan-opname'))->name('laporan-opname');
        Route::get('/laporan-transfer', fn () => view('inventories.laporan.laporan-transfer'))->name('laporan-transfer');
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
