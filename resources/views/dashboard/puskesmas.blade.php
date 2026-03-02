@extends('layouts.puskesmas')
@section('title', 'Dashboard')
@section('content')

    <style>
        /* Animasi Wave (Lambaian Tangan) */
        @keyframes wave-animation {
            0% { transform: rotate( 0.0deg) }
            10% { transform: rotate(14.0deg) }
            20% { transform: rotate(-8.0deg) }
            30% { transform: rotate(14.0deg) }
            40% { transform: rotate(-4.0deg) }
            50% { transform: rotate(10.0deg) }
            60% { transform: rotate( 0.0deg) }
            100% { transform: rotate( 0.0deg) }
        }
        .wave-emoji {
            display: inline-block;
            animation: wave-animation 2.5s infinite;
            transform-origin: 70% 70%;
        }

        /* Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            opacity: 0;
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        /* Delay bertahap agar munculnya bergantian seperti efek domino */
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        /* Efek Hover Kartu Statistik (Floating) */
        .stat-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border-radius: 16px !important;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        }
        .stat-card .icon-box {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .stat-card:hover .icon-box {
            transform: scale(1.15) rotate(8deg);
        }

        /* Gradient Premium untuk Kartu Pertama */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #084298 100%) !important;
        }

        /* Efek Hover List Item (Tabel Mini) */
        .hover-list-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .hover-list-item:hover {
            background-color: #f8fbff;
            transform: translateX(6px);
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
        }
        .hover-list-warning {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .hover-list-warning:hover {
            background-color: #fffcf2;
            transform: translateX(6px);
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark">Halo, {{ Auth::user()->name }}! <span class="wave-emoji">👋</span></h3>
            <p class="text-muted mb-0 mt-1">Selamat datang di Panel Admin <strong>{{ Auth::user()->nama_unit }}</strong>.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <div class="text-muted small fw-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            <div class="text-primary small"><i class="bi bi-clock-history"></i> Sistem Siap Digunakan</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4 animate-fade-up delay-1">
            <div class="stat-card card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-white bg-opacity-25 rounded-circle p-3 me-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0 lh-1" style="font-size: 2.5rem;">{{ $totalPegawai }}</h2>
                        <span class="text-white-50 fw-medium">Total Pegawai Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 animate-fade-up delay-2">
            <div class="stat-card card border-0 shadow-sm h-100" style="border-left: 6px solid #0dcaf0 !important;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle p-3 me-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-paper fs-2"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0 text-dark lh-1" style="font-size: 2.5rem;">{{ $cutiSaya }}</h2>
                        <span class="text-muted fw-medium">Total Pengajuan Cuti</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 animate-fade-up delay-3">
            <div class="stat-card card border-0 shadow-sm h-100" style="border-left: 6px solid #ffc107 !important;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-hourglass-split fs-2"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0 text-dark lh-1" style="font-size: 2.5rem;">{{ $pensiunTahunIni }}</h2>
                        <span class="text-muted fw-medium">Pensiun Tahun Ini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 animate-fade-up delay-4">
        
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 fs-5"><i class="bi bi-calendar-check text-primary me-2"></i>Cuti Terbaru</h6>
                    <a href="{{ route('puskesmas.cuti') }}" class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-medium transition-all">Lihat Semua</a>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @forelse($cutiTerbaru as $cuti)
                                <tr class="border-bottom hover-list-item">
                                    <td class="ps-3 py-3">
                                        <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $cuti->pegawai->nama_lengkap }}</div>
                                        <div class="text-muted" style="font-size: 12.5px;">{{ $cuti->jenis_cuti }}</div>
                                    </td>
                                    <td class="text-end py-3 pe-3">
                                        @if($cuti->status == 'menunggu')
                                            <span class="badge bg-warning text-dark bg-opacity-25 rounded-pill px-3 py-2 border border-warning border-opacity-25 shadow-sm">Pending</span>
                                        @elseif($cuti->status == 'disetujui')
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 border border-primary border-opacity-10 shadow-sm">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-10 shadow-sm">Ditolak</span>
                                        @endif
                                        <div class="text-muted mt-2" style="font-size: 11px; font-weight: 500;">
                                            <i class="bi bi-clock"></i> {{ $cuti->created_at->locale('id')->diffForHumans() }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-5 small bg-light rounded-3">
                                        <i class="bi bi-envelope-x fs-1 d-block mb-2 text-black-50"></i>
                                        Belum ada pengajuan cuti.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 fs-5"><i class="bi bi-person-dash text-warning me-2"></i>Pensiun Terdekat</h6>
                    <a href="{{ route('puskesmas.pensiun') }}" class="btn btn-sm btn-light text-warning rounded-pill px-3 fw-medium transition-all">Lihat Semua</a>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        @forelse($pensiunTerdekat as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $isBulanIni = $tglPensiun->isCurrentMonth() && $tglPensiun->isCurrentYear();
                            @endphp
                            <li class="list-group-item px-3 py-3 d-flex justify-content-between align-items-center border-bottom hover-list-warning">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $p->nama_lengkap }}</div>
                                    <div class="text-muted mt-1" style="font-size: 12px; font-family: monospace;">{{ $p->nip }}</div>
                                </div>
                                <div class="text-end">
                                    @if($isBulanIni)
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 shadow-sm pulse-animation">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $tglPensiun->translatedFormat('d F Y') }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-medium shadow-sm">
                                            {{ $tglPensiun->translatedFormat('d F Y') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 py-5 text-center text-muted small border-0 bg-light rounded-3 mt-2">
                                <i class="bi bi-shield-check fs-1 d-block mb-2 text-success opacity-50"></i>
                                Aman, tidak ada pegawai yang pensiun dalam waktu dekat.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection