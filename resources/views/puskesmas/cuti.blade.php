@extends('layouts.puskesmas')
@section('title', 'Pengajuan Cuti')
@section('content')

    <style>
        /* Animasi Masuk (Fade In Up) untuk halaman utama */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }

        /* Efek Hover Tombol */
        .btn-hover-glow { transition: all 0.3s ease; }
        .btn-hover-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3) !important; }
        .btn-export-glow { transition: all 0.3s ease; }
        .btn-export-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(25, 135, 84, 0.3) !important; }

        /* Efek Hover Baris Tabel (Floating Row) */
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 4px solid transparent; }
        .hover-row:hover { 
            background-color: #f8fbff !important; 
            transform: scale(1.01); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            border-left: 4px solid #0d6efd; 
            z-index: 10; 
            position: relative;
        }

        /* ----------------------------------------------------
           ANIMASI MODAL CANGGIH (POP-UP)
           ---------------------------------------------------- */
        /* Efek latar belakang blur (Glassmorphism) */
        .modal-backdrop.show {
            opacity: 0.5 !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background-color: #000000;
        }
        
        /* Efek muncul memantul (Spring/Bounce Zoom) */
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
        .summary-label {
            font-size: 12px;
            color: #8392ab;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 14.5px;
            font-weight: 600;
            color: #212529;
        }

        /* Style Modal Form Utama */
        .form-section { background-color: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 1px solid #f0f0f0; transition: all 0.3s;}
        .form-section:hover { border-color: #d1e3ff; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.05); }
        .form-section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #212529; }
        .readonly-input { background-color: #f8f9fa !important; color: #6c757d; border: 1px solid #e9ecef; }
        .upload-zone { border: 2px dashed #a5c3e8; border-radius: 12px; padding: 40px 20px; text-align: center; background-color: #f8fbff; cursor: pointer; transition: all 0.3s; }
        .upload-zone:hover { background-color: #eaf2fb; border-color: #0d6efd; transform: translateY(-2px);}
        .upload-icon { font-size: 3rem; color: #20c997; margin-bottom: 10px; transition: all 0.3s;}
        .upload-zone:hover .upload-icon { transform: scale(1.1); }
        .custom-file-btn { border: 1px solid #20c997; color: #20c997; background: transparent; padding: 8px 24px; border-radius: 8px; font-weight: 500; display: inline-block; margin-top: 15px;}
        .custom-file-btn:hover { background: #20c997; color: #fff; }
        #fileInput { display: none; }
        .custom-autocomplete-wrapper { position: relative; }
        .custom-autocomplete-list { position: absolute; top: 100%; left: 0; z-index: 1050; width: 100%; max-height: 200px; overflow-y: auto; background-color: #fff; border: 1px solid #86b7fe; border-top: none; border-radius: 0 0 8px 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: none; }
        .pegawai-option { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background-color 0.2s; }
        .pegawai-option:last-child { border-bottom: none; }
        .pegawai-option:hover { background-color: #f8fbff; padding-left: 20px; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h4 class="fw-bold m-0 text-dark">Riwayat Pengajuan Cuti</h4>
            <small class="text-muted">Pantau dan kelola riwayat pengajuan cuti pegawai Puskesmas.</small>
        </div>
        <button class="btn btn-primary px-4 rounded-pill shadow-sm btn-hover-glow fw-medium" data-bs-toggle="modal" data-bs-target="#modalCuti">
            <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan
        </button>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm animate-fade-up">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger border-0 shadow-sm animate-fade-up">{{ $errors->first() }}</div> @endif

    <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-1" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('puskesmas.cuti') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-auto text-muted fw-medium"><i class="bi bi-funnel-fill text-primary"></i> Filter:</div>
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
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control border-light bg-light shadow-none" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                        <button class="btn btn-light border-light text-muted" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-auto ms-auto d-flex gap-2">
                    <a href="{{ route('puskesmas.cuti') }}" class="btn btn-sm btn-light border text-muted transition-all hover-shadow"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                    <button type="submit" name="export" value="1" class="btn btn-sm btn-success shadow-sm btn-export-glow fw-medium">
                        <i class="bi bi-file-earmark-excel"></i> Export Rekap
                    </button>
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
                            <th class="ps-4 py-3 border-0">Nama Pegawai</th>
                            <th class="border-0">Unit Kerja</th>
                            <th class="border-0">Jenis Cuti</th>
                            <th class="border-0">Dokumen</th>
                            <th class="border-0">Status</th>
                            <th class="text-center border-0" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($riwayatCuti as $c)
                        <tr class="hover-row border-bottom">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark" style="font-size: 14.5px;">{{ $c->pegawai->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 12px; font-family: monospace;">NIP: {{ $c->pegawai->nip }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $c->pegawai->unit_kerja }}</span></td>
                            <td>
                                <div class="fw-medium text-dark">{{ $c->jenis_cuti }}</div>
                                <small class="text-muted" style="font-size: 12px;">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - 
                                    {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="text-decoration-none text-danger fw-bold small transition-all d-inline-block hover-scale">
                                    <i class="bi bi-file-earmark-pdf fs-5 align-middle"></i> Lihat
                                </a>
                            </td>
                            <td>
                                @if($c->status == 'menunggu') <span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill border border-warning border-opacity-25 shadow-sm">Pending</span>
                                @elseif($c->status == 'disetujui') <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill border border-primary border-opacity-10 shadow-sm">Disetujui</span>
                                @else <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-10 shadow-sm">Ditolak</span> @endif
                            </td>
                            
                            <td class="text-center">
                                @if($c->status == 'menunggu')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm transition-all w-100" data-bs-toggle="modal" data-bs-target="#batalModal{{ $c->id }}" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan
                                    </button>
                                @elseif($c->status == 'disetujui' && $c->file_sk_resmi)
                                    <a href="{{ asset('storage/'.$c->file_sk_resmi) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm transition-all w-100" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-download me-1"></i> Download SK
                                    </a>
                                @elseif($c->status == 'ditolak')
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm transition-all w-100" data-bs-toggle="modal" data-bs-target="#alasanModal{{ $c->id }}" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-info-circle me-1"></i> Lihat Alasan
                                    </button>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-folder-x fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="text-muted fw-medium">Belum ada riwayat pengajuan cuti.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @foreach($riwayatCuti as $c)
        @if($c->status == 'menunggu')
        <div class="modal fade" id="batalModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header bg-danger text-white border-0 p-3">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Batalkan Pengajuan</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        
                        <div class="summary-card text-start">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-dark fs-6">Ringkasan Pegawai</span>
                                <span class="badge bg-warning text-dark bg-opacity-25 px-2 py-1">Pending</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="summary-label">NIP</div>
                                    <div class="summary-value">{{ $c->pegawai->nip }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Nama Pegawai</div>
                                    <div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Unit Kerja</div>
                                    <div class="summary-value">{{ $c->pegawai->unit_kerja }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Jenis Cuti</div>
                                    <div class="summary-value">{{ $c->jenis_cuti }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-2 text-dark">Apakah Anda yakin?</h5>
                        <p class="text-muted small mb-4">Data pengajuan cuti ini belum diproses oleh Dinkes. Jika dibatalkan, data akan <strong>dihapus permanen</strong> dari sistem.</p>
                        
                        <form action="{{ route('cuti.destroy', $c->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light w-50 fw-bold rounded-pill border" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger w-50 fw-bold rounded-pill shadow-sm">Ya, Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($c->status == 'ditolak')
        <div class="modal fade" id="alasanModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header bg-danger text-white border-0 p-3">
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-x-circle-fill me-2"></i> Pengajuan Ditolak</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        
                        <div class="summary-card">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-3">
                                <span class="fw-bold text-dark fs-6">Ringkasan Pegawai</span>
                                <span class="badge bg-danger text-danger bg-opacity-10 px-2 py-1 border border-danger border-opacity-25">Ditolak</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="summary-label">NIP</div>
                                    <div class="summary-value">{{ $c->pegawai->nip }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Nama Pegawai</div>
                                    <div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Puskesmas Asal</div>
                                    <div class="summary-value">{{ $c->pegawai->unit_kerja }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-label">Jenis Cuti</div>
                                    <div class="summary-value">{{ $c->jenis_cuti }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger border-danger border-opacity-25 mb-0" style="border-radius: 12px; background-color: #fff5f5;">
                            <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-chat-left-text-fill me-1"></i> Catatan dari Dinas Kesehatan:</h6>
                            <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $c->keterangan_admin ?? 'Tidak ada catatan yang dilampirkan.' }}</p>
                        </div>

                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border" data-bs-dismiss="modal">Tutup Pesan</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <div class="modal fade" id="modalCuti" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa; border-radius: 16px;">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div>
                        <h4 class="modal-title fw-bold text-dark">Formulir Pengajuan Cuti</h4>
                        <p class="text-muted small m-0">Lengkapi data di bawah untuk mengajukan cuti ke Dinas Kesehatan</p>
                    </div>
                    <button type="button" class="btn-close bg-light rounded-circle p-2 shadow-sm" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4 py-4">
                    <div class="form-section shadow-sm">
                        <div class="form-section-title text-primary"><i class="bi bi-person-badge-fill me-2"></i>1. Data Pegawai</div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Nama Pegawai</label>
                            <div class="custom-autocomplete-wrapper" id="searchPegawaiWrapper">
                                <div class="input-group input-group-lg shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-search"></i></span>
                                    <input type="text" id="searchPegawaiInput" class="form-control border-0 ps-0 shadow-none" placeholder="Ketik nama atau NIP pegawai..." autocomplete="off" style="font-size: 15px;">
                                </div>
                                <input type="hidden" name="id_pegawai" id="hiddenIdPegawai" required>
                                <div id="customDropdownList" class="custom-autocomplete-list mt-1">
                                    @foreach($semuaPegawai as $p)
                                        <div class="pegawai-option" data-id="{{ $p->id }}" data-nama="{{ $p->nama_lengkap }}" data-nip="{{ $p->nip }}" data-jabatan="{{ $p->jabatan }}" data-unit="{{ $p->unit_kerja }}">
                                            <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $p->nip }} • {{ $p->jabatan }}</small>
                                        </div>
                                    @endforeach
                                    <div id="noResultItem" class="p-4 text-center text-muted" style="display: none;"><i class="bi bi-emoji-frown me-1"></i> Nama tidak ditemukan</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">NIP</label>
                                <input type="text" id="autoNip" class="form-control readonly-input shadow-none rounded-3" readonly placeholder="Terisi otomatis...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Jabatan</label>
                                <input type="text" id="autoJabatan" class="form-control readonly-input shadow-none rounded-3" readonly placeholder="Terisi otomatis...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Unit Kerja</label>
                                <input type="text" id="autoUnit" class="form-control readonly-input shadow-none rounded-3" readonly placeholder="Terisi otomatis...">
                            </div>
                        </div>
                    </div>

                    <div class="form-section shadow-sm">
                        <div class="form-section-title text-primary"><i class="bi bi-calendar2-range-fill me-2"></i>2. Detail Cuti</div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jenis Cuti</label>
                            <select name="jenis_cuti" class="form-select shadow-none rounded-3" required>
                                <option value="Cuti Tahunan">Cuti Tahunan</option>
                                <option value="Cuti Sakit">Cuti Sakit</option>
                                <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                                <option value="Cuti Alasan Penting">Cuti Alasan Penting</option>
                                <option value="Cuti Besar">Cuti Besar</option>
                            </select>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control shadow-none rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control shadow-none rounded-3" required>
                            </div>
                        </div>
                        
                        <div id="durasiCutiBox" style="display: none;" class="mb-3 p-3 rounded-3 border transition-all">
                            <div id="durasiCutiText" class="fw-bold m-0 text-center" style="font-size: 15px;"></div>
                        </div>

                        <div>
                            <label class="form-label small fw-bold">Alasan Cuti <span class="text-muted fw-normal fst-italic">(Opsional)</span></label>
                            <textarea name="alasan" class="form-control shadow-none rounded-3" rows="3" placeholder="Tuliskan alasan pengajuan cuti secara singkat..."></textarea>
                        </div>
                    </div>

                    <div class="form-section shadow-sm mb-0">
                        <div class="form-section-title text-primary"><i class="bi bi-cloud-arrow-up-fill me-2"></i>3. Upload Dokumen</div>
                        <label class="form-label small fw-bold">Surat Permohonan Resmi (PDF/JPG)</label>
                        <div class="upload-zone mt-2" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cloud-arrow-up upload-icon"></i>
                            <h6 class="fw-bold mt-2 text-dark">Klik atau Seret file ke area ini</h6>
                            <p class="text-muted small mb-1">Upload scan surat permohonan yang telah ditandatangani</p>
                            <p class="text-muted" style="font-size: 11px;">Format: PDF, JPG, PNG (Maks 5MB)</p>
                            <span class="custom-file-btn rounded-pill"><i class="bi bi-search"></i> Pilih File dari Perangkat</span>
                        </div>
                        <input type="file" name="file_permohonan" id="fileInput" accept=".pdf, .jpg, .jpeg, .png" required>
                        <div id="fileNameDisplay" class="mt-3 text-success fw-bold text-center animate-fade-up" style="display: none; font-size: 15px;">
                            <i class="bi bi-check-circle-fill fs-5 align-middle me-1"></i> <span class="text-dark">File siap diupload:</span> <span id="fileNameText" class="text-primary border-bottom border-primary"></span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top p-4 bg-white" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted hover-shadow" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-hover-glow">Kirim Pengajuan <i class="bi bi-send-fill ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. SMART UI: KALKULASI DURASI CUTI ---
            const tglMulai = document.getElementById('tanggal_mulai');
            const tglSelesai = document.getElementById('tanggal_selesai');
            const durasiBox = document.getElementById('durasiCutiBox');
            const durasiText = document.getElementById('durasiCutiText');
            const btnSubmit = document.getElementById('btnSubmit');

            function hitungDurasi() {
                if (tglMulai.value && tglSelesai.value) {
                    const start = new Date(tglMulai.value);
                    const end = new Date(tglSelesai.value);
                    const diffTime = end - start;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                    durasiBox.style.display = 'block';

                    if (diffDays > 0) {
                        durasiBox.className = 'mb-3 p-3 rounded-3 border border-success bg-success bg-opacity-10 text-success animate-fade-up';
                        durasiText.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Total pengajuan: <strong class="fs-5">${diffDays} Hari</strong>`;
                        btnSubmit.disabled = false;
                    } else {
                        durasiBox.className = 'mb-3 p-3 rounded-3 border border-danger bg-danger bg-opacity-10 text-danger animate-fade-up';
                        durasiText.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> Error: Tanggal selesai harus lebih dari tanggal mulai!`;
                        btnSubmit.disabled = true;
                    }
                } else {
                    durasiBox.style.display = 'none';
                    btnSubmit.disabled = false;
                }
            }

            tglMulai.addEventListener('change', hitungDurasi);
            tglSelesai.addEventListener('change', hitungDurasi);


            // --- 2. LOGIC CUSTOM AUTOCOMPLETE ---
            const searchInput = document.getElementById('searchPegawaiInput');
            const hiddenInput = document.getElementById('hiddenIdPegawai');
            const dropdownList = document.getElementById('customDropdownList');
            const options = document.querySelectorAll('.pegawai-option');
            const noResult = document.getElementById('noResultItem');
            const wrapper = document.getElementById('searchPegawaiWrapper');

            function filterList() {
                let filter = searchInput.value.toLowerCase();
                let hasVisible = false;
                options.forEach(option => {
                    let nama = option.getAttribute('data-nama').toLowerCase();
                    let nip = option.getAttribute('data-nip').toLowerCase();
                    if (nama.includes(filter) || nip.includes(filter)) {
                        option.style.display = 'block';
                        hasVisible = true;
                    } else {
                        option.style.display = 'none';
                    }
                });
                noResult.style.display = hasVisible ? 'none' : 'block';
                dropdownList.style.display = 'block';
            }

            searchInput.addEventListener('focus', filterList);
            searchInput.addEventListener('keyup', filterList);

            options.forEach(option => {
                option.addEventListener('click', function(e) {
                    searchInput.value = this.getAttribute('data-nama');
                    hiddenInput.value = this.getAttribute('data-id');
                    document.getElementById('autoNip').value = this.getAttribute('data-nip');
                    document.getElementById('autoJabatan').value = this.getAttribute('data-jabatan');
                    document.getElementById('autoUnit').value = this.getAttribute('data-unit');
                    dropdownList.style.display = 'none';
                });
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) dropdownList.style.display = 'none';
            });

            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    hiddenInput.value = '';
                    document.getElementById('autoNip').value = '';
                    document.getElementById('autoJabatan').value = '';
                    document.getElementById('autoUnit').value = '';
                }
            });

            // --- 3. LOGIC UPLOAD FILE ---
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileNameText = document.getElementById('fileNameText');
            const uploadZone = document.getElementById('uploadZone');

            fileInput.addEventListener('change', function() {
                if(this.files.length > 0) {
                    fileNameText.textContent = this.files[0].name;
                    fileNameDisplay.style.display = 'block';
                    uploadZone.style.borderColor = '#20c997';
                    uploadZone.style.backgroundColor = '#eafbf5';
                    uploadZone.style.transform = 'scale(1.02)';
                    setTimeout(() => uploadZone.style.transform = 'scale(1)', 200);
                } else {
                    fileNameDisplay.style.display = 'none';
                    uploadZone.style.borderColor = '#a5c3e8';
                    uploadZone.style.backgroundColor = '#f8fbff';
                }
            });
        });
    </script>
@endsection