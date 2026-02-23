@extends('layouts.puskesmas')
@section('title', 'Dashboard')
@section('content')
    <h4 class="fw-bold mb-4">Dashboard Puskesmas</h4>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-primary text-white">
                <div class="card-body">
                    <h3>{{ $totalPegawai }}</h3>
                    <span>Total Pegawai</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h3 class="text-success">{{ $cutiSaya }}</h3>
                    <span class="text-muted">Riwayat Pengajuan Cuti</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h3 class="text-warning">{{ $pensiunTahunIni }}</h3>
                    <span class="text-muted">Pensiun Tahun Ini</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle-fill"></i> Selamat datang! Silakan gunakan menu di samping kiri untuk:
        <ul class="mb-0 mt-2">
            <li>Mengajukan cuti pegawai.</li>
            <li><strong>Menambah data pegawai baru</strong> dan memantau pensiun.</li>
        </ul>
    </div>
@endsection