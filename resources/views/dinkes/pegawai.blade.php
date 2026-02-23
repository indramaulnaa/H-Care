@extends('layouts.admin')
@section('title', 'Data Pegawai Se-Kabupaten')
@section('content')
    <div class="mb-4">
        <h4 class="fw-bold m-0">Data Pegawai (Master)</h4>
        <small class="text-muted">Kelola seluruh database pegawai Puskesmas & Dinas Kesehatan.</small>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-auto">
                    <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg"></i> Tambah Pegawai
                    </button>
                </div>

                <div class="col-md-9 ms-auto">
                    <form action="{{ route('dinkes.pegawai') }}" method="GET">
                        <div class="row g-2 justify-content-end">
                            <div class="col-md-4">
                                <select name="unit" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Semua Unit Kerja --</option>
                                    <option value="Dinas Kesehatan" {{ $filterUnit == 'Dinas Kesehatan' ? 'selected' : '' }}>Dinas Kesehatan</option>
                                    @foreach($listUnitKerja as $unit)
                                        @if($unit != 'Dinas Kesehatan')
                                            <option value="{{ $unit }}" {{ $filterUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..." value="{{ $search }}">
                                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                            @if($search || $filterUnit)
                            <div class="col-md-auto">
                                <a href="{{ route('dinkes.pegawai') }}" class="btn btn-light border" title="Reset"><i class="bi bi-x-lg"></i></a>
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
                            <th class="ps-4">No</th>
                            <th>Nama & NIP</th>
                            <th>Unit Kerja</th>
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
                                <div class="fw-bold">{{ $p->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->unit_kerja }}</span>
                            </td>
                            <td>{{ $p->jabatan }}</td>
                            <td>
                                <small class="text-muted d-block">Lahir: {{ $p->tanggal_lahir->format('d M Y') }}</small>
                                <small class="text-danger fw-bold">Pensiun: {{ $tglPensiun->format('Y') }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $p->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white"><h5 class="modal-title">Edit Data Pegawai</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3"><label>NIP</label><input type="text" name="nip" value="{{ $p->nip }}" class="form-control" required></div>
                                            <div class="mb-3"><label>Nama</label><input type="text" name="nama_lengkap" value="{{ $p->nama_lengkap }}" class="form-control" required></div>
                                            <div class="mb-3"><label>Unit Kerja (Mutasi)</label>
                                                <select name="unit_kerja" class="form-select">
                                                    <option value="Dinas Kesehatan" {{ $p->unit_kerja == 'Dinas Kesehatan' ? 'selected' : '' }}>Dinas Kesehatan</option>
                                                    @foreach($listUnitKerja as $u)
                                                        @if($u != 'Dinas Kesehatan')
                                                            <option value="{{ $u }}" {{ $p->unit_kerja == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-6"><label>Jabatan</label><input type="text" name="jabatan" value="{{ $p->jabatan }}" class="form-control" required></div>
                                                <div class="col-6"><label>Tgl Lahir</label><input type="date" name="tanggal_lahir" value="{{ $p->tanggal_lahir->format('Y-m-d') }}" class="form-control" required></div>
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
                                        <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                                        <h5 class="mt-3">Hapus Data?</h5>
                                        <p class="text-muted small">Data {{ $p->nama_lengkap }} akan dihapus permanen.</p>
                                        <form action="{{ route('pegawai.delete', $p->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3">
                {{ $semuaPegawai->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white"><h5 class="modal-title">Tambah Pegawai Baru</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" required></div>
                        <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required></div>
                        
                        <div class="mb-3"><label>Unit Kerja</label>
                            <select name="unit_kerja" class="form-select" required>
                                <option value="">-- Pilih Unit --</option>
                                <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                @foreach($listUnitKerja as $u)
                                    @if($u != 'Dinas Kesehatan') <option value="{{ $u }}">{{ $u }}</option> @endif
                                @endforeach
                                <option value="Lainnya">Lainnya (Input Manual nanti)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3"><label>Jabatan</label><input type="text" name="jabatan" class="form-control" required></div>
                            <div class="col-6 mb-3"><label>Tgl Lahir</label><input type="date" name="tanggal_lahir" class="form-control" required></div>
                        </div>
                        <div class="mb-3"><label>Batas Pensiun</label>
                            <select name="batas_usia_pensiun" class="form-select">
                                <option value="58">58 Tahun (Umum)</option>
                                <option value="60">60 Tahun (Fungsional)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan Data</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection