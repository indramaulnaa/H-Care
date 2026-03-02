@extends('layouts.puskesmas')
@section('title', 'Data Pegawai')
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

        /* Efek Hover Tombol Glow Biru (Primary) & Merah (Danger) */
        .btn-primary-glow { transition: all 0.3s ease; }
        .btn-primary-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3) !important; }
        .btn-danger-glow { transition: all 0.3s ease; }
        .btn-danger-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(220, 53, 69, 0.3) !important; }

        /* Efek Hover Baris Tabel (Floating Row) */
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { 
            background-color: #f8fbff !important; 
            transform: scale(1.01); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            border-left: 4px solid #0d6efd; /* Garis biru di kiri */
            z-index: 10; 
            position: relative;
        }

        /* Animasi Modal Canggih (Pop-up) */
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

        /* --------------------------------------------------
           DESAIN FORM INPUT CANGGIH (SMART INPUT)
           -------------------------------------------------- */
        .modern-input-wrapper {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }
        .modern-input-wrapper:focus-within {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            transform: translateY(-2px);
        }
        .modern-input-icon {
            color: #94a3b8;
            font-size: 1.2rem;
            margin-right: 12px;
            transition: all 0.3s ease;
        }
        .modern-input-wrapper:focus-within .modern-input-icon {
            color: #0d6efd; /* Ikon nyala biru saat diklik */
            transform: scale(1.1);
        }
        .modern-input-wrapper input, 
        .modern-input-wrapper select {
            border: none;
            background: transparent;
            box-shadow: none !important;
            padding: 5px 0;
            width: 100%;
            color: #334155;
            font-weight: 500;
        }
        .modern-input-wrapper input::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0 text-dark">Data Pegawai</h4>
            <small class="text-muted">Kelola database pegawai aktif di <strong>{{ Auth::user()->nama_unit }}</strong>.</small>
        </div>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm animate-fade-up mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    @endif
    @if($errors->any()) 
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm animate-fade-up mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-1" style="border-radius: 12px;">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-auto">
                    <button class="btn btn-primary fw-bold shadow-sm btn-primary-glow px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pegawai
                    </button>
                </div>

                <div class="col-md-9 ms-auto">
                    <form action="{{ route('puskesmas.pegawai') }}" method="GET">
                        <div class="row g-2 justify-content-end align-items-center">
                            <div class="col-md-4">
                                <select name="sort" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                                    <option value="nama_asc" {{ $sort == 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                                    <option value="tgl_lahir_asc" {{ $sort == 'tgl_lahir_asc' ? 'selected' : '' }}>Tgl Lahir (Paling Tua)</option>
                                    <option value="tgl_lahir_desc" {{ $sort == 'tgl_lahir_desc' ? 'selected' : '' }}>Tgl Lahir (Paling Muda)</option>
                                    <option value="pensiun_terdekat" {{ $sort == 'pensiun_terdekat' ? 'selected' : '' }}>Pensiun Terdekat</option>
                                    <option value="pensiun_terlama" {{ $sort == 'pensiun_terlama' ? 'selected' : '' }}>Pensiun Terlama</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                                    <button class="btn btn-light border-light text-muted" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                            @if($search || $sort != 'nama_asc')
                            <div class="col-md-auto">
                                <a href="{{ route('puskesmas.pegawai') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow" title="Reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
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
                            <th class="border-0">Jabatan</th>
                            <th class="border-0">Tgl Lahir & Pensiun</th>
                            <th class="text-center border-0" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($semuaPegawai as $index => $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                            @endphp
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 text-muted fw-medium">{{ $semuaPegawai->firstItem() + $index }}</td>
                            <td class="py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $p->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $p->nip }}</div>
                            </td>
                            <td>
                                <span class="fw-medium text-dark">{{ $p->jabatan }}</span>
                            </td>
                            <td>
                                <div class="text-dark"><small class="text-muted">Lahir:</small> {{ $p->tanggal_lahir->translatedFormat('d M Y') }}</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary btn-sm rounded-circle shadow-sm transition-all" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}" title="Edit Pegawai" style="width: 32px; height: 32px; padding: 0;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-circle shadow-sm transition-all" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $p->id }}" title="Hapus Pegawai" style="width: 32px; height: 32px; padding: 0;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-person-x fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Tidak ada data pegawai yang ditemukan.</span>
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


    <div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <h5 class="modal-title fw-bold m-0"><i class="bi bi-person-plus-fill me-2"></i> Tambah Pegawai Baru</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">NIP Pegawai</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-credit-card-2-front-fill modern-input-icon"></i>
                                <input type="text" name="nip" required placeholder="Contoh: 198501152010011001">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Nama Lengkap</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-person-badge-fill modern-input-icon"></i>
                                <input type="text" name="nama_lengkap" required placeholder="Gelar & Nama (Contoh: dr. Siti Rahma)">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Jabatan</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-briefcase-fill modern-input-icon"></i>
                                <input type="text" name="jabatan" required placeholder="Contoh: Perawat Pelaksana">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tanggal Lahir</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-calendar-date-fill modern-input-icon"></i>
                                    <input type="date" name="tanggal_lahir" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Batas Pensiun</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-hourglass-split modern-input-icon"></i>
                                    <select name="batas_usia_pensiun" required>
                                        <option value="58">58 Tahun (Umum)</option>
                                        <option value="60">60 Tahun (Fungsional)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 16px 16px;">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted transition-all border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-primary-glow">Simpan Data <i class="bi bi-send-fill ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @foreach($semuaPegawai as $p)
        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #475569 0%, #334155 100%);">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-pencil-square me-2"></i> Edit Data Pegawai</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 bg-white">
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">NIP Pegawai</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-credit-card-2-front-fill modern-input-icon"></i>
                                    <input type="text" name="nip" value="{{ $p->nip }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Nama Lengkap</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-person-badge-fill modern-input-icon"></i>
                                    <input type="text" name="nama_lengkap" value="{{ $p->nama_lengkap }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Jabatan</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-briefcase-fill modern-input-icon"></i>
                                    <input type="text" name="jabatan" value="{{ $p->jabatan }}" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tanggal Lahir</label>
                                    <div class="modern-input-wrapper">
                                        <i class="bi bi-calendar-date-fill modern-input-icon"></i>
                                        <input type="date" name="tanggal_lahir" value="{{ $p->tanggal_lahir->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Batas Pensiun</label>
                                    <div class="modern-input-wrapper">
                                        <i class="bi bi-hourglass-split modern-input-icon"></i>
                                        <select name="batas_usia_pensiun" required>
                                            <option value="58" {{ $p->batas_usia_pensiun == 58 ? 'selected' : '' }}>58 Tahun (Umum)</option>
                                            <option value="60" {{ $p->batas_usia_pensiun == 60 ? 'selected' : '' }}>60 Tahun (Fungsional)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 16px 16px;">
                            <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted transition-all border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-primary-glow">Update Data <i class="bi bi-check2-circle ms-1"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalHapus{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-body text-center p-4">
                        <div class="text-danger mb-3 animate-fade-up">
                            <i class="bi bi-trash3-fill" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Hapus Data Pegawai?</h5>
                        <p class="text-muted small mb-4">Data <strong>{{ $p->nama_lengkap }}</strong> beserta NIP <strong>{{ $p->nip }}</strong> akan dihapus secara permanen.</p>
                        
                        <form action="{{ route('pegawai.delete', $p->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light w-50 fw-bold rounded-pill border" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-danger w-50 fw-bold rounded-pill shadow-sm btn-danger-glow">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection