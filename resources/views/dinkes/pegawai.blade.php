@extends('layouts.admin')
@section('title', 'Data Pegawai Se-Kabupaten')
@section('content')

    <style>
        /* Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        /* Tabel Premium */
        .premium-table th { background-color: #f8fafc !important; color: #64748b !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 15px 20px !important; border-bottom: 2px solid #e2e8f0 !important; border-top: none !important; }
        .premium-table td { padding: 16px 20px !important; vertical-align: middle; border-bottom: 1px solid #f1f5f9 !important; color: #334155; }
        
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 3px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: translateX(4px); box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 3px solid #0d6efd; z-index: 10; position: relative; }

        /* Desain Lencana (Badges) Lembut */
        .badge-soft { padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.3px; display: inline-flex; align-items: center; gap: 5px; }
        .badge-soft-primary { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-soft-secondary { background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* Filter Kotak */
        .filter-box { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 25px; }
        .filter-input { background-color: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 0.85rem; padding: 8px 12px; box-shadow: none !important; transition: all 0.3s; }
        .filter-input:focus { background-color: white; border-color: #0d6efd; }
        
        /* Tombol Export Glow */
        .btn-export-glow { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; transition: all 0.3s ease; }
        .btn-export-glow:hover { color: white; transform: translateY(-3px); box-shadow: 0 8px 15px rgba(16, 185, 129, 0.3) !important; }

        /* ----------------------------------------------------
           ANIMASI MODAL CANGGIH & KARTU PROFIL
           ---------------------------------------------------- */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }
        
        /* Desain Profil Card di dalam Modal */
        .profile-header { background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); padding: 40px 20px 30px; text-align: center; color: white; border-radius: 20px 20px 0 0; position: relative; }
        
        /* Efek Ornamen Lingkaran di Modal */
        .profile-header::before { content: ''; position: absolute; top: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        .profile-header::after { content: ''; position: absolute; bottom: -50px; right: -30px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }

        .profile-avatar { width: 90px; height: 90px; background-color: white; color: #0d6efd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 15px; border: 4px solid rgba(255,255,255,0.8); box-shadow: 0 10px 20px rgba(0,0,0,0.15); position: relative; z-index: 2; font-weight: 800; }
        
        .profile-title-text { position: relative; z-index: 2; }
        
        .info-box { background-color: #f8fafc; border-radius: 12px; padding: 15px 20px; border: 1px solid #f1f5f9; margin-bottom: 15px; transition: all 0.3s; }
        .info-box:hover { background-color: white; border-color: #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transform: translateY(-2px); }
        .info-label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .info-value { font-size: 0.95rem; color: #1e293b; font-weight: 600; }
    </style>

    <div class="d-flex justify-content-between align-items-end mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Data Pegawai (Master)</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Pantau seluruh database pegawai aktif se-Kabupaten Batang.</p>
        </div>
    </div>

    <div class="filter-box animate-fade-up delay-1">
        <form action="{{ route('dinkes.pegawai') }}" method="GET">
            <div class="d-flex flex-column flex-xl-row gap-3 align-items-xl-center">
                
                <div class="d-flex align-items-center gap-2 text-primary fw-bold flex-shrink-0" style="font-size: 0.95rem;">
                    <i class="bi bi-funnel-fill fs-5"></i> <span>Filter:</span>
                </div>
                
                <div class="flex-grow-1">
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <select name="unit" class="form-select filter-input" onchange="this.form.submit()">
                                <option value="">Semua Unit Kerja</option>
                                @if(isset($listUnitKerja))
                                    @foreach($listUnitKerja as $unit)
                                        <option value="{{ $unit }}" {{ (isset($filterUnit) && $filterUnit == $unit) ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <select name="sort" class="form-select filter-input" onchange="this.form.submit()">
                                <option value="nama_asc" {{ (isset($sort) && $sort == 'nama_asc') ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                                <option value="tgl_lahir_asc" {{ (isset($sort) && $sort == 'tgl_lahir_asc') ? 'selected' : '' }}>Usia (Paling Tua)</option>
                                <option value="tgl_lahir_desc" {{ (isset($sort) && $sort == 'tgl_lahir_desc') ? 'selected' : '' }}>Usia (Paling Muda)</option>
                                <option value="pensiun_terdekat" {{ (isset($sort) && $sort == 'pensiun_terdekat') ? 'selected' : '' }}>Pensiun Terdekat</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                                <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc; z-index: 0;"><i class="bi bi-search text-muted"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-shrink-0 mt-2 mt-xl-0 justify-content-end">
                    <a href="{{ route('dinkes.pegawai') }}" class="btn btn-light shadow-sm text-secondary border d-flex align-items-center justify-content-center" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    <button type="submit" name="export" value="1" class="btn btn-export-glow shadow-sm fw-bold px-4 py-2 d-flex align-items-center" style="border-radius: 8px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Export Data
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
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">Nama & NIP</th>
                            <th width="25%">Jabatan & Unit Kerja</th>
                            <th width="25%">Usia & Estimasi Pensiun</th>
                            <th width="20%" class="text-center">Aksi / Profil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaPegawai as $index => $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $usia = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
                            @endphp
                        <tr class="hover-row">
                            <td class="ps-4 text-muted fw-bold" style="font-size: 0.9rem;">
                                {{ $semuaPegawai->firstItem() + $index }}
                            </td>
                            
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
                                <div class="fw-semibold text-dark mb-1" style="font-size: 0.9rem;">{{ $p->jabatan }}</div>
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-hospital"></i> {{ $p->unit_kerja }}
                                </span>
                            </td>
                            
                            <td>
                                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 2px;">
                                    Usia Saat Ini: <span class="text-dark fw-medium">{{ $usia }} Tahun</span>
                                </div>
                                <div style="font-size: 0.8rem; color: #dc2626; font-weight: 600;">
                                    Pensiun: {{ $tglPensiun->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            
                            <td class="text-center pe-4">
                                <button class="btn btn-outline-primary btn-sm rounded-pill shadow-sm transition-all fw-bold w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}" style="padding: 6px 0;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="bi bi-person-vcard me-1"></i> Buka Profil
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-person-vcard text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Tidak ada data pegawai yang sesuai dengan filter.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-white border-top">
                {{ $semuaPegawai->withQueryString()->links() }}
            </div>
        </div>
    </div>


    @foreach($semuaPegawai as $p)
        @php
            $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
            $usia = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
        @endphp
        <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="profile-header">
                        <button type="button" class="btn-close btn-close-white shadow-none position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" style="z-index: 10;"></button>
                        
                        <div class="profile-title-text">
                            <div class="profile-avatar mx-auto">
                                {{ substr($p->nama_lengkap, 0, 1) }}
                            </div>
                            <h4 class="fw-bold m-0 text-white">{{ $p->nama_lengkap }}</h4>
                            <p class="text-white-50 m-0 mt-1" style="font-family: monospace; font-size: 0.9rem; letter-spacing: 1px;">{{ $p->nip }}</p>
                        </div>
                    </div>

                    <div class="modal-body p-4 bg-white">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="info-box d-flex align-items-center">
                                    <div class="me-3 text-primary" style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-hospital-fill"></i></div>
                                    <div>
                                        <div class="info-label">Penempatan / Unit Kerja</div>
                                        <div class="info-value">{{ $p->unit_kerja }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="info-box d-flex align-items-center">
                                    <div class="me-3 text-info" style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-briefcase-fill"></i></div>
                                    <div>
                                        <div class="info-label">Jabatan Fungsional</div>
                                        <div class="info-value">{{ $p->jabatan }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="info-box text-center h-100 d-flex flex-column justify-content-center">
                                    <div class="info-label">Tgl Lahir / Usia</div>
                                    <div class="info-value text-primary mt-1">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') }}</div>
                                    <div class="badge-soft badge-soft-primary mx-auto mt-2">{{ $usia }} Tahun</div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="info-box text-center h-100 d-flex flex-column justify-content-center" style="background-color: #fef2f2; border-color: #fecaca;">
                                    <div class="info-label text-danger">Estimasi Pensiun</div>
                                    <div class="info-value text-danger mt-1">{{ $tglPensiun->translatedFormat('d M Y') }}</div>
                                    <div class="badge-soft badge-soft-danger mx-auto mt-2 border-0"><i class="bi bi-hourglass-split"></i> Masa Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 p-4 pt-0 bg-white">
                        <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border shadow-sm text-secondary hover-shadow py-2" data-bs-dismiss="modal">
                            Tutup ID Card
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    @endforeach

@endsection