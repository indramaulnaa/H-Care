@extends('layouts.puskesmas')

@section('title', 'E-Pensiun Monitoring')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold m-0">E-Pensiun Monitoring</h4>
        <small class="text-muted">Kelola dan pantau data pensiun pegawai di {{ Auth::user()->nama_unit }}.</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($pensiunBulanIniRealtime->count() > 0)
    <div class="card border-warning bg-warning bg-opacity-10 mb-4" id="peringatanPensiunBox">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="tutupPeringatan()" title="Tutup peringatan ini"></button>
        
        <div class="card-body d-flex align-items-start gap-3">
            <div class="bg-warning text-dark rounded p-2 mt-1">
                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold text-dark m-0">Peringatan: Pegawai Akan Pensiun Bulan Ini</h5>
                    <span class="badge bg-warning text-dark">{{ $pensiunBulanIniRealtime->count() }} Pegawai</span>
                </div>
                <p class="text-muted small mb-2">Segera lengkapi dokumen pensiun untuk pegawai berikut:</p>
                
                <div class="d-flex flex-wrap gap-2 pe-4"> 
                    @foreach($pensiunBulanIniRealtime as $p)
                    <div class="bg-white border rounded p-2 px-3 shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-secondary"></i>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ $p->nip }}</div>
                            <div class="text-danger fw-bold" style="font-size: 10px;">
                                {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pensiun ({{ $filterTahun ?? date('Y') }})</div>
                    <h2 class="fw-bold m-0 text-primary">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-secondary h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Belum Upload</div>
                    <h2 class="fw-bold m-0 text-secondary">{{ $stats['belum_upload'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Menunggu Verifikasi</div>
                    <h2 class="fw-bold m-0 text-warning">{{ $stats['menunggu'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Lengkap</div>
                    <h2 class="fw-bold m-0 text-success">{{ $stats['lengkap'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <form action="{{ route('puskesmas.pensiun') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted"><i class="bi bi-funnel-fill"></i> Filter:</div>
                
                <div class="col-md-2">
                    <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Bulan --</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        @for($y=date('Y'); $y<=date('Y')+5; $y++)
                            <option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div class="col-md-auto ms-auto">
                    <a href="{{ route('puskesmas.pensiun') }}" class="btn btn-sm btn-light border text-muted">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4">Nama & NIP</th>
                            <th>Usia</th>
                            <th>Tgl Lahir & Pensiun</th>
                            <th>Status Dokumen</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPensiun as $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                                $berkas = $p->berkas_pensiun;
                                $usiaSekarang = \Carbon\Carbon::parse($p->tanggal_lahir)->age;
                            @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>{{ $usiaSekarang }} Tahun</td>
                            
                            <td>
                                <div class="text-dark"><small class="text-muted">Lahir:</small> {{ $p->tanggal_lahir->format('d M Y') }}</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td>
                                @if(!$berkas)
                                    <span class="badge bg-light text-secondary border">Belum Upload</span>
                                @elseif($berkas->status == 'menunggu')
                                    <span class="badge bg-warning text-dark bg-opacity-75">Menunggu Verifikasi</span>
                                @elseif($berkas->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success">Lengkap</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Revisi</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                @if(!$p->is_pensiun_open)
                                    @if($p->is_request_open_access)
                                        <button class="btn btn-warning btn-sm bg-opacity-25 text-dark border-warning" disabled>
                                            <i class="bi bi-hourglass-split"></i> Menunggu Dinkes
                                        </button>
                                    @else
                                        <form action="{{ route('puskesmas.request_akses', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm">
                                                <i class="bi bi-bell-fill"></i> Minta Akses Upload
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if(!$berkas)
                                        <button class="btn btn-light border btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}">
                                            <i class="bi bi-upload text-primary"></i> Upload Berkas
                                        </button>
                                    @elseif($berkas->status == 'menunggu')
                                        <span class="text-muted small fst-italic">Proses Dinkes...</span>
                                    @elseif($berkas->status == 'disetujui')
                                        <button class="btn btn-success btn-sm" disabled><i class="bi bi-check2"></i> Selesai</button>
                                    @else
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $p->id }}">Revisi</button>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        @if($p->is_pensiun_open)
                        <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Upload Berkas Pensiun</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pensiun.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_pegawai" value="{{ $p->id }}">
                                        <div class="modal-body text-start">
                                            <div class="alert alert-info small mb-3">
                                                <strong>Pegawai:</strong> {{ $p->nama_lengkap }}<br>
                                                Pastikan file format <strong>PDF</strong> dan maksimal <strong>2MB</strong>.
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">1. SK CPNS</label>
                                                <input type="file" name="file_sk_cpns" class="form-control" required accept="application/pdf">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">2. SK Pangkat Terakhir</label>
                                                <input type="file" name="file_sk_pangkat" class="form-control" required accept="application/pdf">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">3. Kartu Pegawai (Karpeg)</label>
                                                <input type="file" name="file_karpeg" class="form-control" required accept="application/pdf">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Kirim Berkas</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2 opacity-50"></i>
                                Tidak ada data pegawai pensiun di filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek session untuk menyembunyikan box kuning
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