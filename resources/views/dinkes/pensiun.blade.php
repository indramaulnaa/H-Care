@extends('layouts.admin')
@section('title', 'E-Pensiun Monitoring')
@section('content')

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }
        .btn-warning-glow { transition: all 0.3s ease; }
        .btn-warning-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(245, 158, 11, 0.3) !important; }

        /* Tabel Premium */
        .premium-table th { background-color: #f8fafc !important; color: #64748b !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 15px 20px !important; border-bottom: 2px solid #e2e8f0 !important; border-top: none !important; }
        .premium-table td { padding: 16px 20px !important; vertical-align: middle; border-bottom: 1px solid #f1f5f9 !important; color: #334155; }
        
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 3px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: translateX(4px); box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 3px solid #0d6efd; z-index: 10; position: relative; }

        /* Desain Lencana (Badges) Lembut */
        .badge-soft { padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.3px; display: inline-flex; align-items: center; gap: 5px; }
        .badge-soft-primary { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-soft-warning { background-color: #fefce8; color: #d97706; border: 1px solid #fde68a; }
        .badge-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-soft-info { background-color: #ecfeff; color: #0284c7; border: 1px solid #bae6fd; }
        .badge-soft-danger { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-soft-secondary { background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* Filter Kotak */
        .filter-box { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 25px; }
        .filter-input { background-color: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 0.85rem; padding: 8px 12px; box-shadow: none !important; transition: all 0.3s; }
        .filter-input:focus { background-color: white; border-color: #0d6efd; }

        /* Modal Kustom */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }
        .summary-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .summary-label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .summary-value { font-size: 0.95rem; font-weight: 600; color: #1e293b; }

        /* Radio Button Aksi Modal */
        .action-radio { display: none; }
        .action-label { display: block; width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; font-weight: 600; color: #64748b; background: white; }
        .action-radio:checked + .label-setuju { border-color: #10b981; background-color: #f0fdf4; color: #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15); }
        .action-radio:checked + .label-tolak { border-color: #ef4444; background-color: #fef2f2; color: #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15); }
        .action-content { display: none; margin-top: 15px; animation: fadeInUp 0.4s forwards; }
        
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); } 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); } }
        .pulse-btn { animation: pulse-ring 2s infinite; }

        /* Desain Modal Arsip Premium */
        .arsip-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 18px; text-decoration: none; transition: all 0.3s ease; }
        .arsip-card:hover { border-color: #cbd5e1; box-shadow: 0 8px 25px rgba(0,0,0,0.04); transform: translateY(-3px); }
        .arsip-icon-box { width: 48px; height: 48px; background-color: #fef2f2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; transition: all 0.3s; }
        .arsip-card:hover .arsip-icon-box { background-color: #ef4444; color: #ffffff; }
        .arsip-title { color: #1e293b; font-weight: 700; font-size: 0.95rem; margin-bottom: 2px; text-decoration: none !important; }
        .arsip-subtitle { color: #64748b; font-size: 0.8rem; margin: 0; text-decoration: none !important; }
        .btn-tutup-arsip { background-color: #64748b; color: #ffffff; border-radius: 50px; padding: 12px; font-weight: 600; border: none; transition: all 0.3s; width: 100%; box-shadow: 0 4px 10px rgba(100, 116, 139, 0.2); }
        .btn-tutup-arsip:hover { background-color: #475569; color: #ffffff; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(100, 116, 139, 0.3); }
    </style>

    <div class="d-flex justify-content-between align-items-end mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">E-Pensiun Monitoring</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola dan pantau data pensiun pegawai di seluruh unit Puskesmas Kabupaten Batang.</p>
        </div>
    </div>

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm animate-fade-up mb-4" style="border-radius: 12px; background-color: #f0fdf4; color: #16a34a;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    @if($pensiunBulanIniRealtime->count() > 0)
    <div class="alert border-0 shadow-sm mb-4 animate-fade-up" id="peringatanPensiunBox" style="background-color: #fffbeb; border-radius: 16px; position: relative;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" onclick="tutupPeringatan()" title="Tutup peringatan ini"></button>
        <div class="d-flex align-items-start gap-3 p-2">
            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="bi bi-bell-fill fs-4"></i>
            </div>
            <div class="w-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="fw-bold text-dark m-0">Perhatian: Pegawai Pensiun Bulan Ini</h5>
                    <span class="badge bg-warning text-dark shadow-sm rounded-pill">{{ $pensiunBulanIniRealtime->count() }} Pegawai</span>
                </div>
                <p class="text-muted small mb-3">Harap pantau kelengkapan dokumen pensiun dari Puskesmas terkait berikut:</p>
                <div class="d-flex flex-wrap gap-2 pe-4"> 
                    @foreach($pensiunBulanIniRealtime as $p)
                    <div class="bg-white border rounded-3 p-2 px-3 shadow-sm d-flex align-items-center gap-2 transition-all hover-scale" style="border-color: #fde68a !important;">
                        <i class="bi bi-person-circle text-warning fs-4"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $p->nama_lengkap }}</div>
                            <div class="text-muted d-flex align-items-center gap-2" style="font-size: 0.7rem;">
                                <span><i class="bi bi-hospital"></i> {{ $p->unit_kerja }}</span>
                                <span class="text-danger fw-bold"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-4 animate-fade-up delay-1">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-top: 4px solid #0d6efd !important;">
                <div class="card-body p-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Total Pensiun ({{ $filterTahun }})</div>
                    <h2 class="fw-bold m-0 text-primary">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-top: 4px solid #64748b !important;">
                <div class="card-body p-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Belum Upload</div>
                    <h2 class="fw-bold m-0" style="color: #475569;">{{ $stats['belum_upload'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-top: 4px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Menunggu Verifikasi</div>
                    <h2 class="fw-bold m-0 text-warning">{{ $stats['menunggu'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-top: 4px solid #10b981 !important;">
                <div class="card-body p-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Dokumen Lengkap</div>
                    <h2 class="fw-bold m-0 text-success">{{ $stats['lengkap'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-box animate-fade-up delay-2">
        <form action="{{ route('dinkes.pensiun') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-auto d-flex align-items-center gap-2 text-primary fw-bold" style="font-size: 0.9rem;">
                <i class="bi bi-funnel-fill fs-5"></i> Filter Data:
            </div>
            
            <div class="col-md-2">
                <select name="bulan" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $filterBulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="col-md-2">
                <select name="tahun" class="form-select filter-input" onchange="this.form.submit()">
                    @for($y=date('Y'); $y<=date('Y')+5; $y++)
                        <option value="{{ $y }}" {{ $filterTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <select name="unit" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($listUnitKerja as $unit)
                        <option value="{{ $unit }}" {{ $filterUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                    <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc;"><i class="bi bi-search text-muted"></i></button>
                </div>
            </div>

            <div class="col-md-auto ms-auto">
                <a href="{{ route('dinkes.pensiun') }}" class="btn btn-light shadow-sm text-secondary border" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table mb-0">
                    <thead>
                        <tr>
                            <th width="25%">Nama Pegawai & NIP</th>
                            <th width="15%">Unit Kerja</th>
                            <th width="15%">Tgl Lahir & Pensiun</th>
                            <th width="12%">Akses Dokumen</th>
                            <th width="18%">Status Berkas</th>
                            <th width="15%" class="text-center">Aksi / Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPensiun as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $berkas = $p->berkas_pensiun;
                            @endphp
                        <tr class="hover-row">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        {{ substr($p->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $p->nama_lengkap }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem; font-family: monospace; letter-spacing: 0.5px;">{{ $p->nip }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-hospital"></i> {{ $p->unit_kerja }}
                                </span>
                            </td>
                            
                            <td>
                                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 2px;">
                                    Lahir: <span class="text-dark fw-medium">{{ $p->tanggal_lahir->translatedFormat('d M Y') }}</span>
                                </div>
                                <div style="font-size: 0.8rem; color: #dc2626; font-weight: 600;">
                                    Pensiun: {{ $tglPensiun->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            
                            <td>
                                @if($p->is_pensiun_open)
                                    <span class="badge-soft badge-soft-success"><i class="bi bi-unlock-fill"></i> Terbuka</span>
                                @else
                                    <span class="badge-soft badge-soft-secondary"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                @endif
                            </td>

                            <td>
                                @if(!$berkas)
                                    <span class="badge-soft badge-soft-secondary">Belum Upload</span>
                                @elseif($berkas->status == 'menunggu_tahap_1')
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Review Tahap 1</span>
                                @elseif($berkas->status == 'ditolak_tahap_1')
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Revisi Tahap 1</span>
                                @elseif($berkas->status == 'lulus_tahap_1')
                                    <span class="badge-soft badge-soft-info"><i class="bi bi-cloud-arrow-up-fill"></i> Tunggu Tahap 2</span>
                                @elseif($berkas->status == 'menunggu_tahap_2')
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Review Tahap 2</span>
                                @elseif($berkas->status == 'ditolak_tahap_2')
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Revisi Tahap 2</span>
                                @elseif($berkas->status == 'disetujui')
                                    <span class="badge-soft badge-soft-success"><i class="bi bi-check-circle-fill"></i> Dokumen Lengkap</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(!$p->is_pensiun_open)
                                    <form action="{{ route('dinkes.buka_akses', $p->id) }}" method="POST">
                                        @csrf
                                        @if($p->is_request_open_access)
                                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold text-dark shadow-sm btn-warning-glow pulse-btn" style="border-radius: 8px; font-size: 0.8rem;" title="Puskesmas meminta akses">
                                                <i class="bi bi-key-fill"></i> Buka (Diminta!)
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold transition-all" style="border-radius: 8px; font-size: 0.8rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="bi bi-key-fill"></i> Buka Akses
                                            </button>
                                        @endif
                                    </form>
                                @else
                                    @if(!$berkas)
                                        <span class="text-muted fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-hourglass"></i> Menunggu Upload</span>
                                    @elseif($berkas->status == 'disetujui')
                                        <button class="btn btn-light btn-sm text-success w-100 fw-bold shadow-sm border border-success border-opacity-25" data-bs-toggle="modal" data-bs-target="#arsipModal{{ $berkas->id }}" style="border-radius: 8px; font-size: 0.8rem; background-color: #f0fdf4;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                            <i class="bi bi-folder-check"></i> Arsip Berkas
                                        </button>
                                    @elseif(in_array($berkas->status, ['menunggu_tahap_1', 'menunggu_tahap_2']))
                                        <button class="btn btn-primary btn-sm w-100 fw-bold shadow-sm btn-glow" data-bs-toggle="modal" data-bs-target="#verifModal{{ $berkas->id }}" style="border-radius: 8px; font-size: 0.8rem;">
                                            <i class="bi bi-shield-check"></i> Verifikasi
                                        </button>
                                    @else
                                        <button class="btn btn-outline-info btn-sm w-100 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#verifModal{{ $berkas->id }}" style="border-radius: 8px; font-size: 0.8rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                            <i class="bi bi-eye"></i> Detail Berkas
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-inbox text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Tidak ada data pegawai pensiun sesuai filter.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @foreach($dataPensiun as $p)
        @php $berkas = $p->berkas_pensiun; @endphp
        
        @if($berkas && $berkas->status != 'disetujui')
        <div class="modal fade" id="verifModal{{ $berkas->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <div>
                            @if(in_array($berkas->status, ['menunggu_tahap_1', 'menunggu_tahap_2']))
                                <h5 class="modal-title fw-bold m-0"><i class="bi bi-shield-check me-2"></i> Verifikasi Dokumen - @if($berkas->status == 'menunggu_tahap_1') Tahap 1 @else Tahap 2 @endif</h5>
                                <small class="text-white-50 mt-1 d-block">Tinjau kelengkapan dokumen pensiun dari Puskesmas.</small>
                            @else
                                <h5 class="modal-title fw-bold m-0"><i class="bi bi-file-earmark-text me-2"></i> Detail Dokumen Pensiun</h5>
                                <small class="text-white-50 mt-1 d-block">Lihat riwayat dokumen dan status saat ini.</small>
                            @endif
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white">
                        <div class="summary-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-10 pb-3 mb-3">
                                <span class="fw-bold text-primary fs-6"><i class="bi bi-person-vcard me-2"></i> Data Pegawai Pensiun</span>
                                @if(in_array($berkas->status, ['menunggu_tahap_1', 'menunggu_tahap_2']))
                                    <span class="badge-soft badge-soft-warning px-3">Menunggu Verifikasi</span>
                                @elseif($berkas->status == 'lulus_tahap_1')
                                    <span class="badge-soft badge-soft-info px-3">Menunggu Tahap 2</span>
                                @else
                                    <span class="badge-soft badge-soft-danger px-3">Revisi Dibutuhkan</span>
                                @endif
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6"><div class="summary-label">Nama Pegawai</div><div class="summary-value">{{ $p->nama_lengkap }}</div></div>
                                <div class="col-md-6"><div class="summary-label">NIP</div><div class="summary-value" style="font-family: monospace;">{{ $p->nip }}</div></div>
                                <div class="col-md-6"><div class="summary-label">Unit Puskesmas</div><div class="summary-value">{{ $p->unit_kerja }}</div></div>
                                <div class="col-md-6"><div class="summary-label">Tanggal Pensiun</div><div class="summary-value text-danger">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun)->translatedFormat('d M Y') }}</div></div>
                            </div>
                        </div>

                        @if($berkas->catatan_dinkes)
                            <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 mb-4 mt-4 rounded-4 p-3 shadow-sm">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Catatan Revisi yang Anda Berikan:</h6>
                                <p class="mb-0 text-dark" style="font-size: 0.9rem;">{{ $berkas->catatan_dinkes }}</p>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                            <h6 class="fw-bold text-dark m-0"><i class="bi bi-folder2-open text-primary me-1"></i> Dokumen Terlampir:</h6>
                        </div>

                        <div class="row g-3 mb-4">
                            @if($berkas->file_sk_cpns && $berkas->file_sk_pangkat)
                                <div class="col-6">
                                    <a href="{{ asset('storage/'.$berkas->file_sk_cpns) }}" target="_blank" class="btn btn-light border w-100 p-3 text-start shadow-sm hover-scale transition-all" style="border-radius: 12px;">
                                        <i class="bi bi-file-pdf fs-2 float-start me-3 text-danger"></i> 
                                        <div class="fw-bold text-dark">File 1</div><small class="text-muted">SK CPNS</small>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ asset('storage/'.$berkas->file_sk_pangkat) }}" target="_blank" class="btn btn-light border w-100 p-3 text-start shadow-sm hover-scale transition-all" style="border-radius: 12px;">
                                        <i class="bi bi-file-pdf fs-2 float-start me-3 text-danger"></i> 
                                        <div class="fw-bold text-dark">File 2</div><small class="text-muted">SK Pangkat Akhir</small>
                                    </a>
                                </div>
                            @endif

                            @if($berkas->file_karpeg)
                                <div class="col-12 mt-3">
                                    <a href="{{ asset('storage/'.$berkas->file_karpeg) }}" target="_blank" class="btn btn-light border border-info w-100 p-3 text-start shadow-sm hover-scale transition-all" style="border-radius: 12px; background-color: #f0f9ff;">
                                        <i class="bi bi-file-pdf fs-2 float-start me-3 text-danger"></i> 
                                        <div class="fw-bold text-dark">File 3 (Tahap Akhir)</div><small class="text-muted">Kartu Pegawai (Karpeg)</small>
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if(in_array($berkas->status, ['menunggu_tahap_1', 'menunggu_tahap_2']))
                            <form action="{{ route('pensiun.verifikasi', $berkas->id) }}" method="POST">
                                @csrf
                                <hr class="text-muted opacity-10 my-4">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-ui-checks-grid text-primary me-2"></i> Keputusan Verifikasi:</h6>
                                <div class="row g-3 mb-2">
                                    <div class="col-6">
                                        <input type="radio" name="aksi" value="setuju" id="setujuPensiun{{ $berkas->id }}" class="action-radio" onchange="toggleActionPensiun('{{ $berkas->id }}', 'setuju')">
                                        <label class="action-label label-setuju shadow-sm" for="setujuPensiun{{ $berkas->id }}">
                                            <i class="bi bi-check-circle-fill fs-4 d-block mb-1"></i> @if($berkas->status == 'menunggu_tahap_1') Luluskan Tahap 1 @else Setujui Sepenuhnya @endif
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" name="aksi" value="tolak" id="tolakPensiun{{ $berkas->id }}" class="action-radio" onchange="toggleActionPensiun('{{ $berkas->id }}', 'tolak')">
                                        <label class="action-label label-tolak shadow-sm" for="tolakPensiun{{ $berkas->id }}">
                                            <i class="bi bi-x-circle-fill fs-4 d-block mb-1"></i> Tolak & Minta Revisi
                                        </label>
                                    </div>
                                </div>

                                <div id="boxTolakPensiun{{ $berkas->id }}" class="action-content p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4">
                                    <label class="form-label fw-bold text-danger mb-3"><i class="bi bi-pencil-square me-1"></i> Catatan Kekurangan/Revisi</label>
                                    <textarea name="catatan" id="catatanTolakPensiun{{ $berkas->id }}" class="form-control bg-white shadow-sm border-danger border-opacity-50" rows="3" placeholder="Tuliskan dengan jelas letak kesalahan agar Puskesmas mudah memperbaiki..."></textarea>
                                </div>

                                <div class="mt-4 pt-3 text-end">
                                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold border text-secondary me-2" data-bs-dismiss="modal">Batalkan</button>
                                    <button type="submit" id="btnSubmitVerifPensiun{{ $berkas->id }}" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm btn-glow" disabled>Simpan Keputusan <i class="bi bi-send-fill ms-2"></i></button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-dark rounded-3 p-3 text-center mt-4 shadow-sm">
                                <i class="bi bi-info-circle-fill text-info fs-5 align-middle me-2"></i>
                                @if($berkas->status == 'lulus_tahap_1')
                                    Menunggu Puskesmas mengunggah dokumen Tahap 2 (Karpeg).
                                @else
                                    Menunggu Puskesmas memperbaiki dokumen sesuai catatan revisi.
                                @endif
                            </div>
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-light px-5 rounded-pill fw-bold border text-secondary shadow-sm" data-bs-dismiss="modal">Tutup Detail</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($berkas && $berkas->status == 'disetujui')
        <div class="modal fade" id="arsipModal{{ $berkas->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="modal-header text-white border-0 p-4" style="background-color: #10b981;">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-folder-check me-2"></i> Arsip Dokumen Pensiun</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white text-start">
                        
                        <div class="alert alert-success border-0 shadow-sm mb-3 rounded-3 d-flex align-items-center p-3" style="background-color: #f0fdf4; color: #16a34a;">
                            <i class="bi bi-check-circle-fill fs-5 me-2"></i> 
                            <span style="font-size: 0.9rem;">Seluruh proses pemberkasan pensiun telah disetujui.</span>
                        </div>

                        <div class="p-3 mb-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; background-color: #ffffff;">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">Ringkasan Pegawai</span>
                                <span class="badge-soft badge-soft-success border-0 px-3 py-1">Selesai</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Nama Pegawai</div>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->nama_lengkap }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">NIP</div>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem; font-family: monospace;">{{ $p->nip }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Unit Kerja</div>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->unit_kerja }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Pensiun</div>
                                    <div class="fw-bold text-danger" style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun)->translatedFormat('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ asset('storage/'.$berkas->file_sk_cpns) }}" target="_blank" class="arsip-card">
                                <div class="arsip-icon-box"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                <div>
                                    <div class="arsip-title">1. SK CPNS</div>
                                    <p class="arsip-subtitle">Klik untuk melihat file PDF</p>
                                </div>
                            </a>
                            <a href="{{ asset('storage/'.$berkas->file_sk_pangkat) }}" target="_blank" class="arsip-card">
                                <div class="arsip-icon-box"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                <div>
                                    <div class="arsip-title">2. SK Pangkat Terakhir</div>
                                    <p class="arsip-subtitle">Klik untuk melihat file PDF</p>
                                </div>
                            </a>
                            <a href="{{ asset('storage/'.$berkas->file_karpeg) }}" target="_blank" class="arsip-card">
                                <div class="arsip-icon-box"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                <div>
                                    <div class="arsip-title">3. Kartu Pegawai (Karpeg)</div>
                                    <p class="arsip-subtitle">Klik untuk melihat file PDF</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-1 bg-white">
                        <button type="button" class="btn-tutup-arsip" data-bs-dismiss="modal">Tutup Arsip</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if(sessionStorage.getItem('hidePeringatanPensiunDinkes') === 'true') {
                const box = document.getElementById('peringatanPensiunBox');
                if(box) box.style.display = 'none'; 
            }
        });

        function tutupPeringatan() {
            const box = document.getElementById('peringatanPensiunBox');
            if(box) {
                box.style.display = 'none';
                sessionStorage.setItem('hidePeringatanPensiunDinkes', 'true');
            }
        }

        function toggleActionPensiun(id, action) {
            const boxTolak = document.getElementById('boxTolakPensiun' + id);
            const btnSubmit = document.getElementById('btnSubmitVerifPensiun' + id);
            const catatan = document.getElementById('catatanTolakPensiun' + id);

            btnSubmit.disabled = false;

            if (action === 'setuju') {
                boxTolak.style.display = 'none';
                btnSubmit.className = 'btn btn-success px-5 rounded-pill fw-bold shadow-sm btn-glow';
                catatan.required = false;
                catatan.value = ''; 
            } else if (action === 'tolak') {
                boxTolak.style.display = 'block';
                btnSubmit.className = 'btn btn-danger px-5 rounded-pill fw-bold shadow-sm btn-glow';
                catatan.required = true;
            }
        }
    </script>
@endsection