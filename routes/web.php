<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AbsensiReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// HOME REDIRECT
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'superadmin' => redirect()->route('dashboard.superadmin'),
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
    // Backstop per-IP (selain throttle email+IP di controller)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1')->name('login.post');

    // Registrasi publik dinonaktifkan: akun dibuat oleh Superadmin (Data Karyawan).
    // Route GET dipertahankan agar link 'register' yang ada tidak rusak (redirect ke login).
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    // LUPA / RESET PASSWORD
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:6,1')->name('password.update');
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
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmin'])
        ->name('dashboard.superadmin');

    // Alias agar sidebar bisa pakai route('superadmin.dashboard')
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])
        ->name('superadmin.dashboard');

    // Karyawan
    Route::get('/superadmin/karyawan', [SuperadminController::class, 'karyawanIndex'])
        ->name('superadmin.karyawan.index');
    Route::get('/superadmin/karyawan/create', [SuperadminController::class, 'karyawanCreate'])
        ->name('superadmin.karyawan.create');
    Route::post('/superadmin/karyawan', [SuperadminController::class, 'karyawanStore'])
        ->name('superadmin.karyawan.store');
    Route::get('/superadmin/karyawan/{id}', [SuperadminController::class, 'karyawanShow'])
        ->name('superadmin.karyawan.show');
    Route::get('/superadmin/karyawan/{id}/edit', [SuperadminController::class, 'karyawanEdit'])
        ->name('superadmin.karyawan.edit');
    Route::put('/superadmin/karyawan/{id}', [SuperadminController::class, 'karyawanUpdate'])
        ->name('superadmin.karyawan.update');
    Route::delete('/superadmin/karyawan/{id}', [SuperadminController::class, 'karyawanDestroy'])
        ->name('superadmin.karyawan.destroy');
    Route::get('/superadmin/karyawan/{id}/attendance-data', [SuperadminController::class, 'karyawanAttendanceData'])
        ->name('superadmin.karyawan.attendance-data');

    // Approval
    Route::get('/superadmin/approval', [SuperadminController::class, 'approvalIzinCuti'])
        ->name('superadmin.approval.index');
    Route::post('/superadmin/approval-approve/{id}', [SuperadminController::class, 'approve'])
        ->name('superadmin.approval.approve');
    Route::post('/superadmin/approval-reject/{id}', [SuperadminController::class, 'reject'])
        ->name('superadmin.approval.reject');

    // Inventory superadmin
    Route::get('/superadmin/inventory', [SuperadminController::class, 'inventoryIndex'])
        ->name('superadmin.inventory.index');
    Route::get('/superadmin/inventory/export', [SuperadminController::class, 'exportInventoryExcel'])
        ->name('superadmin.inventory.export');
    Route::delete('/superadmin/inventory/batalkan', [SuperadminController::class, 'batalkanInventory'])
        ->name('superadmin.inventory.batalkan');

    Route::get('/superadmin/transfer', [SuperadminController::class, 'transferIndex'])
        ->name('superadmin.transfer.index');
    Route::get('/superadmin/transfer/export', [SuperadminController::class, 'exportTransferExcel'])
        ->name('superadmin.transfer.export');
    Route::delete('/superadmin/transfer/batalkan', [SuperadminController::class, 'batalkanTransfer'])
        ->name('superadmin.transfer.batalkan');

    // Profile superadmin
    Route::get('/superadmin/profile', [SuperadminController::class, 'profile'])
        ->name('superadmin.profile');
    Route::put('/superadmin/profile', [SuperadminController::class, 'updateProfile'])
        ->name('superadmin.profile.update');
    Route::put('/superadmin/profile/password', [SuperadminController::class, 'updatePassword'])
        ->name('superadmin.profile.password');

    Route::post('/superadmin/store-user', [SuperadminController::class, 'storeUser'])
        ->name('superadmin.storeUser');

    // ABSENSI
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/masuk', fn () => view('absensi.absensi.absen-masuk'))->name('masuk');
        Route::get('/pulang', fn () => view('absensi.absensi.absen-pulang'))->name('pulang');
        
        // PENGGUNAAN CONTROLLER UNTUK CUTI & IZIN
        Route::get('/pengajuan-izin', [LeaveController::class, 'createIzin'])->name('pengajuan-izin');
        Route::get('/cuti', [LeaveController::class, 'createCuti'])->name('cuti');
        Route::post('/cuti', [LeaveController::class, 'store'])->name('cuti.post');

        Route::post('/simpan', [AttendanceController::class, 'simpanAbsensi'])->name('simpan');
        Route::get('/riwayat', [AttendanceController::class, 'getRiwayat'])->name('riwayat');
        Route::get('/cek-hari-ini', [AttendanceController::class, 'checkTodayAttendance'])->name('cek');
    });

    // MONITORING ABSENSI
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/rekap-harian', [AbsensiReportController::class, 'harian'])->name('harian');
        Route::get('/rekap-bulanan', [AbsensiReportController::class, 'bulanan'])->name('bulanan');
    });

    // LAPORAN ABSENSI
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/absensi', [AbsensiReportController::class, 'laporan'])->name('absensi');
        Route::get('/izin-cuti', [LaporanController::class, 'index'])->name('cuti');
    });

    // PENGATURAN
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // INVENTORY
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');

        Route::get('/tambah-barang', [InventoryController::class, 'create'])->name('tambah-barang');
        Route::post('/tambah-barang', [InventoryController::class, 'store'])->name('tambah-barang.store');
        
        Route::get('/input-opname', [InventoryController::class, 'inputOpname'])->name('input-opname');
        Route::post('/input-opname', [InventoryController::class, 'simpanOpname'])->name('input-opname.store');
        
        Route::get('/stock-opname', [InventoryController::class, 'stockOpname'])->name('stock-opname');
        
        Route::get('/transfer-stock', [InventoryController::class, 'transferStock'])->name('transfer-stock');
        Route::post('/transfer-stock', [InventoryController::class, 'simpanTransfer'])->name('transfer-stock.store');

        Route::get('/laporan-opname', [InventoryController::class, 'laporanOpname'])->name('laporan-opname');
        Route::get('/laporan-opname/export', [InventoryController::class, 'exportOpnameExcel'])->name('laporan-opname.export');
        Route::delete('/laporan-opname/batalkan', [InventoryController::class, 'batalkanOpname'])->name('laporan-opname.batalkan');

        Route::get('/laporan-transfer', [InventoryController::class, 'laporanTransfer'])->name('laporan-transfer');
        Route::get('/laporan-transfer/export', [InventoryController::class, 'exportTransferExcel'])->name('laporan-transfer.export');
        Route::delete('/laporan-transfer/batalkan', [InventoryController::class, 'batalkanTransfer'])->name('laporan-transfer.batalkan');
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
