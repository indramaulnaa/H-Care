@extends('layouts.puskesmas')
@section('title', 'Pengajuan Cuti')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Pengajuan Cuti</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCuti">
            <i class="bi bi-plus-lg"></i> Buat Pengajuan Baru
        </button>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Pegawai</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end pe-4">SK / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatCuti as $c)
                    <tr>
                        <td class="ps-4">{{ $c->pegawai->nama_lengkap }}</td>
                        <td>{{ $c->jenis_cuti }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->tanggal_mulai)->format('d/m') }} - {{ \Carbon\Carbon::parse($c->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>
                            @if($c->status == 'menunggu') <span class="badge bg-warning text-dark">Proses Dinkes</span>
                            @elseif($c->status == 'disetujui') <span class="badge bg-success">Disetujui</span>
                            @else <span class="badge bg-danger">Ditolak</span> @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($c->status == 'disetujui' && $c->file_sk_resmi)
                                <a href="{{ asset('storage/'.$c->file_sk_resmi) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download SK
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalCuti" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white"><h5 class="modal-title">Form Cuti</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Pegawai</label>
                            <select name="id_pegawai" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                @foreach($semuaPegawai as $p) <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label>Jenis Cuti</label><select name="jenis_cuti" class="form-select"><option>Cuti Tahunan</option><option>Cuti Sakit</option><option>Cuti Melahirkan</option></select></div>
                        <div class="row"><div class="col-6 mb-3"><label>Mulai</label><input type="date" name="tanggal_mulai" class="form-control" required></div><div class="col-6 mb-3"><label>Selesai</label><input type="date" name="tanggal_selesai" class="form-control" required></div></div>
                        <div class="mb-3"><label>Surat Permohonan (PDF)</label><input type="file" name="file_permohonan" class="form-control" required accept="application/pdf"></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Kirim Pengajuan</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection