@extends('layouts.admin')
@section('title', 'E-Pensiun Monitoring')
@section('content')

    <style>
        /* Animasi Masuk */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        /* Efek Hover Tombol Glow */
        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3) !important; }
        .btn-warning-glow { transition: all 0.3s ease; }
        .btn-warning-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(255, 193, 7, 0.4) !important; }

        /* Floating Row Tabel */
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 4px solid #0d6efd; z-index: 10; position: relative; }

        /* Animasi Modal (Memantul & Blur) */
        .modal-backdrop.show { opacity: 0.5 !important; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: #000000; }
        .modal.fade .modal-dialog { transform: scale(0.85) translateY(20px); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }

        /* Ringkasan & Form Verifikasi */
        .summary-card { background-color: #f8fbff; border: 1px solid #e1ecff; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
        .summary-label { font-size: 11px; color: #8392ab; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 3px; }
        .summary-value { font-size: 14.5px; color: #212529; font-weight: 600; }
        
        /* Radio Button Smart Verif */
        .action-radio { display: none; }
        .action-label { display: block; width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; cursor: pointer; transition: all 0.3s; font-weight: 600; color: #64748b; }
        .action-radio:checked + .label-setuju { border-color: #198754; background-color: #198754; color: white; box-shadow: 0 5px 15px rgba(25,135,84,0.3); }
        .action-radio:checked + .label-tolak { border-color: #dc3545; background-color: #dc3545; color: white; box-shadow: 0 5px 15px rgba(220,53,69,0.3); }
        .action-content { display: none; margin-top: 15px; animation: fadeInUp 0.4s forwards; }

        /* Efek Berkedip untuk Notif */
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); } }
        .pulse-btn { animation: pulse-ring 2s infinite; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0 text-dark">E-Pensiun Monitoring</h4>
            <small class="text-muted">Kelola dan pantau data pensiun pegawai di seluruh unit Puskesmas.</small>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm animate-fade-up mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif

    @if($pensiunBulanIniRealtime->count() > 0)
    <div class="card border-warning bg-warning bg-opacity-10 mb-4 animate-fade-up" id="peringatanPensiunBox" style="border-radius: 12px;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" onclick="tutupPeringatan()" title="Tutup peringatan ini"></button>
        <div class="card-body d-flex align-items-start gap-3 p-4">
            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div class="w-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="fw-bold text-dark m-0">Perhatian: Ada Pegawai Pensiun Bulan Ini</h5>
                    <span class="badge bg-warning text-dark shadow-sm">{{ $pensiunBulanIniRealtime->count() }} Pegawai</span>
                </div>
                <p class="text-muted small mb-3">Harap pantau kelengkapan dokumen pensiun dari Puskesmas terkait:</p>
                <div class="d-flex flex-wrap gap-2 pe-4"> 
                    @foreach($pensiunBulanIniRealtime as $p)
                    <div class="bg-white border rounded p-2 px-3 shadow-sm d-flex align-items-center gap-2 transition-all hover-scale">
                        <i class="bi bi-person-circle text-primary fs-4"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->nama_lengkap }}</div>
                            <div class="text-muted d-flex align-items-center gap-2" style="font-size: 11px;">
                                <span><i class="bi bi-building"></i> {{ $p->unit_kerja }}</span>
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
            <div class="card border-0 shadow-sm border-start border-4 border-primary h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small fw-bold text-uppercase mb-1">Total Pensiun ({{ $filterTahun }})</div><h2 class="fw-bold m-0 text-primary">{{ $stats['total'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-secondary h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small fw-bold text-uppercase mb-1">Belum Upload</div><h2 class="fw-bold m-0 text-secondary">{{ $stats['belum_upload'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small fw-bold text-uppercase mb-1">Menunggu Verifikasi</div><h2 class="fw-bold m-0 text-warning">{{ $stats['menunggu'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small fw-bold text-uppercase mb-1">Dokumen Lengkap</div><h2 class="fw-bold m-0 text-success">{{ $stats['lengkap'] }}</h2></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 animate-fade-up delay-2" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('dinkes.pensiun') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted fw-bold"><i class="bi bi-funnel-fill text-primary"></i> Filter:</div>
                <div class="col-md-2"><select name="bulan" class="form-select form-select-sm border-light bg-light shadow-none" onchange="this.form.submit()"><option value="">-- Semua Bulan --</option>@for($i=1; $i<=12; $i++)<option value="{{ $i }}" {{ $filterBulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>@endfor</select></div>
                <div class="col-md-2"><select name="tahun" class="form-select form-select-sm border-light bg-light shadow-none" onchange="this.form.submit()">@for($y=date('Y'); $y<=date('Y')+5; $y++)<option value="{{ $y }}" {{ $filterTahun == $y ? 'selected' : '' }}>{{ $y }}</option>@endfor</select></div>
                <div class="col-md-3"><select name="unit" class="form-select form-select-sm border-light bg-light shadow-none" onchange="this.form.submit()"><option value="">-- Semua Unit --</option>@foreach($listUnitKerja as $unit)<option value="{{ $unit }}" {{ $filterUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>@endforeach</select></div>
                <div class="col-md-3"><div class="input-group input-group-sm"><input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}"><button class="btn btn-light border-light" type="submit"><i class="bi bi-search"></i></button></div></div>
                <div class="col-md-auto ms-auto"><a href="{{ route('dinkes.pensiun') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3 border-0">Nama & NIP</th>
                            <th class="border-0">Unit Kerja</th>
                            <th class="border-0">Tgl Lahir & Pensiun</th>
                            <th class="border-0">Status Akses</th>
                            <th class="border-0">Status Dokumen</th>
                            <th class="text-center pe-4 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($dataPensiun as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $berkas = $p->berkas_pensiun;
                            @endphp
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $p->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $p->nip }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-building"></i> {{ $p->unit_kerja }}</span></td>
                            <td>
                                <div class="text-dark"><small class="text-muted">Lahir:</small> {{ $p->tanggal_lahir->translatedFormat('d M Y') }}</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td>
                                @if($p->is_pensiun_open)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-unlock-fill"></i> Terbuka</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                @endif
                            </td>

                            <td>
                                @if(!$berkas)
                                    <span class="badge bg-light text-secondary border px-2 py-1">Belum Upload</span>
                                @elseif($berkas->status == 'menunggu')
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-2 py-1 border border-warning border-opacity-50">Menunggu Verifikasi</span>
                                @elseif($berkas->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 border border-success border-opacity-25">Lengkap</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25">Revisi</span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                @if(!$p->is_pensiun_open)
                                    <form action="{{ route('dinkes.buka_akses', $p->id) }}" method="POST">
                                        @csrf
                                        @if($p->is_request_open_access)
                                            <button type="submit" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm btn-warning-glow pulse-btn w-100 fw-bold" title="Puskesmas meminta akses">
                                                <i class="bi bi-bell-fill"></i> Buka (Diminta!)
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 w-100 fw-medium transition-all" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="bi bi-key-fill"></i> Buka Akses
                                            </button>
                                        @endif
                                    </form>
                                @else
                                    @if($berkas && $berkas->status == 'menunggu')
                                        <button class="btn btn-sm btn-primary px-3 rounded-pill shadow-sm btn-glow w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#verifModal{{ $berkas->id }}">
                                            <i class="bi bi-shield-check"></i> Verifikasi
                                        </button>
                                    @elseif($berkas && $berkas->status == 'disetujui')
                                        <button class="btn btn-sm btn-light border text-success w-100 fw-medium" disabled><i class="bi bi-check2-all"></i> Selesai</button>
                                    @else
                                        <button class="btn btn-sm btn-light border text-muted w-100" disabled style="font-size: 11px;">Menunggu Upload</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-inbox fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Tidak ada data pegawai pensiun sesuai filter.</span>
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
        @if($berkas && $berkas->status == 'menunggu')
        <div class="modal fade" id="verifModal{{ $berkas->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-shield-check me-2"></i> Verifikasi Dokumen Pensiun</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white">
                        
                        <div class="summary-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-primary fs-6"><i class="bi bi-person-vcard me-1"></i> Data Pegawai Pensiun</span>
                                <span class="badge bg-warning text-dark bg-opacity-25 px-2 py-1">Menunggu Verifikasi</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6"><div class="summary-label">Nama Pegawai</div><div class="summary-value">{{ $p->nama_lengkap }}</div></div>
                                <div class="col-md-6"><div class="summary-label">NIP</div><div class="summary-value">{{ $p->nip }}</div></div>
                                <div class="col-md-6"><div class="summary-label">Unit Kerja</div><div class="summary-value">{{ $p->unit_kerja }}</div></div>
                                <div class="col-md-6"><div class="summary-label">Tanggal Pensiun</div><div class="summary-value text-danger">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun)->translatedFormat('d M Y') }}</div></div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-folder2-open"></i> File Dokumen (PDF)</h6>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <a href="{{ asset('storage/'.$berkas->file_sk_cpns) }}" target="_blank" class="btn btn-outline-danger w-100 fw-bold shadow-sm transition-all hover-scale" style="border-radius: 10px;">
                                    <i class="bi bi-file-pdf fs-5 d-block mb-1"></i> 1. SK CPNS
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="{{ asset('storage/'.$berkas->file_sk_pangkat) }}" target="_blank" class="btn btn-outline-danger w-100 fw-bold shadow-sm transition-all hover-scale" style="border-radius: 10px;">
                                    <i class="bi bi-file-pdf fs-5 d-block mb-1"></i> 2. SK Pangkat
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="{{ asset('storage/'.$berkas->file_karpeg) }}" target="_blank" class="btn btn-outline-danger w-100 fw-bold shadow-sm transition-all hover-scale" style="border-radius: 10px;">
                                    <i class="bi bi-file-pdf fs-5 d-block mb-1"></i> 3. Karpeg
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('pensiun.verifikasi', $berkas->id) }}" method="POST">
                            @csrf
                            <hr class="text-muted opacity-25">
                            <h6 class="fw-bold mb-3 text-dark">Keputusan Verifikasi:</h6>
                            <div class="row g-3 mb-2">
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="setuju" id="setujuPensiun{{ $berkas->id }}" class="action-radio" onchange="toggleActionPensiun('{{ $berkas->id }}', 'setuju')">
                                    <label class="action-label label-setuju shadow-sm" for="setujuPensiun{{ $berkas->id }}">
                                        <i class="bi bi-check-circle-fill fs-5 d-block mb-1"></i> Dokumen Lengkap (Setujui)
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="tolak" id="tolakPensiun{{ $berkas->id }}" class="action-radio" onchange="toggleActionPensiun('{{ $berkas->id }}', 'tolak')">
                                    <label class="action-label label-tolak shadow-sm" for="tolakPensiun{{ $berkas->id }}">
                                        <i class="bi bi-x-circle-fill fs-5 d-block mb-1"></i> Ada Kekurangan (Revisi)
                                    </label>
                                </div>
                            </div>

                            <div id="boxTolakPensiun{{ $berkas->id }}" class="action-content p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3">
                                <label class="form-label fw-bold text-danger"><i class="bi bi-pencil-square"></i> Catatan Kekurangan/Revisi</label>
                                <textarea name="catatan" id="catatanTolakPensiun{{ $berkas->id }}" class="form-control bg-white shadow-none border-danger border-opacity-50" rows="2" placeholder="Tuliskan file apa yang salah atau kurang jelas..."></textarea>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold border me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnSubmitVerifPensiun{{ $berkas->id }}" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm" disabled>Simpan Keputusan <i class="bi bi-send-fill ms-1"></i></button>
                            </div>
                        </form>

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

        // Logika Form Verifikasi Cerdas
        function toggleActionPensiun(id, action) {
            const boxTolak = document.getElementById('boxTolakPensiun' + id);
            const btnSubmit = document.getElementById('btnSubmitVerifPensiun' + id);
            const catatan = document.getElementById('catatanTolakPensiun' + id);

            btnSubmit.disabled = false;

            if (action === 'setuju') {
                boxTolak.style.display = 'none';
                btnSubmit.className = 'btn btn-success px-5 rounded-pill fw-bold shadow-sm btn-glow';
                catatan.required = false;
                catatan.value = ''; // bersihkan catatan
            } else if (action === 'tolak') {
                boxTolak.style.display = 'block';
                btnSubmit.className = 'btn btn-danger px-5 rounded-pill fw-bold shadow-sm btn-glow';
                catatan.required = true;
            }
        }
    </script>
@endsection