@extends('layouts.admin')
@section('title', 'Data Pegawai Se-Kabupaten')
@section('content')

    <style>
        /* Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }

        /* Efek Hover Tombol Glow */
        .btn-export-glow { transition: all 0.3s ease; }
        .btn-export-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(25, 135, 84, 0.3) !important; }

        /* Efek Hover Baris Tabel (Floating Row) */
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { 
            background-color: #f8fbff !important; 
            transform: scale(1.01); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            border-left: 4px solid #0d6efd; 
            z-index: 10; 
            position: relative;
        }

        /* ----------------------------------------------------
           ANIMASI MODAL CANGGIH & KARTU PROFIL
           ---------------------------------------------------- */
        .modal-backdrop.show {
            opacity: 0.5 !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background-color: #000000;
        }
        .modal.fade .modal-dialog {
            transform: scale(0.85) translateY(20px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
        
        /* Desain Profil Card di dalam Modal */
        .profile-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
            border-radius: 16px 16px 0 0;
        }
        .profile-avatar {
            width: 80px; height: 80px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
            font-size: 2.5rem; margin-bottom: 15px; border: 3px solid rgba(255,255,255,0.5);
        }
        .info-box {
            background-color: #f8f9fa; border-radius: 10px; padding: 12px 15px;
            border: 1px solid #e9ecef; margin-bottom: 12px;
        }
        .info-label { font-size: 11px; color: #6c757d; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-value { font-size: 14.5px; color: #212529; font-weight: 600; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0 text-dark">Data Pegawai (Master)</h4>
            <small class="text-muted">Pantau seluruh database pegawai aktif se-Kabupaten Batang.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-1" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('dinkes.pegawai') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-auto text-muted fw-medium"><i class="bi bi-funnel-fill text-primary"></i> Filter:</div>
                    
                    <div class="col-md-3">
                        <select name="unit" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                            <option value="">-- Semua Unit Kerja --</option>
                            @foreach($listUnitKerja as $unit)
                                <option value="{{ $unit }}" {{ $filterUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="sort" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                            <option value="nama_asc" {{ $sort == 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                            <option value="tgl_lahir_asc" {{ $sort == 'tgl_lahir_asc' ? 'selected' : '' }}>Usia (Paling Tua)</option>
                            <option value="tgl_lahir_desc" {{ $sort == 'tgl_lahir_desc' ? 'selected' : '' }}>Usia (Paling Muda)</option>
                            <option value="pensiun_terdekat" {{ $sort == 'pensiun_terdekat' ? 'selected' : '' }}>Pensiun Terdekat</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                            <button class="btn btn-light border-light text-muted" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <div class="col-md-auto ms-auto d-flex gap-2">
                        <a href="{{ route('dinkes.pegawai') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                        <button type="submit" name="export" value="1" class="btn btn-sm btn-success shadow-sm btn-export-glow fw-medium">
                            <i class="bi bi-file-earmark-excel"></i> Export Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3 border-0" width="5%">No</th>
                            <th class="border-0">Nama & NIP</th>
                            <th class="border-0">Jabatan & Unit Kerja</th>
                            <th class="border-0">Usia & Estimasi Pensiun</th>
                            <th class="text-center border-0" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($semuaPegawai as $index => $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $usia = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
                            @endphp
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 text-muted fw-medium">{{ $semuaPegawai->firstItem() + $index }}</td>
                            <td class="py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $p->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $p->nip }}</div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $p->jabatan }}</div>
                                <div class="text-primary" style="font-size: 12px;"><i class="bi bi-building"></i> {{ $p->unit_kerja }}</div>
                            </td>
                            <td>
                                <div class="text-dark"><small class="text-muted">Usia:</small> {{ $usia }} Tahun</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td class="text-center pe-3">
                                <button class="btn btn-outline-primary btn-sm rounded-pill shadow-sm transition-all fw-medium w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="bi bi-person-vcard me-1"></i> Profil
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-person-vcard fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Tidak ada data pegawai yang sesuai dengan filter.</span>
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
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    
                    <div class="profile-header position-relative">
                        <button type="button" class="btn-close btn-close-white shadow-none position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                        <div class="profile-avatar shadow-sm">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h4 class="fw-bold m-0">{{ $p->nama_lengkap }}</h4>
                        <p class="text-white-50 m-0" style="font-family: monospace;">{{ $p->nip }}</p>
                    </div>

                    <div class="modal-body p-4 bg-white">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="info-box d-flex align-items-center">
                                    <div class="me-3 text-primary fs-3"><i class="bi bi-building-fill"></i></div>
                                    <div>
                                        <div class="info-label">Lokasi / Unit Kerja</div>
                                        <div class="info-value">{{ $p->unit_kerja }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box d-flex align-items-center">
                                    <div class="me-3 text-info fs-3"><i class="bi bi-briefcase-fill"></i></div>
                                    <div>
                                        <div class="info-label">Jabatan Fungsional</div>
                                        <div class="info-value">{{ $p->jabatan }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <div class="info-label">Tgl Lahir / Usia</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') }} ({{ $usia }} Thn)</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box border-danger border-opacity-25 bg-danger bg-opacity-10">
                                    <div class="info-label text-danger">Estimasi Pensiun</div>
                                    <div class="info-value text-danger">{{ $tglPensiun->translatedFormat('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border shadow-sm text-muted hover-shadow" data-bs-dismiss="modal">Tutup Profil</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection