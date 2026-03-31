@extends('layouts.puskesmas')
@section('title', 'Data Pegawai')
@section('content')

    <style>
        /* Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }
        
        .btn-danger-glow { transition: all 0.3s ease; }
        .btn-danger-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(220, 38, 38, 0.25) !important; }

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

        /* Modal & Modern Input */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }

        .modern-input-wrapper { display: flex; align-items: center; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 15px; transition: all 0.3s ease; }
        .modern-input-wrapper:focus-within { background-color: #ffffff; border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); transform: translateY(-2px); }
        .modern-input-icon { color: #94a3b8; font-size: 1.2rem; margin-right: 12px; transition: all 0.3s ease; }
        .modern-input-wrapper:focus-within .modern-input-icon { color: #0d6efd; }
        .modern-input-wrapper input, .modern-input-wrapper select { border: none; background: transparent; box-shadow: none !important; padding: 5px 0; width: 100%; color: #334155; font-weight: 500; outline: none; }
        
        .action-btn { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s; }
        .action-btn:hover { transform: scale(1.1); }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Data Pegawai (Internal)</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola database pegawai aktif di <strong>{{ Auth::user()->nama_unit }}</strong>.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm btn-glow fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus-fill me-2 fs-5"></i> Tambah Pegawai
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
        <form action="{{ route('puskesmas.pegawai') }}" method="GET">
            <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
                
                <div class="d-flex align-items-center gap-2 text-primary fw-bold flex-shrink-0" style="font-size: 0.95rem;">
                    <i class="bi bi-search fs-5"></i> <span>Pencarian:</span>
                </div>
                
                <div class="flex-grow-1" style="max-width: 600px;">
                    <div class="row g-2">
                        <div class="col-12 col-md-5">
                            <select name="sort" class="form-select filter-input" onchange="this.form.submit()">
                                <option value="nama_asc" {{ $sort == 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                                <option value="tgl_lahir_asc" {{ $sort == 'tgl_lahir_asc' ? 'selected' : '' }}>Tgl Lahir (Paling Tua)</option>
                                <option value="tgl_lahir_desc" {{ $sort == 'tgl_lahir_desc' ? 'selected' : '' }}>Tgl Lahir (Paling Muda)</option>
                                <option value="pensiun_terdekat" {{ $sort == 'pensiun_terdekat' ? 'selected' : '' }}>Pensiun Terdekat</option>
                                <option value="pensiun_terlama" {{ $sort == 'pensiun_terlama' ? 'selected' : '' }}>Pensiun Terlama</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama Pegawai / NIP..." value="{{ $search ?? '' }}">
                                <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc; z-index: 0;"><i class="bi bi-search text-muted"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-shrink-0 mt-2 mt-md-0 justify-content-end">
                    @if($search || $sort != 'nama_asc')
                        <a href="{{ route('puskesmas.pegawai') }}" class="btn btn-light shadow-sm text-secondary border d-flex align-items-center justify-content-center" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
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
                            <th width="30%">Nama & NIP</th>
                            <th width="25%">Jabatan</th>
                            <th width="25%">Usia & Estimasi Pensiun</th>
                            <th width="15%" class="text-center">Aksi Pengelolaan</th>
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
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 42px; height: 42px; flex-shrink: 0; font-size: 1.1rem;">
                                        {{ substr($p->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $p->nama_lengkap }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem; font-family: monospace; letter-spacing: 0.5px;">NIP: {{ $p->nip }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-briefcase-fill opacity-50"></i> {{ $p->jabatan }}
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
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary rounded-circle shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}" title="Edit Data Pegawai">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-outline-danger rounded-circle shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $p->id }}" title="Hapus Data Pegawai">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-person-x text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
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


    <div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <div>
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-person-plus-fill me-2"></i> Tambah Pegawai Baru</h5>
                        <small class="text-white-50 mt-1 d-block">Masukkan data nakes/pegawai ke dalam sistem.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nomor Induk Pegawai (NIP)</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-credit-card-2-front-fill modern-input-icon"></i>
                                <input type="text" name="nip" required placeholder="Contoh: 198501152010011001">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Lengkap & Gelar</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-person-badge-fill modern-input-icon"></i>
                                <input type="text" name="nama_lengkap" required placeholder="Contoh: dr. Siti Rahma">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Jabatan Fungsional/Pelaksana</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-briefcase-fill modern-input-icon"></i>
                                <input type="text" name="jabatan" required placeholder="Contoh: Perawat Pelaksana">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Tanggal Lahir</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-calendar-date-fill modern-input-icon"></i>
                                    <input type="date" name="tanggal_lahir" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Batas Pensiun</label>
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
                    <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm hover-shadow" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-glow">Simpan Data <i class="bi bi-send-fill ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @foreach($semuaPegawai as $p)
        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #475569 0%, #334155 100%);">
                        <div>
                            <h5 class="modal-title fw-bold m-0"><i class="bi bi-pencil-square me-2"></i> Edit Data Pegawai</h5>
                            <small class="text-white-50 mt-1 d-block">Perbarui informasi profil atau jabatan pegawai.</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 bg-white">
                            
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nomor Induk Pegawai (NIP)</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-credit-card-2-front-fill modern-input-icon"></i>
                                    <input type="text" name="nip" value="{{ $p->nip }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Lengkap & Gelar</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-person-badge-fill modern-input-icon"></i>
                                    <input type="text" name="nama_lengkap" value="{{ $p->nama_lengkap }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Jabatan Fungsional/Pelaksana</label>
                                <div class="modern-input-wrapper">
                                    <i class="bi bi-briefcase-fill modern-input-icon"></i>
                                    <input type="text" name="jabatan" value="{{ $p->jabatan }}" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Tanggal Lahir</label>
                                    <div class="modern-input-wrapper">
                                        <i class="bi bi-calendar-date-fill modern-input-icon"></i>
                                        <input type="date" name="tanggal_lahir" value="{{ $p->tanggal_lahir->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Batas Pensiun</label>
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
                        <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 20px 20px;">
                            <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm hover-shadow" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-dark px-5 fw-bold rounded-pill shadow-sm btn-glow">Update Data <i class="bi bi-check2-circle ms-1"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalHapus{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-body text-center p-4">
                        <div class="mb-3 animate-fade-up">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-x-fill fs-1"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Hapus Data Pegawai?</h5>
                        <p class="text-muted small mb-4">Data <strong>{{ $p->nama_lengkap }}</strong> beserta riwayatnya akan dihapus secara permanen dari sistem.</p>
                        
                        <form action="{{ route('pegawai.delete', $p->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="d-flex flex-column gap-2">
                                <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm py-2 btn-danger-glow">Ya, Hapus Permanen</button>
                                <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border text-muted py-2" data-bs-dismiss="modal">Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection