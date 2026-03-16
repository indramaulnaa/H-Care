@extends('layouts.puskesmas')

@section('title', 'E-Pensiun Monitoring')

@section('content')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3) !important; }
        .btn-info-glow { transition: all 0.3s ease; }
        .btn-info-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 202, 240, 0.4) !important; }

        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 4px solid #0d6efd; z-index: 10; position: relative; }

        .modal-backdrop.show { opacity: 0.5 !important; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: #000000; }
        .modal.fade .modal-dialog { transform: scale(0.85) translateY(20px); opacity: 0; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }

        /* Efek Berkedip untuk Notif */
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); } }
        .pulse-btn { animation: pulse-ring 2s infinite; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0">E-Pensiun Monitoring</h4>
            <small class="text-muted">Kelola dan pantau data pensiun pegawai di {{ Auth::user()->nama_unit }}.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 animate-fade-up">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($pensiunBulanIniRealtime->count() > 0)
    <div class="card border-warning bg-warning bg-opacity-10 mb-4 animate-fade-up" id="peringatanPensiunBox" style="border-radius: 12px;">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" onclick="tutupPeringatan()" title="Tutup peringatan ini"></button>
        <div class="card-body d-flex align-items-start gap-3 p-4">
            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="fw-bold text-dark m-0">Peringatan: Pegawai Akan Pensiun Bulan Ini</h5>
                    <span class="badge bg-warning text-dark shadow-sm">{{ $pensiunBulanIniRealtime->count() }} Pegawai</span>
                </div>
                <p class="text-muted small mb-3">Segera lengkapi dokumen pensiun untuk pegawai berikut:</p>
                <div class="d-flex flex-wrap gap-2 pe-4"> 
                    @foreach($pensiunBulanIniRealtime as $p)
                    <div class="bg-white border rounded p-2 px-3 shadow-sm d-flex align-items-center gap-2 transition-all hover-scale">
                        <i class="bi bi-person-circle text-primary fs-4"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size: 11px;">NIP: {{ $p->nip }}</div>
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
                <div class="card-body"><div class="text-muted small mb-1 fw-bold text-uppercase">Total Pensiun ({{ $filterTahun ?? date('Y') }})</div><h2 class="fw-bold m-0 text-primary">{{ $stats['total'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-secondary h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small mb-1 fw-bold text-uppercase">Belum Upload</div><h2 class="fw-bold m-0 text-secondary">{{ $stats['belum_upload'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small mb-1 fw-bold text-uppercase">Proses Dinkes</div><h2 class="fw-bold m-0 text-warning">{{ $stats['menunggu'] }}</h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100" style="border-radius: 12px;">
                <div class="card-body"><div class="text-muted small mb-1 fw-bold text-uppercase">Selesai</div><h2 class="fw-bold m-0 text-success">{{ $stats['lengkap'] }}</h2></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-2" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('puskesmas.pensiun') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted fw-bold"><i class="bi bi-funnel-fill text-primary"></i> Filter:</div>
                <div class="col-md-2"><select name="bulan" class="form-select form-select-sm border-light bg-light shadow-none" onchange="this.form.submit()"><option value="">-- Semua Bulan --</option>@for($i=1; $i<=12; $i++)<option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>@endfor</select></div>
                <div class="col-md-2"><select name="tahun" class="form-select form-select-sm border-light bg-light shadow-none" onchange="this.form.submit()">@for($y=date('Y'); $y<=date('Y')+5; $y++)<option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>@endfor</select></div>
                <div class="col-md-4"><div class="input-group input-group-sm"><input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}"><button class="btn btn-light border-light text-muted" type="submit"><i class="bi bi-search"></i></button></div></div>
                <div class="col-md-auto ms-auto"><a href="{{ route('puskesmas.pensiun') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
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
                            <th class="border-0">Usia</th>
                            <th class="border-0">Tgl Lahir & Pensiun</th>
                            <th class="border-0">Status Dokumen</th>
                            <th class="text-center pe-4 border-0" style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($dataPensiun as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $berkas = $p->berkas_pensiun;
                                $usiaSekarang = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
                            @endphp
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $p->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $p->nip }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $usiaSekarang }} Tahun</span></td>
                            <td>
                                <div class="text-dark"><small class="text-muted">Lahir:</small> {{ $p->tanggal_lahir->translatedFormat('d M Y') }}</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td>
                                @if(!$berkas)
                                    <span class="badge bg-light text-secondary border px-2 py-1">Belum Upload</span>
                                @elseif($berkas->status == 'menunggu_tahap_1')
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-2 py-1 border border-warning border-opacity-50"><i class="bi bi-hourglass-split"></i> Proses Dinkes (Tahap 1)</span>
                                @elseif($berkas->status == 'ditolak_tahap_1')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25"><i class="bi bi-x-circle"></i> Revisi Tahap 1</span>
                                @elseif($berkas->status == 'lulus_tahap_1')
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 border border-info border-opacity-50"><i class="bi bi-check-circle"></i> Lulus (Tunggu Tahap 2)</span>
                                @elseif($berkas->status == 'menunggu_tahap_2')
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-2 py-1 border border-warning border-opacity-50"><i class="bi bi-hourglass-split"></i> Proses Dinkes (Tahap 2)</span>
                                @elseif($berkas->status == 'ditolak_tahap_2')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25"><i class="bi bi-x-circle"></i> Revisi Tahap 2</span>
                                @elseif($berkas->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 border border-success border-opacity-25"><i class="bi bi-check2-all"></i> Selesai Total</span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                @if(!$p->is_pensiun_open)
                                    @if($p->is_request_open_access)
                                        <button class="btn btn-warning btn-sm bg-opacity-25 text-dark border-warning w-100 fw-bold pulse-btn" disabled>Menunggu Dinkes</button>
                                    @else
                                        <form action="{{ route('puskesmas.request_akses', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm w-100 fw-bold transition-all" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"><i class="bi bi-bell-fill"></i> Minta Akses</button>
                                        </form>
                                    @endif
                                @else
                                    @if(!$berkas || $berkas->status == 'ditolak_tahap_1')
                                        <button class="btn btn-primary btn-sm shadow-sm rounded-pill px-3 w-100 fw-bold btn-glow" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}"><i class="bi bi-upload"></i> Upload Tahap 1</button>
                                    @elseif($berkas->status == 'lulus_tahap_1' || $berkas->status == 'ditolak_tahap_2')
                                        <button class="btn btn-info text-white btn-sm shadow-sm rounded-pill px-3 w-100 fw-bold btn-info-glow" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}"><i class="bi bi-upload"></i> Upload Tahap 2</button>
                                    @elseif($berkas->status == 'menunggu_tahap_1' || $berkas->status == 'menunggu_tahap_2')
                                        <span class="text-muted small fw-medium"><i class="bi bi-gear-wide-connected"></i> Diproses Dinkes...</span>
                                    @elseif($berkas->status == 'disetujui')
                                        <button class="btn btn-light btn-sm rounded-pill border text-success w-100 fw-bold" disabled><i class="bi bi-check2-all"></i> Tuntas</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-folder-x fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Tidak ada data pegawai pensiun.</span>
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
        @if($p->is_pensiun_open)
        <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <div>
                            <h5 class="modal-title fw-bold m-0"><i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Berkas Pensiun</h5>
                            <small class="opacity-75">@if(!$berkas || $berkas->status == 'ditolak_tahap_1') Fase 1: Syarat Dasar @else Fase 2: Syarat Akhir @endif</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75 hover-opacity-100" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('pensiun.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pegawai" value="{{ $p->id }}">
                        <div class="modal-body p-4 text-start bg-white">
                            
                            <div class="alert alert-light border shadow-sm mb-4" style="border-radius: 10px;">
                                <div class="fw-bold text-dark"><i class="bi bi-person-fill text-primary"></i> {{ $p->nama_lengkap }}</div>
                                <div class="text-muted small">Pastikan file yang diunggah berformat <strong>PDF</strong> dan maksimal <strong>2MB</strong> per file.</div>
                            </div>

                            @if($berkas && ($berkas->status == 'ditolak_tahap_1' || $berkas->status == 'ditolak_tahap_2'))
                                <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 mb-4 rounded-3">
                                    <h6 class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Catatan Revisi Dinkes:</h6>
                                    <p class="mb-0 text-dark small">{{ $berkas->catatan }}</p>
                                </div>
                            @endif

                            @if(!$berkas || $berkas->status == 'ditolak_tahap_1')
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-file-pdf"></i> 1. Upload SK CPNS</label>
                                    <input type="file" name="file_sk_cpns" class="form-control bg-light shadow-none" required accept="application/pdf">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-file-pdf"></i> 2. Upload SK Pangkat Terakhir</label>
                                    <input type="file" name="file_sk_pangkat" class="form-control bg-light shadow-none" required accept="application/pdf">
                                </div>
                            
                            @else
                                <div class="alert alert-success border-success border-opacity-25 bg-success bg-opacity-10 small mb-3 rounded-3">
                                    <i class="bi bi-check-circle-fill text-success"></i> SK CPNS dan SK Pangkat telah <strong>Lulus Verifikasi</strong>. Silakan upload berkas terakhir.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-info"><i class="bi bi-file-pdf"></i> 3. Upload Kartu Pegawai (Karpeg)</label>
                                    <input type="file" name="file_karpeg" class="form-control bg-light shadow-none border-info border-opacity-50" required accept="application/pdf">
                                </div>
                            @endif

                        </div>
                        <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 16px 16px;">
                            <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border hover-shadow" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-glow">Kirim Berkas <i class="bi bi-send-fill ms-1"></i></button>
                        </div>
                    </form>
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