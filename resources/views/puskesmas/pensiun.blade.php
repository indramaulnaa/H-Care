@extends('layouts.puskesmas')
@section('title', 'E-Pensiun Monitoring')
@section('content')

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }
        
        .btn-info-glow { transition: all 0.3s ease; }
        .btn-info-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 202, 240, 0.3) !important; }

        /* Tabel Premium */
        .premium-table th { background-color: #f8fafc !important; color: #64748b !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 15px 20px !important; border-bottom: 2px solid #e2e8f0 !important; border-top: none !important; }
        .premium-table td { padding: 16px 20px !important; vertical-align: middle; border-bottom: 1px solid #f1f5f9 !important; color: #334155; }
        
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 3px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: translateX(4px); box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 3px solid #0d6efd; z-index: 10; position: relative; }

        /* Desain Lencana (Badges) Lembut */
        .badge-soft { padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.3px; display: inline-flex; align-items: center; gap: 5px; }
        .badge-soft-primary { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-soft-warning { background-color: #fefce8; color: #d97706; border: 1px solid #fde68a; }
        .badge-soft-info { background-color: #ecfeff; color: #4f46e5; border: 1px solid #c7d2fe; }
        .badge-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
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

        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(13, 110, 253, 0); } 100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); } }
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

        /* File Input Custom Style */
        .custom-file-input { background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 15px; width: 100%; color: #334155; font-size: 0.9rem; transition: all 0.3s ease; }
        .custom-file-input:focus, .custom-file-input:hover { border-color: #0d6efd; background-color: #eff6ff; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); }
        .custom-file-input::-webkit-file-upload-button { background: white; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; margin-right: 15px; color: #475569; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .custom-file-input::-webkit-file-upload-button:hover { background: #f1f5f9; border-color: #94a3b8; }
    </style>

    <div class="d-flex justify-content-between align-items-end mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">E-Pensiun Monitoring</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola dan pantau pengajuan pensiun pegawai di <strong>{{ Auth::user()->nama_unit }}</strong>.</p>
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
                    <h5 class="fw-bold text-dark m-0">Perhatian: Pegawai Akan Pensiun Bulan Ini</h5>
                    <span class="badge bg-warning text-dark shadow-sm rounded-pill">{{ $pensiunBulanIniRealtime->count() }} Pegawai</span>
                </div>
                <p class="text-muted small mb-3">Segera lengkapi unggahan dokumen persyaratan pensiun untuk pegawai berikut:</p>
                <div class="d-flex flex-wrap gap-2 pe-4"> 
                    @foreach($pensiunBulanIniRealtime as $p)
                    <div class="bg-white border rounded-3 p-2 px-3 shadow-sm d-flex align-items-center gap-2 transition-all hover-scale" style="border-color: #fde68a !important;">
                        <i class="bi bi-person-circle text-warning fs-4"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $p->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size: 0.7rem; font-family: monospace;">NIP: {{ $p->nip }}</div>
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
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Pensiun ({{ $filterTahun ?? date('Y') }})</div>
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
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Diproses Dinkes</div>
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
        <form action="{{ route('puskesmas.pensiun') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-auto d-flex align-items-center gap-2 text-primary fw-bold" style="font-size: 0.9rem;">
                <i class="bi bi-funnel-fill fs-5"></i> Filter Data:
            </div>
            
            <div class="col-md-2">
                <select name="bulan" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="col-md-2">
                <select name="tahun" class="form-select filter-input" onchange="this.form.submit()">
                    @for($y=date('Y'); $y<=date('Y')+5; $y++)
                        <option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama / NIP Pegawai..." value="{{ $search ?? '' }}">
                    <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc;"><i class="bi bi-search text-muted"></i></button>
                </div>
            </div>

            <div class="col-md-auto ms-auto">
                <a href="{{ route('puskesmas.pensiun') }}" class="btn btn-light shadow-sm text-secondary border" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
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
                            <th class="ps-4" width="30%">Nama & NIP</th>
                            <th width="15%">Usia Saat Ini</th>
                            <th width="20%">Tgl Lahir & Pensiun</th>
                            <th width="20%">Status Dokumen</th>
                            <th class="text-center pe-4" width="15%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($dataPensiun as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $berkas = $p->berkas_pensiun;
                                $usiaSekarang = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
                            @endphp
                        <tr class="hover-row">
                            <td class="ps-4 py-3">
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
                                <span class="badge-soft badge-soft-secondary border-0 bg-light" style="font-size: 0.8rem;">
                                    {{ $usiaSekarang }} Tahun
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
                                @if(!$berkas)
                                    <span class="badge-soft badge-soft-secondary">Belum Upload</span>
                                @elseif($berkas->status == 'menunggu_tahap_1')
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Review Dinkes (T1)</span>
                                @elseif($berkas->status == 'ditolak_tahap_1')
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Revisi Tahap 1</span>
                                @elseif($berkas->status == 'lulus_tahap_1')
                                    <span class="badge-soft badge-soft-info"><i class="bi bi-cloud-arrow-up-fill"></i> Wajib Upload T2</span>
                                @elseif($berkas->status == 'menunggu_tahap_2')
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Review Dinkes (T2)</span>
                                @elseif($berkas->status == 'ditolak_tahap_2')
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Revisi Tahap 2</span>
                                @elseif($berkas->status == 'disetujui')
                                    <span class="badge-soft badge-soft-success"><i class="bi bi-check-circle-fill"></i> Selesai Total</span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                @if(!$p->is_pensiun_open)
                                    @if($p->is_request_open_access)
                                        <button class="btn btn-warning btn-sm w-100 fw-bold text-dark border-0" style="background-color: #fef3c7; border-radius: 8px;" disabled>
                                            <i class="bi bi-hourglass"></i> Menunggu Dinkes
                                        </button>
                                    @else
                                        <form action="{{ route('puskesmas.request_akses', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold shadow-sm transition-all" style="border-radius: 8px;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="bi bi-bell-fill"></i> Minta Akses
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if(!$berkas || $berkas->status == 'ditolak_tahap_1')
                                        <button class="btn btn-primary btn-sm w-100 fw-bold shadow-sm btn-glow pulse-btn" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}" style="border-radius: 8px;">
                                            <i class="bi bi-upload"></i> Upload Tahap 1
                                        </button>
                                    @elseif($berkas->status == 'lulus_tahap_1' || $berkas->status == 'ditolak_tahap_2')
                                        <button class="btn btn-info text-white btn-sm w-100 fw-bold shadow-sm btn-info-glow pulse-btn" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}" style="border-radius: 8px;">
                                            <i class="bi bi-upload"></i> Upload Tahap 2
                                        </button>
                                    @elseif($berkas->status == 'menunggu_tahap_1' || $berkas->status == 'menunggu_tahap_2')
                                        <span class="text-muted fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-gear-wide-connected"></i> Sedang Dicek...</span>
                                    @elseif($berkas->status == 'disetujui')
                                        <button class="btn btn-light btn-sm text-success w-100 fw-bold shadow-sm border border-success border-opacity-25" data-bs-toggle="modal" data-bs-target="#arsipModal{{ $p->id }}" style="border-radius: 8px; background-color: #f0fdf4;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                            <i class="bi bi-folder-check"></i> Lihat Arsip
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-folder-x text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Tidak ada data pegawai pensiun yang sesuai filter.</span>
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
        
        @if($p->is_pensiun_open && (!$berkas || in_array($berkas->status, ['ditolak_tahap_1', 'lulus_tahap_1', 'ditolak_tahap_2'])))
        <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <div>
                            <h5 class="modal-title fw-bold m-0"><i class="bi bi-cloud-arrow-up-fill me-2"></i> Form Upload Berkas</h5>
                            <small class="opacity-75 d-block mt-1">@if(!$berkas || $berkas->status == 'ditolak_tahap_1') Dokumen Tahap 1 (Syarat Dasar) @else Dokumen Tahap 2 (Syarat Akhir) @endif</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('pensiun.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pegawai" value="{{ $p->id }}">
                        <div class="modal-body p-4 text-start bg-white">
                            
                            <div class="p-3 mb-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; background-color: #f8fafc;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center shadow-sm border border-primary border-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $p->nama_lengkap }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem; font-family: monospace;">NIP: {{ $p->nip }}</div>
                                    </div>
                                    <div>
                                        <span class="badge-soft badge-soft-primary px-3">Upload PDF</span>
                                    </div>
                                </div>
                            </div>

                            @if($berkas && ($berkas->status == 'lulus_tahap_1' || $berkas->status == 'ditolak_tahap_2'))
                                <div class="alert alert-success border-0 bg-success bg-opacity-10 mb-3 rounded-3 d-flex align-items-center p-3 shadow-sm">
                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> 
                                    <span class="text-dark" style="font-size: 0.9rem;">Tahap 1 <strong>Lulus Verifikasi</strong>. Silakan unggah berkas terakhir Anda.</span>
                                </div>
                            @endif

                            @if($berkas && ($berkas->status == 'ditolak_tahap_1' || $berkas->status == 'ditolak_tahap_2'))
                                <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 mb-3 rounded-4 p-3 shadow-sm">
                                    <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Catatan Revisi dari Dinkes:</h6>
                                    <p class="mb-0 text-dark" style="font-size: 0.9rem;">
                                        {{ $berkas->catatan_dinkes ?? 'Tidak ada catatan spesifik. Silakan periksa kembali kelengkapan dokumen Anda.' }}
                                    </p>
                                </div>
                            @endif

                            <div class="alert border-0 d-flex align-items-center p-3 mb-4 shadow-sm" style="background-color: #eff6ff; border-radius: 12px;">
                                <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                                <div style="font-size: 0.85rem; color: #1e3a8a;">
                                    Pastikan file berformat <strong>.PDF</strong> dengan ukuran maksimal <strong class="text-danger">2 MB</strong> per file agar sistem dapat menerimanya.
                                </div>
                            </div>

                            @if(!$berkas || $berkas->status == 'ditolak_tahap_1')
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill me-1"></i> 1. File SK CPNS <span class="text-danger fw-normal" style="font-size: 0.7rem;">(Maks 2MB)</span></label>
                                    <input type="file" name="file_sk_cpns" class="custom-file-input" required accept="application/pdf">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill me-1"></i> 2. File SK Pangkat Terakhir <span class="text-danger fw-normal" style="font-size: 0.7rem;">(Maks 2MB)</span></label>
                                    <input type="file" name="file_sk_pangkat" class="custom-file-input" required accept="application/pdf">
                                </div>
                            @else
                                <div class="mb-2">
                                    <label class="form-label fw-bold text-info mb-2" style="color: #0284c7 !important; font-size: 0.85rem;"><i class="bi bi-file-earmark-pdf-fill me-1"></i> 3. Kartu Pegawai (Karpeg) <span class="text-danger fw-normal" style="font-size: 0.7rem;">(Maks 2MB)</span></label>
                                    <input type="file" name="file_karpeg" class="custom-file-input" style="border-color: #bae6fd; background-color: #f0f9ff;" required accept="application/pdf">
                                </div>
                            @endif
                            
                        </div>
                        <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 20px 20px;">
                            <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-glow">Unggah Berkas <i class="bi bi-cloud-arrow-up-fill ms-1"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($berkas && $berkas->status == 'disetujui')
        <div class="modal fade" id="arsipModal{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="modal-header text-white border-0 p-4" style="background-color: #10b981;">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-folder-check me-2"></i> Arsip Dokumen Pensiun</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white text-start">
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 1.2rem;">
                                <i class="bi bi-check"></i>
                            </div>
                            <div style="font-size: 0.9rem; color: #334155; line-height: 1.5;">
                                Proses pensiun <strong class="text-dark">{{ $p->nama_lengkap }}</strong> telah tuntas sepenuhnya.<br>Berikut arsip berkas yang telah disetujui.
                            </div>
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
            if(sessionStorage.getItem('hidePeringatanPensiun') === 'true') {
                const box = document.getElementById('peringatanPensiunBox');
                if(box) box.style.display = 'none'; 
            }
        });
        function tutupPeringatan() {
            const box = document.getElementById('peringatanPensiunBox');
            if(box) {
                box.style.display = 'none';
                sessionStorage.setItem('hidePeringatanPensiun', 'true');
            }
        }
    </script>
@endsection