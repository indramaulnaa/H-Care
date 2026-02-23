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
    
    // Dashboard Utama
    Route::get('/dashboard/dinkes', [DashboardController::class, 'indexDinkes'])->name('dashboard.dinkes');

    // Halaman Menu Sidebar
    Route::get('/dinkes/cuti', [DashboardController::class, 'pageCuti'])->name('dinkes.cuti');
    Route::get('/dinkes/pensiun', [DashboardController::class, 'pagePensiun'])->name('dinkes.pensiun');

    // Proses Verifikasi (Aksi Tombol)
    Route::post('/pensiun/verifikasi/{id}', [BerkasPensiunController::class, 'verifikasi'])->name('pensiun.verifikasi');
    Route::post('/cuti/verifikasi/{id}', [PengajuanCutiController::class, 'verifikasi'])->name('cuti.verifikasi');

    // Halaman Menu Sidebar Dinkes
    Route::get('/dinkes/cuti', [DashboardController::class, 'pageCuti'])->name('dinkes.cuti');
    Route::get('/dinkes/pensiun', [DashboardController::class, 'pagePensiun'])->name('dinkes.pensiun');
    
    // Rute Baru: Data Pegawai (Master)
    Route::get('/dinkes/pegawai', [DashboardController::class, 'pageDataPegawaiDinkes'])->name('dinkes.pegawai');

    // Route untuk membuka akses upload (PENTING)
    Route::post('/dinkes/pensiun/buka-akses/{id}', [DashboardController::class, 'bukaAksesPensiun'])->name('dinkes.buka_akses');


    // ------------------------------------------
    // B. AREA ADMIN PUSKESMAS
    // ------------------------------------------

    // Dashboard Utama
    Route::get('/dashboard/puskesmas', [DashboardController::class, 'indexPuskesmas'])->name('dashboard.puskesmas');
    
    // 1. Modul Pengajuan Cuti
    Route::get('/puskesmas/cuti', [DashboardController::class, 'pageCutiPuskesmas'])->name('puskesmas.cuti');
    Route::post('/cuti/store', [PengajuanCutiController::class, 'store'])->name('cuti.store'); // Simpan Cuti
    
    // 2. Modul Data Pegawai (Master Data - Tambah/Edit/Hapus)
    Route::get('/puskesmas/pegawai', [DashboardController::class, 'pageDataPegawai'])->name('puskesmas.pegawai');
    
    // Logic CRUD Pegawai
    Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');       // Tambah
    Route::put('/pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update'); // Edit
    Route::delete('/pegawai/delete/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.delete'); // Hapus

    // 3. Modul E-Pensiun (Monitoring 1 Tahun & Upload Berkas)
    Route::get('/puskesmas/pensiun', [DashboardController::class, 'pagePensiunPuskesmas'])->name('puskesmas.pensiun');
    Route::post('/pensiun/upload', [BerkasPensiunController::class, 'store'])->name('pensiun.store'); // Upload Berkas

});