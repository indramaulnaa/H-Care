@extends('layouts.admin')

@section('title', 'E-Pensiun Monitoring')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold m-0">E-Pensiun Monitoring</h4>
        <small class="text-muted">Kelola dan pantau data pensiun pegawai di seluruh unit puskesmas.</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pensiun ({{ $filterTahun }})</div>
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
            <form action="{{ route('dinkes.pensiun') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted"><i class="bi bi-funnel-fill"></i> Filter:</div>
                
                <div class="col-md-2">
                    <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Bulan --</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $filterBulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        @for($y=date('Y'); $y<=date('Y')+5; $y++)
                            <option value="{{ $y }}" {{ $filterTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="unit" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($listUnitKerja as $unit)
                            <option value="{{ $unit }}" {{ $filterUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div class="col-md-auto ms-auto">
                    <a href="{{ route('dinkes.pensiun') }}" class="btn btn-sm btn-light border text-muted" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
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
                            <th class="ps-4">Nama Pegawai</th>
                            <th>Unit Kerja</th>
                            <th>Usia</th>
                            <th>Tgl Lahir & Pensiun</th>
                            <th>Status Akses</th>
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
                            <td>{{ $p->unit_kerja }}</td>
                            <td>{{ $usiaSekarang }} Tahun</td>
                            
                            <td>
                                <div class="text-dark"><small class="text-muted">Lahir:</small> {{ $p->tanggal_lahir->format('d M Y') }}</div>
                                <div class="text-danger fw-bold"><small class="text-muted fw-normal">Pensiun:</small> {{ $tglPensiun->translatedFormat('d M Y') }}</div>
                            </td>
                            
                            <td>
                                @if($p->is_pensiun_open)
                                    <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-unlock-fill"></i> Terbuka</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                @endif
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
                                    <form action="{{ route('dinkes.buka_akses', $p->id) }}" method="POST">
                                        @csrf
                                        @if($p->is_request_open_access)
                                            <button type="submit" class="btn btn-sm btn-warning rounded-pill px-3 shadow" title="Puskesmas meminta akses">
                                                <i class="bi bi-bell-fill"></i> Buka Akses (Diminta!)
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-key-fill"></i> Buka Akses
                                            </button>
                                        @endif
                                    </form>
                                @else
                                    @if($berkas && $berkas->status == 'menunggu')
                                        <button class="btn btn-sm btn-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#verifModal{{ $berkas->id }}">
                                            Verifikasi
                                        </button>
                                    @elseif($berkas && $berkas->status == 'disetujui')
                                        <button class="btn btn-sm btn-light border text-success" disabled><i class="bi bi-check2-all"></i> Selesai</button>
                                    @else
                                        <button class="btn btn-sm btn-light border text-muted" disabled>Menunggu Upload</button>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        @if($berkas)
                        <div class="modal fade" id="verifModal{{ $berkas->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Verifikasi: {{ $p->nama_lengkap }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-2 mb-3">
                                            <div class="col-4"><a href="{{ asset('storage/'.$berkas->file_sk_cpns) }}" target="_blank" class="btn btn-outline-dark w-100"><i class="bi bi-file-pdf"></i> SK CPNS</a></div>
                                            <div class="col-4"><a href="{{ asset('storage/'.$berkas->file_sk_pangkat) }}" target="_blank" class="btn btn-outline-dark w-100"><i class="bi bi-file-pdf"></i> SK Pangkat</a></div>
                                            <div class="col-4"><a href="{{ asset('storage/'.$berkas->file_karpeg) }}" target="_blank" class="btn btn-outline-dark w-100"><i class="bi bi-file-pdf"></i> Karpeg</a></div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between gap-2">
                                            <form action="{{ route('pensiun.verifikasi', $berkas->id) }}" method="POST" class="w-50">
                                                @csrf <input type="hidden" name="aksi" value="tolak">
                                                <div class="input-group">
                                                    <input type="text" name="catatan" class="form-control" placeholder="Alasan penolakan..." required>
                                                    <button class="btn btn-danger">Tolak</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('pensiun.verifikasi', $berkas->id) }}" method="POST" class="w-25 text-end">
                                                @csrf <input type="hidden" name="aksi" value="setuju">
                                                <button class="btn btn-success w-100">Setujui ✅</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Tidak ada data pegawai pensiun sesuai filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection