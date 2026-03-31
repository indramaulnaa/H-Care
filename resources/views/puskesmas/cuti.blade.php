@extends('layouts.puskesmas')
@section('title', 'Riwayat Pengajuan Cuti')
@section('content')

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }
        
        .btn-export-glow { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; transition: all 0.3s ease; }
        .btn-export-glow:hover { color: white; transform: translateY(-3px); box-shadow: 0 8px 15px rgba(16, 185, 129, 0.3) !important; }

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
        .badge-soft-danger { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-soft-secondary { background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* Filter Kotak */
        .filter-box { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 25px; }
        .filter-input { background-color: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 0.85rem; padding: 8px 12px; box-shadow: none !important; transition: all 0.3s; }
        .filter-input:focus { background-color: white; border-color: #0d6efd; }

        /* Modal & Form Modern */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }

        .form-section { background-color: #fff; border-radius: 16px; padding: 25px; margin-bottom: 20px; border: 1px solid #e2e8f0; transition: all 0.3s; }
        .form-section:hover { border-color: #bfdbfe; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.05); }
        .form-section-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 15px; color: #1e293b; display: flex; align-items: center; gap: 8px; }
        
        .modern-input-wrapper { display: flex; align-items: center; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 15px; transition: all 0.3s ease; }
        .modern-input-wrapper:focus-within { background-color: #ffffff; border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); }
        .modern-input-wrapper input, .modern-input-wrapper select, .modern-input-wrapper textarea { border: none; background: transparent; box-shadow: none !important; padding: 5px 0; width: 100%; color: #334155; font-weight: 500; outline: none; }
        .readonly-input { background-color: #f1f5f9 !important; color: #64748b !important; }

        /* Upload Zone */
        .upload-zone { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px 20px; text-align: center; background-color: #f8fafc; cursor: pointer; transition: all 0.3s; }
        .upload-zone:hover { background-color: #f0fdfa; border-color: #10b981; }
        .upload-icon { font-size: 3.5rem; color: #94a3b8; margin-bottom: 10px; transition: all 0.3s; }
        .upload-zone:hover .upload-icon { color: #10b981; transform: scale(1.1); }
        #fileInput { display: none; }

        /* Autocomplete Custom */
        .custom-autocomplete-wrapper { position: relative; }
        .custom-autocomplete-list { position: absolute; top: 100%; left: 0; z-index: 1050; width: 100%; max-height: 200px; overflow-y: auto; background-color: #fff; border: 1px solid #bfdbfe; border-top: none; border-radius: 0 0 10px 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: none; }
        .pegawai-option { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background-color 0.2s; }
        .pegawai-option:last-child { border-bottom: none; }
        .pegawai-option:hover { background-color: #eff6ff; padding-left: 20px; }
        
        .summary-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .summary-label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .summary-value { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Riwayat Pengajuan Cuti</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Pantau dan kelola riwayat pengajuan cuti pegawai di unit Anda.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm btn-glow fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalCuti">
            <i class="bi bi-plus-lg me-2 fs-5"></i> Buat Pengajuan
        </button>
    </div>

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm animate-fade-up mb-4" style="border-radius: 12px; background-color: #f0fdf4; color: #16a34a;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div> 
    @endif
    @if($errors->any()) 
        <div class="alert alert-danger border-0 shadow-sm animate-fade-up mb-4" style="border-radius: 12px; background-color: #fef2f2; color: #dc2626;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="filter-box animate-fade-up delay-1">
        <form action="{{ route('puskesmas.cuti') }}" method="GET">
            <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
                
                <div class="d-flex align-items-center gap-2 text-primary fw-bold flex-shrink-0" style="font-size: 0.95rem;">
                    <i class="bi bi-funnel-fill fs-5"></i> <span>Filter Data:</span>
                </div>
                
                <div class="flex-grow-1">
                    <div class="row g-2">
                        <div class="col-12 col-md-3">
                            <select name="bulan" class="form-select filter-input" onchange="this.form.submit()">
                                <option value="">Semua Bulan</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="tahun" class="form-select filter-input" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
                                    <option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama Pegawai / NIP..." value="{{ $search ?? '' }}">
                                <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc; z-index: 0;"><i class="bi bi-search text-muted"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-shrink-0 mt-2 mt-md-0 justify-content-end">
                    <a href="{{ route('puskesmas.cuti') }}" class="btn btn-light shadow-sm text-secondary border d-flex align-items-center justify-content-center" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    <button type="submit" name="export" value="1" class="btn btn-export-glow shadow-sm fw-bold px-3 py-2 d-flex align-items-center" style="border-radius: 8px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Export Rekap
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="25%">Nama & NIP</th>
                            <th width="15%">Unit Kerja</th>
                            <th width="20%">Detail Cuti</th>
                            <th width="10%" class="text-center">Dokumen</th>
                            <th width="15%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatCuti as $c)
                        <tr class="hover-row">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        {{ substr($c->pegawai->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $c->pegawai->nama_lengkap }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem; font-family: monospace; letter-spacing: 0.5px;">{{ $c->pegawai->nip }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-hospital"></i> {{ $c->pegawai->unit_kerja }}
                                </span>
                            </td>
                            
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $c->jenis_cuti }}</div>
                                <div style="color: #64748b; font-size: 0.8rem; margin-top: 2px;">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - 
                                    {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="btn btn-light btn-sm text-danger fw-bold border-danger border-opacity-25" style="border-radius: 8px; font-size: 0.75rem; padding: 5px 12px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                                    <i class="bi bi-file-earmark-pdf-fill fs-6 align-middle me-1"></i> PDF
                                </a>
                            </td>
                            
                            <td class="text-center">
                                @if($c->status == 'menunggu') 
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Pending</span>
                                @elseif($c->status == 'diproses') 
                                    <span class="badge-soft badge-soft-primary"><i class="bi bi-search"></i> Direview Dinkes</span>
                                @elseif($c->status == 'disetujui') 
                                    <span class="badge-soft badge-soft-success"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                                @else 
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span> 
                                @endif
                            </td>
                            
                            <td class="text-center pe-3">
                                @if($c->status == 'menunggu')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm transition-all w-100 fw-medium" data-bs-toggle="modal" data-bs-target="#batalModal{{ $c->id }}" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan
                                    </button>
                                @elseif($c->status == 'diproses')
                                    <button class="btn btn-light btn-sm rounded-pill px-3 w-100 text-secondary border fw-medium" disabled title="Terkunci: Sedang dibaca oleh Dinkes" style="background: #f1f5f9;">
                                        <i class="bi bi-lock-fill"></i> Terkunci
                                    </button>
                                @elseif($c->status == 'disetujui' && $c->file_sk_resmi)
                                    <a href="{{ asset('storage/'.$c->file_sk_resmi) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm transition-all w-100 fw-bold" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-download me-1"></i> Unduh SK
                                    </a>
                                @elseif($c->status == 'ditolak')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm transition-all w-100 fw-medium" data-bs-toggle="modal" data-bs-target="#alasanModal{{ $c->id }}" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-info-circle me-1"></i> Lihat Alasan
                                    </button>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-folder-x text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Belum ada riwayat pengajuan cuti di unit ini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @foreach($riwayatCuti as $c)
        @if($c->status == 'menunggu')
        <div class="modal fade" id="batalModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="modal-header bg-danger text-white border-0 p-4">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Batalkan Pengajuan</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="summary-card text-start">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-dark fs-6">Ringkasan Pengajuan</span>
                                <span class="badge-soft badge-soft-warning">Pending</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6"><div class="summary-label">Nama Pegawai</div><div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div></div>
                                <div class="col-6"><div class="summary-label">NIP</div><div class="summary-value">{{ $c->pegawai->nip }}</div></div>
                                <div class="col-12"><div class="summary-label">Jenis Cuti</div><div class="summary-value text-primary">{{ $c->jenis_cuti }}</div></div>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Apakah Anda yakin?</h5>
                        <p class="text-muted small mb-4">Pengajuan yang dibatalkan akan <strong>dihapus permanen</strong> dari sistem sebelum diproses oleh Dinkes.</p>
                        
                        <form action="{{ route('cuti.destroy', $c->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light w-50 fw-bold rounded-pill border shadow-sm" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger w-50 fw-bold rounded-pill shadow-sm">Ya, Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($c->status == 'ditolak')
        <div class="modal fade" id="alasanModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="modal-header bg-danger text-white border-0 p-4">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-x-circle-fill me-2"></i> Pengajuan Ditolak</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="summary-card">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-dark fs-6">Ringkasan Pegawai</span>
                                <span class="badge-soft badge-soft-danger">Ditolak</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6"><div class="summary-label">Nama Pegawai</div><div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div></div>
                                <div class="col-6"><div class="summary-label">NIP</div><div class="summary-value">{{ $c->pegawai->nip }}</div></div>
                                <div class="col-12"><div class="summary-label">Jenis Cuti</div><div class="summary-value">{{ $c->jenis_cuti }}</div></div>
                            </div>
                        </div>
                        <div class="alert alert-danger border-danger border-opacity-25 mb-0" style="border-radius: 12px; background-color: #fef2f2;">
                            <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-chat-left-text-fill me-1"></i> Catatan dari Dinas Kesehatan:</h6>
                            <p class="mb-0 text-dark" style="line-height: 1.6; font-size: 0.95rem;">{{ $c->keterangan_admin ?? 'Tidak ada catatan spesifik yang dilampirkan.' }}</p>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border shadow-sm" data-bs-dismiss="modal">Tutup Pesan</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <div class="modal fade" id="modalCuti" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="background-color: #f8fafc; border-radius: 20px;">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4 px-md-5">
                    <div>
                        <h4 class="modal-title fw-bold text-dark" style="letter-spacing: -0.5px;">Formulir Pengajuan Cuti Baru</h4>
                        <p class="text-muted small m-0 mt-1">Lengkapi data dengan benar sebelum mengirimkan permohonan ke Dinas Kesehatan.</p>
                    </div>
                    <button type="button" class="btn-close bg-white rounded-circle p-2 shadow-sm border" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4 px-md-5 py-4">
                    
                    <div class="form-section shadow-sm">
                        <div class="form-section-title"><i class="bi bi-person-vcard text-primary fs-5"></i> 1. Identitas Pegawai</div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Pilih Nama Pegawai</label>
                            <div class="custom-autocomplete-wrapper" id="searchPegawaiWrapper">
                                <div class="modern-input-wrapper" style="padding: 12px 15px; border-color: #bfdbfe;">
                                    <i class="bi bi-search modern-input-icon text-primary"></i>
                                    <input type="text" id="searchPegawaiInput" class="form-control-lg border-0 bg-transparent p-0 w-100 shadow-none" placeholder="Ketik nama atau NIP pegawai Anda..." autocomplete="off" style="font-size: 1rem; color: #1e293b;">
                                </div>
                                <input type="hidden" name="id_pegawai" id="hiddenIdPegawai" required>
                                
                                <div id="customDropdownList" class="custom-autocomplete-list mt-1">
                                    @foreach($semuaPegawai as $p)
                                        <div class="pegawai-option" data-id="{{ $p->id }}" data-nama="{{ $p->nama_lengkap }}" data-nip="{{ $p->nip }}" data-jabatan="{{ $p->jabatan }}" data-unit="{{ $p->unit_kerja }}">
                                            <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $p->nip }} • {{ $p->jabatan }}</small>
                                        </div>
                                    @endforeach
                                    <div id="noResultItem" class="p-4 text-center text-muted" style="display: none;"><i class="bi bi-emoji-frown me-1"></i> Nama tidak ditemukan dalam database</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">NIP</label>
                                <div class="modern-input-wrapper readonly-input"><input type="text" id="autoNip" readonly placeholder="Terisi otomatis..."></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Jabatan</label>
                                <div class="modern-input-wrapper readonly-input"><input type="text" id="autoJabatan" readonly placeholder="Terisi otomatis..."></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Unit Kerja</label>
                                <div class="modern-input-wrapper readonly-input"><input type="text" id="autoUnit" readonly placeholder="Terisi otomatis..."></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section shadow-sm">
                        <div class="form-section-title"><i class="bi bi-calendar2-range text-primary fs-5"></i> 2. Rincian Cuti</div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Jenis Cuti</label>
                            <div class="modern-input-wrapper">
                                <select name="jenis_cuti" required>
                                    <option value="" disabled selected>-- Pilih Kategori Cuti --</option>
                                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                                    <option value="Cuti Sakit">Cuti Sakit</option>
                                    <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                                    <option value="Cuti Alasan Penting">Cuti Alasan Penting</option>
                                    <option value="Cuti Besar">Cuti Besar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tanggal Mulai</label>
                                <div class="modern-input-wrapper"><input type="date" name="tanggal_mulai" id="tanggal_mulai" required></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tanggal Selesai</label>
                                <div class="modern-input-wrapper"><input type="date" name="tanggal_selesai" id="tanggal_selesai" required></div>
                            </div>
                        </div>
                        
                        <div id="durasiCutiBox" style="display: none;" class="mb-4 p-3 rounded-3 border transition-all">
                            <div id="durasiCutiText" class="fw-bold m-0 text-center" style="font-size: 0.95rem;"></div>
                        </div>

                        <div>
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Alasan Cuti <span class="fw-normal text-lowercase text-black-50">(Opsional)</span></label>
                            <div class="modern-input-wrapper" style="align-items: flex-start;">
                                <textarea name="alasan" rows="3" placeholder="Tuliskan alasan pengajuan cuti secara singkat..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section shadow-sm mb-0">
                        <div class="form-section-title"><i class="bi bi-cloud-arrow-up text-primary fs-5"></i> 3. Dokumen Pendukung</div>
                        <p class="text-muted small mb-3">Upload hasil scan <strong>Surat Permohonan Resmi / Surat Dokter</strong> yang telah ditandatangani.</p>
                        
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
                            <h5 class="fw-bold mt-2 text-dark">Klik atau Seret file ke area ini</h5>
                            <p class="text-muted small mb-2">Format yang didukung: PDF, JPG, PNG (Maks 5MB)</p>
                            <span class="btn btn-outline-primary rounded-pill px-4 mt-2 fw-medium">Jelajahi File</span>
                        </div>
                        <input type="file" name="file_permohonan" id="fileInput" accept=".pdf, .jpg, .jpeg, .png" required>
                        
                        <div id="fileNameDisplay" class="mt-4 p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 text-success fw-bold text-center animate-fade-up" style="display: none;">
                            <i class="bi bi-check-circle-fill fs-5 align-middle me-2"></i> <span class="text-dark">File siap diupload:</span> <span id="fileNameText" class="text-success ms-1 text-decoration-underline"></span>
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer border-top p-4 px-md-5 bg-white" style="border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm hover-shadow" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-glow">Kirim Pengajuan <i class="bi bi-send-fill ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. SMART UI: KALKULASI DURASI CUTI
            const tglMulai = document.getElementById('tanggal_mulai');
            const tglSelesai = document.getElementById('tanggal_selesai');
            const durasiBox = document.getElementById('durasiCutiBox');
            const durasiText = document.getElementById('durasiCutiText');
            const btnSubmit = document.getElementById('btnSubmit');

            function hitungDurasi() {
                if (tglMulai.value && tglSelesai.value) {
                    const start = new Date(tglMulai.value);
                    const end = new Date(tglSelesai.value);
                    const diffTime = end - start;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                    durasiBox.style.display = 'block';

                    if (diffDays > 0) {
                        durasiBox.className = 'mb-4 p-3 rounded-3 border border-success bg-success bg-opacity-10 text-success animate-fade-up';
                        durasiText.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Total durasi pengajuan cuti Anda adalah: <strong class="fs-5 ms-1">${diffDays} Hari</strong>`;
                        btnSubmit.disabled = false;
                    } else {
                        durasiBox.className = 'mb-4 p-3 rounded-3 border border-danger bg-danger bg-opacity-10 text-danger animate-fade-up';
                        durasiText.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> Error: Tanggal selesai tidak boleh mendahului tanggal mulai!`;
                        btnSubmit.disabled = true;
                    }
                } else {
                    durasiBox.style.display = 'none';
                    btnSubmit.disabled = false;
                }
            }

            tglMulai.addEventListener('change', hitungDurasi);
            tglSelesai.addEventListener('change', hitungDurasi);

            // 2. LOGIC CUSTOM AUTOCOMPLETE
            const searchInput = document.getElementById('searchPegawaiInput');
            const hiddenInput = document.getElementById('hiddenIdPegawai');
            const dropdownList = document.getElementById('customDropdownList');
            const options = document.querySelectorAll('.pegawai-option');
            const noResult = document.getElementById('noResultItem');
            const wrapper = document.getElementById('searchPegawaiWrapper');

            function filterList() {
                let filter = searchInput.value.toLowerCase();
                let hasVisible = false;
                options.forEach(option => {
                    let nama = option.getAttribute('data-nama').toLowerCase();
                    let nip = option.getAttribute('data-nip').toLowerCase();
                    if (nama.includes(filter) || nip.includes(filter)) {
                        option.style.display = 'block';
                        hasVisible = true;
                    } else {
                        option.style.display = 'none';
                    }
                });
                noResult.style.display = hasVisible ? 'none' : 'block';
                dropdownList.style.display = 'block';
            }

            searchInput.addEventListener('focus', filterList);
            searchInput.addEventListener('keyup', filterList);

            options.forEach(option => {
                option.addEventListener('click', function(e) {
                    searchInput.value = this.getAttribute('data-nama');
                    hiddenInput.value = this.getAttribute('data-id');
                    document.getElementById('autoNip').value = this.getAttribute('data-nip');
                    document.getElementById('autoJabatan').value = this.getAttribute('data-jabatan');
                    document.getElementById('autoUnit').value = this.getAttribute('data-unit');
                    dropdownList.style.display = 'none';
                    
                    // Efek visual sukses memilih
                    wrapper.querySelector('.modern-input-wrapper').style.borderColor = '#10b981';
                    setTimeout(() => wrapper.querySelector('.modern-input-wrapper').style.borderColor = '#bfdbfe', 1000);
                });
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) dropdownList.style.display = 'none';
            });

            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    hiddenInput.value = '';
                    document.getElementById('autoNip').value = '';
                    document.getElementById('autoJabatan').value = '';
                    document.getElementById('autoUnit').value = '';
                }
            });

            // 3. LOGIC UPLOAD FILE
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileNameText = document.getElementById('fileNameText');
            const uploadZone = document.getElementById('uploadZone');

            fileInput.addEventListener('change', function() {
                if(this.files.length > 0) {
                    fileNameText.textContent = this.files[0].name;
                    fileNameDisplay.style.display = 'block';
                    uploadZone.style.borderColor = '#10b981';
                    uploadZone.style.backgroundColor = '#f0fdf4';
                    uploadZone.querySelector('.upload-icon').style.color = '#10b981';
                } else {
                    fileNameDisplay.style.display = 'none';
                    uploadZone.style.borderColor = '#cbd5e1';
                    uploadZone.style.backgroundColor = '#f8fafc';
                    uploadZone.querySelector('.upload-icon').style.color = '#94a3b8';
                }
            });
        });
    </script>
@endsection