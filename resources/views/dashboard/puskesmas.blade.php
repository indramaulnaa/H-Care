@extends('layouts.puskesmas')
@section('title', 'Dashboard')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold m-0 text-dark">Halo, {{ Auth::user()->name }}! 👋</h3>
            <p class="text-muted mb-0 mt-1">Selamat datang di Panel Admin <strong>{{ Auth::user()->nama_unit }}</strong>.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <div class="text-muted small fw-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            <div class="text-primary small"><i class="bi bi-clock-history"></i> Sistem Siap Digunakan</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded p-3 me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0">{{ $totalPegawai }}</h2>
                        <span class="small text-white-50">Total Pegawai Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
                        <i class="bi bi-envelope-paper fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0 text-dark">{{ $cutiSaya }}</h2>
                        <span class="small text-muted">Total Pengajuan Cuti</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold m-0 text-dark">{{ $pensiunTahunIni }}</h2>
                        <span class="small text-muted">Pensiun Tahun Ini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0"><i class="bi bi-calendar-check text-primary me-2"></i>Cuti Terbaru</h6>
                    <a href="{{ route('puskesmas.cuti') }}" class="btn btn-sm btn-light text-primary small">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @forelse($cutiTerbaru as $cuti)
                                <tr class="border-bottom">
                                    <td class="ps-0 py-3">
                                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $cuti->pegawai->nama_lengkap }}</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $cuti->jenis_cuti }}</div>
                                    </td>
                                    <td class="text-end py-3">
                                        @if($cuti->status == 'menunggu')
                                            <span class="badge bg-warning text-dark bg-opacity-25 rounded-pill px-3">Pending</span>
                                        @elseif($cuti->status == 'disetujui')
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Ditolak</span>
                                        @endif
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            {{ $cuti->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4 small">Belum ada pengajuan cuti.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0"><i class="bi bi-person-dash text-warning me-2"></i>Pensiun Terdekat</h6>
                    <a href="{{ route('puskesmas.pensiun') }}" class="btn btn-sm btn-light text-primary small">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($pensiunTerdekat as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $isBulanIni = $tglPensiun->isCurrentMonth() && $tglPensiun->isCurrentYear();
                            @endphp
                            <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->nama_lengkap }}</div>
                                    <div class="text-muted" style="font-size: 12px;">{{ $tglPensiun->format('d M Y') }}</div>
                                </div>
                                <div>
                                    @if($isBulanIni)
                                        <span class="badge bg-danger rounded-pill">Bulan Ini!</span>
                                    @else
                                        <span class="badge bg-light text-dark border rounded-pill">{{ $tglPensiun->diffInMonths(\Carbon\Carbon::now()) }} bln lagi</span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 py-4 text-center text-muted small border-0">Aman, tidak ada yang pensiun dekat-dekat ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection