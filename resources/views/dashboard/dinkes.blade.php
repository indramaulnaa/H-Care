@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Dashboard Ringkasan</h3>
        <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Pegawai</small>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalPegawai }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Pensiun Th. Ini</small>
                        <h3 class="fw-bold mb-0 text-warning">{{ $totalPensiunTahunIni }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Verifikasi Cuti</small>
                        <h3 class="fw-bold mb-0 text-danger">{{ $cutiPending }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded p-3">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Verifikasi Pensiun</small>
                        <h3 class="fw-bold mb-0 text-info">{{ $berkasPending }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded p-3">
                        <i class="bi bi-archive fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-success mt-4 border-0 shadow-sm">
        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Info Sistem</h5>
        <p class="mb-0">Silakan gunakan <strong>Sidebar di sebelah kiri</strong> untuk mengakses menu Verifikasi Cuti dan Monitoring Data Pensiun secara terpisah.</p>
    </div>
@endsection