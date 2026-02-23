@extends('layouts.admin')

@section('title', 'Verifikasi Cuti')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0">Verifikasi Pengajuan Cuti</h4>
            <small class="text-muted">Tinjau dan setujui pengajuan cuti dari Puskesmas</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4">Nama Pegawai</th>
                            <th>Unit Kerja</th>
                            <th>Jenis Cuti</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataCuti as $cuti)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $cuti->pegawai->nama_lengkap }}</div>
                                <small class="text-muted">NIP: {{ $cuti->pegawai->nip }}</small>
                            </td>
                            <td>{{ $cuti->pegawai->unit_kerja }}</td>
                            <td>
                                {{ $cuti->jenis_cuti }} <br>
                                <small class="text-muted" style="font-size: 11px">
                                    {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $cuti->file_permohonan) }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-file-pdf text-danger"></i> Lihat PDF
                                </a>
                            </td>
                            <td>
                                @if($cuti->status == 'menunggu')
                                    <span class="badge bg-warning text-dark bg-opacity-25 text-opacity-75 px-3 py-2 rounded-pill">Pending</span>
                                @elseif($cuti->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Disetujui</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($cuti->status == 'menunggu')
                                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCuti{{ $cuti->id }}">
                                        <i class="bi bi-pencil-square"></i> Proses
                                    </button>
                                @else
                                    <button class="btn btn-light btn-sm text-muted rounded-pill px-3" disabled>Selesai</button>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="modalCuti{{ $cuti->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Proses Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Upload <strong>Surat Keputusan (SK)</strong> bertanda tangan Kadis.</p>
                                        <form action="{{ route('cuti.verifikasi', $cuti->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="file" name="file_sk_resmi" class="form-control" accept="application/pdf" required>
                                            </div>
                                            <button type="submit" name="aksi" value="setuju" class="btn btn-success w-100 mb-2">Setujui & Upload SK</button>
                                        </form>
                                        <hr>
                                        <form action="{{ route('cuti.verifikasi', $cuti->id) }}" method="POST">
                                            @csrf
                                            <input type="text" name="keterangan" class="form-control mb-2" placeholder="Alasan penolakan..." required>
                                            <button type="submit" name="aksi" value="tolak" class="btn btn-danger w-100">Tolak</button>
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
    </div>
@endsection