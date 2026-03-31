<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BerkasPensiunController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Middleware\RoleMiddleware; // <-- Memanggil file keamanan baru

// ====================================================
// 1. HALAMAN PUBLIK
// ====================================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ====================================================
// 2. HALAMAN KHUSUS ADMIN (Wajib Login)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // ----------------------------------------------------
    // A. AREA ADMIN DINKES (Hanya untuk role: admin_dinkes)
    // ----------------------------------------------------
    Route::middleware([RoleMiddleware::class.':admin_dinkes'])->group(function () {
        
        Route::get('/dashboard/dinkes', [DashboardController::class, 'indexDinkes'])->name('dashboard.dinkes');
        Route::get('/dinkes/cuti', [DashboardController::class, 'pageCuti'])->name('dinkes.cuti');
        Route::get('/dinkes/pensiun', [DashboardController::class, 'pagePensiun'])->name('dinkes.pensiun');
        Route::get('/dinkes/pegawai', [DashboardController::class, 'pageDataPegawaiDinkes'])->name('dinkes.pegawai');

        Route::post('/pensiun/verifikasi/{id}', [BerkasPensiunController::class, 'verifikasi'])->name('pensiun.verifikasi');
        Route::post('/cuti/tandai-diproses/{id}', [PengajuanCutiController::class, 'tandaiDiproses'])->name('cuti.diproses');
        Route::post('/cuti/verifikasi/{id}', [PengajuanCutiController::class, 'verifikasi'])->name('cuti.verifikasi');
        Route::post('/dinkes/pensiun/buka-akses/{id}', [DashboardController::class, 'bukaAksesPensiun'])->name('dinkes.buka_akses');

        // Manajemen Akun Puskesmas (Dinkes)
        Route::get('/dinkes/akun-puskesmas', [App\Http\Controllers\AkunPuskesmasController::class, 'index'])->name('dinkes.akun');
        Route::post('/dinkes/akun-puskesmas/store', [App\Http\Controllers\AkunPuskesmasController::class, 'store'])->name('akun.store');
        Route::put('/dinkes/akun-puskesmas/update/{id}', [App\Http\Controllers\AkunPuskesmasController::class, 'update'])->name('akun.update');
        Route::delete('/dinkes/akun-puskesmas/delete/{id}', [App\Http\Controllers\AkunPuskesmasController::class, 'destroy'])->name('akun.delete');
    });


    // ----------------------------------------------------
    // B. AREA ADMIN PUSKESMAS (Hanya untuk role: admin_puskesmas)
    // ----------------------------------------------------
    Route::middleware([RoleMiddleware::class.':admin_puskesmas'])->group(function () {
        
        Route::get('/dashboard/puskesmas', [DashboardController::class, 'indexPuskesmas'])->name('dashboard.puskesmas');
        
        Route::get('/puskesmas/cuti', [DashboardController::class, 'pageCutiPuskesmas'])->name('puskesmas.cuti');
        Route::post('/cuti/store', [PengajuanCutiController::class, 'store'])->name('cuti.store');
        Route::delete('/cuti/delete/{id}', [PengajuanCutiController::class, 'destroy'])->name('cuti.destroy');
        
        Route::get('/puskesmas/pegawai', [DashboardController::class, 'pageDataPegawai'])->name('puskesmas.pegawai');
        Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::put('/pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/delete/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.delete');

        Route::get('/puskesmas/pensiun', [DashboardController::class, 'pagePensiunPuskesmas'])->name('puskesmas.pensiun');
        Route::post('/pensiun/upload', [BerkasPensiunController::class, 'store'])->name('pensiun.store');
        Route::post('/puskesmas/pensiun/request-akses/{id}', [DashboardController::class, 'requestBukaAksesPensiun'])->name('puskesmas.request_akses');
    });

});