<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BerkasPensiunController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\PegawaiController;

// ====================================================
// 1. HALAMAN PUBLIK (Bisa diakses siapa saja)
// ====================================================

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ====================================================
// 2. HALAMAN KHUSUS ADMIN (Harus Login Dulu)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // ------------------------------------------
    // A. AREA ADMIN DINAS KESEHATAN (DINKES)
    // ------------------------------------------
    
    // Dashboard & Menu Sidebar Dinkes
    Route::get('/dashboard/dinkes', [DashboardController::class, 'indexDinkes'])->name('dashboard.dinkes');
    Route::get('/dinkes/cuti', [DashboardController::class, 'pageCuti'])->name('dinkes.cuti');
    Route::get('/dinkes/pensiun', [DashboardController::class, 'pagePensiun'])->name('dinkes.pensiun');
    Route::get('/dinkes/pegawai', [DashboardController::class, 'pageDataPegawaiDinkes'])->name('dinkes.pegawai');

    // Aksi / Proses Dinkes
    Route::post('/pensiun/verifikasi/{id}', [BerkasPensiunController::class, 'verifikasi'])->name('pensiun.verifikasi');
    Route::post('/cuti/verifikasi/{id}', [PengajuanCutiController::class, 'verifikasi'])->name('cuti.verifikasi');
    Route::post('/dinkes/pensiun/buka-akses/{id}', [DashboardController::class, 'bukaAksesPensiun'])->name('dinkes.buka_akses');


    // ------------------------------------------
    // B. AREA ADMIN PUSKESMAS
    // ------------------------------------------

    // Dashboard Puskesmas
    Route::get('/dashboard/puskesmas', [DashboardController::class, 'indexPuskesmas'])->name('dashboard.puskesmas');
    
    // 1. Modul Pengajuan Cuti (Puskesmas)
    Route::get('/puskesmas/cuti', [DashboardController::class, 'pageCutiPuskesmas'])->name('puskesmas.cuti');
    Route::post('/cuti/store', [PengajuanCutiController::class, 'store'])->name('cuti.store');
    Route::delete('/cuti/delete/{id}', [PengajuanCutiController::class, 'destroy'])->name('cuti.destroy');
    
    // 2. Modul Data Pegawai (Puskesmas - Master Data)
    Route::get('/puskesmas/pegawai', [DashboardController::class, 'pageDataPegawai'])->name('puskesmas.pegawai');
    Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/delete/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.delete');

    // 3. Modul E-Pensiun (Puskesmas)
    Route::get('/puskesmas/pensiun', [DashboardController::class, 'pagePensiunPuskesmas'])->name('puskesmas.pensiun');
    Route::post('/pensiun/upload', [BerkasPensiunController::class, 'store'])->name('pensiun.store');
    Route::post('/puskesmas/pensiun/request-akses/{id}', [DashboardController::class, 'requestBukaAksesPensiun'])->name('puskesmas.request_akses');

});