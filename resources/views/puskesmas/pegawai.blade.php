@extends('layouts.puskesmas')
@section('title', 'Data Pegawai')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0">Data Pegawai</h4>
            <small class="text-muted">Database Pegawai Aktif</small>
        </div>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus-fill"></i> Tambah Pegawai
        </button>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">NIP</th>
                        <th>Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th>Tgl Lahir</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semuaPegawai as $p)
                    <tr>
                        <td class="ps-4 text-muted">{{ $p->nip }}</td>
                        <td class="fw-bold">{{ $p->nama_lengkap }}</td>
                        <td>{{ $p->jabatan }}</td>
                        <td>{{ $p->tanggal_lahir->format('d M Y') }}</td>
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
                                <div class="modal-header bg-primary text-white"><h5 class="modal-title">Edit Pegawai</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body text-start">
                                        <div class="mb-3"><label>NIP</label><input type="text" name="nip" value="{{ $p->nip }}" class="form-control" required></div>
                                        <div class="mb-3"><label>Nama</label><input type="text" name="nama_lengkap" value="{{ $p->nama_lengkap }}" class="form-control" required></div>
                                        <div class="mb-3"><label>Jabatan</label><input type="text" name="jabatan" value="{{ $p->jabatan }}" class="form-control" required></div>
                                        <div class="row">
                                            <div class="col-6"><label>Tgl Lahir</label><input type="date" name="tanggal_lahir" value="{{ $p->tanggal_lahir->format('Y-m-d') }}" class="form-control" required></div>
                                            <div class="col-6"><label>Batas Pensiun</label>
                                                <select name="batas_usia_pensiun" class="form-select">
                                                    <option value="58" {{ $p->batas_usia_pensiun == 58 ? 'selected' : '' }}>58 Tahun</option>
                                                    <option value="60" {{ $p->batas_usia_pensiun == 60 ? 'selected' : '' }}>60 Tahun</option>
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
                                    <div class="text-danger mb-3"><i class="bi bi-exclamation-circle fs-1"></i></div>
                                    <h5 class="mb-3">Hapus Data?</h5>
                                    <p class="text-muted small">Data <strong>{{ $p->nama_lengkap }}</strong> akan dihapus permanen.</p>
                                    <form action="{{ route('pegawai.delete', $p->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white"><h5 class="modal-title">Tambah Pegawai</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" required></div>
                        <div class="mb-3"><label>Nama</label><input type="text" name="nama_lengkap" class="form-control" required></div>
                        <div class="mb-3"><label>Jabatan</label><input type="text" name="jabatan" class="form-control" required></div>
                        <div class="row">
                            <div class="col-6"><label>Tgl Lahir</label><input type="date" name="tanggal_lahir" class="form-control" required></div>
                            <div class="col-6"><label>Pensiun</label><select name="batas_usia_pensiun" class="form-select"><option value="58">58</option><option value="60">60</option></select></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection