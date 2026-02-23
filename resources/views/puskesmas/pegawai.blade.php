@extends('layouts.puskesmas')
@section('title', 'Data Pegawai')
@section('content')
    <div class="mb-4">
        <h4 class="fw-bold m-0">Data Pegawai</h4>
        <small class="text-muted">Kelola database pegawai aktif di {{ Auth::user()->nama_unit }}.</small>
    </div>

    @if(session('success')) <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif
    @if($errors->any()) <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">{{ $errors->first() }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-auto">
                    <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-person-plus-fill"></i> Tambah Pegawai
                    </button>
                </div>

                <div class="col-md-9 ms-auto">
                    <form action="{{ route('puskesmas.pegawai') }}" method="GET">
                        <div class="row g-2 justify-content-end">
                            <div class="col-md-4">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="nama_asc" {{ $sort == 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                                    <option value="tgl_lahir_asc" {{ $sort == 'tgl_lahir_asc' ? 'selected' : '' }}>Tgl Lahir (Paling Tua)</option>
                                    <option value="tgl_lahir_desc" {{ $sort == 'tgl_lahir_desc' ? 'selected' : '' }}>Tgl Lahir (Paling Muda)</option>
                                    <option value="pensiun_terdekat" {{ $sort == 'pensiun_terdekat' ? 'selected' : '' }}>Pensiun Terdekat</option>
                                    <option value="pensiun_terlama" {{ $sort == 'pensiun_terlama' ? 'selected' : '' }}>Pensiun Terlama</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                            @if($search || $sort != 'nama_asc')
                            <div class="col-md-auto">
                                <a href="{{ route('puskesmas.pegawai') }}" class="btn btn-light border" title="Reset"><i class="bi bi-x-lg"></i></a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th>Nama & NIP</th>
                            <th>Jabatan</th>
                            <th>Tgl Lahir / Pensiun</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaPegawai as $index => $p)
                            @php
                                $tglPensiun = \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($p->batas_usia_pensiun);
                            @endphp
                        <tr>
                            <td class="ps-4">{{ $semuaPegawai->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>{{ $p->jabatan }}</td>
                            <td>
                                <small class="text-muted d-block">Lahir: {{ $p->tanggal_lahir->format('d M Y') }}</small>
                                <small class="text-danger fw-bold">Pensiun: {{ $tglPensiun->format('d M Y') }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $p->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white"><h5 class="modal-title">Edit Pegawai</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                    <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3"><label class="form-label small fw-bold text-muted">NIP</label><input type="text" name="nip" value="{{ $p->nip }}" class="form-control" required></div>
                                            <div class="mb-3"><label class="form-label small fw-bold text-muted">Nama Lengkap</label><input type="text" name="nama_lengkap" value="{{ $p->nama_lengkap }}" class="form-control" required></div>
                                            <div class="mb-3"><label class="form-label small fw-bold text-muted">Jabatan</label><input type="text" name="jabatan" value="{{ $p->jabatan }}" class="form-control" required></div>
                                            <div class="row">
                                                <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ $p->tanggal_lahir->format('Y-m-d') }}" class="form-control" required></div>
                                                <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">Batas Usia Pensiun</label>
                                                    <select name="batas_usia_pensiun" class="form-select">
                                                        <option value="58" {{ $p->batas_usia_pensiun == 58 ? 'selected' : '' }}>58 Tahun (Umum)</option>
                                                        <option value="60" {{ $p->batas_usia_pensiun == 60 ? 'selected' : '' }}>60 Tahun (Fungsional)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalHapus{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center pt-4">
                                        <div class="text-danger mb-3"><i class="bi bi-exclamation-triangle fs-1"></i></div>
                                        <h5 class="mb-3">Hapus Data?</h5>
                                        <p class="text-muted small">Data <strong>{{ $p->nama_lengkap }}</strong> akan dihapus permanen dari sistem.</p>
                                        <form action="{{ route('pegawai.delete', $p->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger w-100">Ya, Hapus Data</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data pegawai yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $semuaPegawai->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white"><h5 class="modal-title">Tambah Pegawai Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label small fw-bold text-muted">NIP</label><input type="text" name="nip" class="form-control" required placeholder="Contoh: 198501152010011001"></div>
                        <div class="mb-3"><label class="form-label small fw-bold text-muted">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required placeholder="Tuliskan nama beserta gelar..."></div>
                        <div class="mb-3"><label class="form-label small fw-bold text-muted">Jabatan</label><input type="text" name="jabatan" class="form-control" required placeholder="Contoh: Perawat Pelaksana"></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="form-label small fw-bold text-muted">Batas Usia Pensiun</label>
                                <select name="batas_usia_pensiun" class="form-select">
                                    <option value="58">58 Tahun (Umum)</option>
                                    <option value="60">60 Tahun (Fungsional)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success px-4">Simpan Data</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection 