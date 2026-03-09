@extends('layouts.admin')
@section('title', 'Verifikasi Cuti')
@section('content')

    <style>
        /* Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }

        /* Efek Hover Tombol Glow */
        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }

        /* Efek Hover Baris Tabel (Floating Row) */
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { 
            background-color: #f8fbff !important; 
            transform: scale(1.01); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            border-left: 4px solid #0dcaf0; /* Garis Info Dinkes */
            z-index: 10; 
            position: relative;
        }

        /* ----------------------------------------------------
           ANIMASI MODAL CANGGIH (POP-UP)
           ---------------------------------------------------- */
        .modal-backdrop.show {
            opacity: 0.5 !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background-color: #000000;
        }
        .modal.fade .modal-dialog {
            transform: scale(0.85) translateY(20px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* ----------------------------------------------------
           KOTAK RINGKASAN PEGAWAI
           ---------------------------------------------------- */
        .summary-card {
            background-color: #f8fbff;
            border: 1px solid #e1ecff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .summary-label { font-size: 12px; color: #8392ab; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;}
        .summary-value { font-size: 14.5px; font-weight: 600; color: #212529; }

        /* Radio Button Custom (Tolak / Setuju) */
        .action-radio { display: none; }
        .action-label {
            display: block; width: 100%; padding: 12px; border: 2px solid #e2e8f0;
            border-radius: 10px; text-align: center; cursor: pointer; transition: all 0.3s;
            font-weight: 600; color: #64748b;
        }
        .action-radio:checked + .label-setuju { border-color: #198754; background-color: #198754; color: white; box-shadow: 0 5px 15px rgba(25,135,84,0.3); }
        .action-radio:checked + .label-tolak { border-color: #dc3545; background-color: #dc3545; color: white; box-shadow: 0 5px 15px rgba(220,53,69,0.3); }
        
        .action-content { display: none; margin-top: 20px; animation: fadeInUp 0.4s forwards; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0 text-dark">Verifikasi Pengajuan Cuti</h4>
            <small class="text-muted">Kelola dan verifikasi permohonan cuti dari seluruh unit Puskesmas.</small>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm animate-fade-up mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif
    @if($errors->any()) <div class="alert alert-danger border-0 shadow-sm animate-fade-up mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif

    <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-1" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('dinkes.cuti') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted fw-medium"><i class="bi bi-funnel-fill text-info"></i> Filter:</div>
                
                <div class="col-md-2">
                    <select name="bulan" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Bulan --</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="tahun" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Tahun --</option>
                        @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
                            <option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="unit" class="form-select form-select-sm border-light shadow-none bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Unit Kerja --</option>
                        @if(isset($listUnitKerja))
                            @foreach($listUnitKerja as $unit)
                                <option value="{{ $unit }}" {{ (isset($filterUnit) && $filterUnit == $unit) ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                        <button class="btn btn-light border-light text-muted" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div class="col-md-auto ms-auto d-flex gap-2">
                    <a href="{{ route('dinkes.cuti') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
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
                            <th class="border-0">Detail Cuti</th>
                            <th class="border-0">Dokumen</th>
                            <th class="border-0">Status</th>
                            <th class="text-center border-0" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @php $dataLoop = isset($dataCuti) ? $dataCuti : (isset($riwayatCuti) ? $riwayatCuti : []); @endphp
                        
                        @forelse($dataLoop as $c)
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $c->pegawai->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $c->pegawai->nip }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-building"></i> {{ $c->pegawai->unit_kerja }}</span></td>
                            <td>
                                <div class="fw-medium text-dark">{{ $c->jenis_cuti }}</div>
                                <small class="text-muted" style="font-size: 12px;">
                                    {{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - 
                                    {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="btn btn-sm btn-light border text-danger fw-bold transition-all shadow-sm">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                </a>
                            </td>
                            <td>
                                @if($c->status == 'menunggu') 
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill border border-warning border-opacity-50 shadow-sm"><i class="bi bi-hourglass-split"></i> Pending</span>
                                @elseif($c->status == 'disetujui') 
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25 shadow-sm"><i class="bi bi-check2-all"></i> Disetujui</span>
                                @else 
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25 shadow-sm"><i class="bi bi-x-circle"></i> Ditolak</span> 
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                @if($c->status == 'menunggu')
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 shadow btn-glow w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#verifModal{{ $c->id }}">
                                        Verifikasi
                                    </button>
                                @elseif($c->status == 'disetujui')
                                    <button class="btn btn-light btn-sm rounded-pill border text-success w-100" disabled><i class="bi bi-check2"></i> Selesai</button>
                                @elseif($c->status == 'ditolak')
                                    <button class="btn btn-light btn-sm rounded-pill border text-danger w-100" disabled><i class="bi bi-x-lg"></i> Ditolak</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-inbox fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Tidak ada antrean pengajuan cuti.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($dataLoop, 'links'))
            <div class="p-3 bg-white border-top">
                {{ $dataLoop->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>


    @foreach($dataLoop as $c)
        @if($c->status == 'menunggu')
        <div class="modal fade" id="verifModal{{ $c->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-shield-check me-2"></i> Verifikasi Pengajuan Cuti</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 text-start bg-white">
                        
                        <div class="summary-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-primary fs-6"><i class="bi bi-person-lines-fill me-1"></i> Informasi Pengaju</span>
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm"><i class="bi bi-file-pdf"></i> Buka Surat</a>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="summary-label">Nama Pegawai</div>
                                    <div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">NIP</div>
                                    <div class="summary-value">{{ $c->pegawai->nip }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Unit Kerja</div>
                                    <div class="summary-value">{{ $c->pegawai->unit_kerja }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Jenis Cuti</div>
                                    <div class="summary-value text-primary">{{ $c->jenis_cuti }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Tanggal Cuti</div>
                                    <div class="summary-value">{{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Alasan (Opsional)</div>
                                    <div class="summary-value text-muted">{{ $c->alasan ?: '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('cuti.verifikasi', $c->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h6 class="fw-bold mb-3 text-dark">Keputusan Tindakan:</h6>
                            <div class="row g-3 mb-2">
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="setuju" id="setuju{{ $c->id }}" class="action-radio" onchange="toggleAction('{{ $c->id }}', 'setuju')">
                                    <label class="action-label label-setuju shadow-sm" for="setuju{{ $c->id }}">
                                        <i class="bi bi-check-circle-fill fs-5 d-block mb-1"></i> Setujui Pengajuan
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="tolak" id="tolak{{ $c->id }}" class="action-radio" onchange="toggleAction('{{ $c->id }}', 'tolak')">
                                    <label class="action-label label-tolak shadow-sm" for="tolak{{ $c->id }}">
                                        <i class="bi bi-x-circle-fill fs-5 d-block mb-1"></i> Tolak Pengajuan
                                    </label>
                                </div>
                            </div>

                            <div id="boxSetuju{{ $c->id }}" class="action-content p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3">
                                <label class="form-label fw-bold text-success"><i class="bi bi-upload"></i> Upload SK Cuti Resmi (PDF)</label>
                                <input type="file" name="file_sk_resmi" id="fileSK{{ $c->id }}" class="form-control bg-white shadow-none border-success border-opacity-50" accept="application/pdf">
                                <small class="text-muted d-block mt-1">Sistem akan otomatis mengirimkan SK ini ke pihak Puskesmas.</small>
                            </div>

                            <div id="boxTolak{{ $c->id }}" class="action-content p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3">
                                <label class="form-label fw-bold text-danger"><i class="bi bi-pencil-square"></i> Alasan Penolakan</label>
                                <textarea name="catatan" id="catatanTolak{{ $c->id }}" class="form-control bg-white shadow-none border-danger border-opacity-50" rows="2" placeholder="Tuliskan mengapa pengajuan ini ditolak..."></textarea>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold border me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnSubmitVerif{{ $c->id }}" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm" disabled>Simpan Keputusan <i class="bi bi-send-fill ms-1"></i></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <script>
        function toggleAction(id, action) {
            const boxSetuju = document.getElementById('boxSetuju' + id);
            const boxTolak = document.getElementById('boxTolak' + id);
            const btnSubmit = document.getElementById('btnSubmitVerif' + id);
            const fileSK = document.getElementById('fileSK' + id);
            const catatan = document.getElementById('catatanTolak' + id);

            // Aktifkan tombol submit
            btnSubmit.disabled = false;

            if (action === 'setuju') {
                boxSetuju.style.display = 'block';
                boxTolak.style.display = 'none';
                btnSubmit.className = 'btn btn-success px-5 rounded-pill fw-bold shadow-sm btn-glow';
                
                // Set requirement rules
                fileSK.required = true;
                catatan.required = false;
                catatan.value = ''; // bersihkan catatan

            } else if (action === 'tolak') {
                boxSetuju.style.display = 'none';
                boxTolak.style.display = 'block';
                btnSubmit.className = 'btn btn-danger px-5 rounded-pill fw-bold shadow-sm btn-glow';
                
                // Set requirement rules
                fileSK.required = false;
                fileSK.value = ''; // bersihkan file
                catatan.required = true;
            }
        }
    </script>
@endsection