@extends('layouts.admin')
@section('title', 'Verifikasi Cuti')
@section('content')

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }

        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25) !important; }

        /* Tabel Premium */
        .premium-table th { background-color: #f8fafc !important; color: #64748b !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; padding: 15px 20px !important; border-bottom: 2px solid #e2e8f0 !important; border-top: none !important; }
        .premium-table td { padding: 16px 20px !important; vertical-align: middle; border-bottom: 1px solid #f1f5f9 !important; color: #334155; }
        
        .hover-row { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border-left: 3px solid transparent; }
        .hover-row:hover { background-color: #f8fbff !important; transform: translateX(4px); box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 3px solid #0d6efd; z-index: 10; position: relative; }

        /* Desain Lencana (Badges) Lembut */
        .badge-soft { padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.3px; display: inline-flex; align-items: center; gap: 5px; }
        .badge-soft-primary { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-soft-warning { background-color: #fefce8; color: #d97706; border: 1px solid #fde68a; }
        .badge-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-soft-danger { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-soft-secondary { background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

        /* Filter Kotak */
        .filter-box { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 25px; }
        .filter-input { background-color: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 0.85rem; padding: 8px 12px; box-shadow: none !important; transition: all 0.3s; }
        .filter-input:focus { background-color: white; border-color: #0d6efd; }

        /* Modal Kustom */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }
        .summary-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .summary-label { font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .summary-value { font-size: 0.95rem; font-weight: 600; color: #1e293b; }

        /* Radio Button Aksi Modal */
        .action-radio { display: none; }
        .action-label { display: block; width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; font-weight: 600; color: #64748b; background: white; }
        .action-radio:checked + .label-setuju { border-color: #10b981; background-color: #f0fdf4; color: #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15); }
        .action-radio:checked + .label-tolak { border-color: #ef4444; background-color: #fef2f2; color: #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15); }
        .action-content { display: none; margin-top: 15px; animation: fadeInUp 0.4s forwards; }
        
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(13, 110, 253, 0); } 100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); } }
        .pulse-btn { animation: pulse-ring 2s infinite; }
    </style>

    <div class="d-flex justify-content-between align-items-end mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Verifikasi Pengajuan Cuti</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola dan verifikasi permohonan cuti dari seluruh unit Puskesmas Kabupaten Batang.</p>
        </div>
    </div>

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm animate-fade-up mb-4" style="border-radius: 12px; background-color: #f0fdf4; color: #16a34a;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div> 
    @endif
    
    @if($errors->any()) 
        <div class="alert alert-danger border-0 shadow-sm animate-fade-up mb-4" style="border-radius: 12px; background-color: #fef2f2; color: #dc2626;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="filter-box animate-fade-up delay-1">
        <form action="{{ route('dinkes.cuti') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-auto d-flex align-items-center gap-2 text-primary fw-bold" style="font-size: 0.9rem;">
                <i class="bi bi-funnel-fill fs-5"></i> Filter Data:
            </div>
            
            <div class="col-md-2">
                <select name="bulan" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ (isset($filterBulan) && $filterBulan == $i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="col-md-2">
                <select name="tahun" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
                        <option value="{{ $y }}" {{ (isset($filterTahun) && $filterTahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <select name="unit" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Semua Unit Kerja</option>
                    @if(isset($listUnitKerja))
                        @foreach($listUnitKerja as $unit)
                            <option value="{{ $unit }}" {{ (isset($filterUnit) && $filterUnit == $unit) ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama / NIP..." value="{{ $search ?? '' }}">
                    <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc;"><i class="bi bi-search text-muted"></i></button>
                </div>
            </div>

            <div class="col-md-auto ms-auto">
                <a href="{{ route('dinkes.cuti') }}" class="btn btn-light shadow-sm text-secondary border" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table mb-0">
                    <thead>
                        <tr>
                            <th width="25%">Nama Pegawai & NIP</th>
                            <th width="15%">Unit Kerja</th>
                            <th width="20%">Detail Cuti</th>
                            <th width="10%" class="text-center">Dokumen</th>
                            <th width="15%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Aksi Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $dataLoop = isset($dataCuti) ? $dataCuti : (isset($riwayatCuti) ? $riwayatCuti : []); @endphp
                        
                        @forelse($dataLoop as $c)
                        <tr class="hover-row">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        {{ substr($c->pegawai->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $c->pegawai->nama_lengkap }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem; font-family: monospace; letter-spacing: 0.5px;">{{ $c->pegawai->nip }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge-soft badge-soft-secondary">
                                    <i class="bi bi-hospital"></i> {{ $c->pegawai->unit_kerja }}
                                </span>
                            </td>
                            
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $c->jenis_cuti }}</div>
                                <div style="color: #64748b; font-size: 0.8rem; margin-top: 2px;">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - 
                                    {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="btn btn-light btn-sm text-danger fw-bold border-danger border-opacity-25" style="border-radius: 8px; font-size: 0.75rem; padding: 5px 12px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                                    <i class="bi bi-file-earmark-pdf-fill fs-6 align-middle me-1"></i> PDF
                                </a>
                            </td>
                            
                            <td class="text-center">
                                @if($c->status == 'menunggu') 
                                    <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Pending</span>
                                @elseif($c->status == 'diproses') 
                                    <span class="badge-soft badge-soft-primary"><i class="bi bi-search"></i> Direview</span>
                                @elseif($c->status == 'disetujui') 
                                    <span class="badge-soft badge-soft-success"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                                @else 
                                    <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span> 
                                @endif
                            </td>

                            <td class="text-center">
                                @if($c->status == 'menunggu')
                                    <form action="{{ route('cuti.diproses', $c->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold text-dark shadow-sm" style="border-radius: 8px; font-size: 0.8rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                            <i class="bi bi-lock-fill"></i> Proses Berkas
                                        </button>
                                    </form>
                                @elseif($c->status == 'diproses')
                                    <button class="btn btn-primary btn-sm w-100 fw-bold shadow-sm pulse-btn" data-bs-toggle="modal" data-bs-target="#verifModal{{ $c->id }}" style="border-radius: 8px; font-size: 0.8rem;">
                                        <i class="bi bi-shield-check"></i> Verifikasi
                                    </button>
                                @elseif($c->status == 'disetujui')
                                    <span class="text-success fw-bold" style="font-size: 0.85rem;"><i class="bi bi-check2-all fs-5 align-middle"></i> Selesai</span>
                                @elseif($c->status == 'ditolak')
                                    <span class="text-danger fw-bold" style="font-size: 0.85rem;"><i class="bi bi-x-lg fs-5 align-middle"></i> Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-inbox text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Belum ada pengajuan cuti yang masuk.</span>
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
        @if($c->status == 'menunggu' || $c->status == 'diproses')
        <div class="modal fade" id="verifModal{{ $c->id }}" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                        <div>
                            <h5 class="modal-title fw-bold m-0"><i class="bi bi-shield-check me-2"></i> Keputusan Verifikasi Cuti</h5>
                            <small class="text-white-50 mt-1 d-block">Tinjau kelengkapan dan tentukan status pengajuan pegawai.</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white">
                        <div class="summary-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-10 pb-3 mb-3">
                                <span class="fw-bold text-primary fs-6"><i class="bi bi-person-lines-fill me-2"></i> Informasi Pengaju</span>
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold"><i class="bi bi-file-pdf-fill me-1"></i> Buka Surat PDF</a>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="summary-label">Nama Pegawai</div>
                                    <div class="summary-value">{{ $c->pegawai->nama_lengkap }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Nomor Induk Pegawai (NIP)</div>
                                    <div class="summary-value" style="font-family: monospace;">{{ $c->pegawai->nip }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Unit Puskesmas</div>
                                    <div class="summary-value">{{ $c->pegawai->unit_kerja }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Jenis Cuti</div>
                                    <div class="summary-value text-primary">{{ $c->jenis_cuti }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Durasi Cuti</div>
                                    <div class="summary-value">{{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-label">Alasan (Opsional)</div>
                                    <div class="summary-value text-muted" style="font-weight: 400;">{{ $c->alasan ?: 'Tidak mencantumkan alasan' }}</div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('cuti.verifikasi', $c->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h6 class="fw-bold mb-3 mt-4 text-dark"><i class="bi bi-ui-checks-grid text-primary me-2"></i> Tentukan Keputusan Akhir:</h6>
                            <div class="row g-3 mb-2">
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="setuju" id="setuju{{ $c->id }}" class="action-radio" onchange="toggleAction('{{ $c->id }}', 'setuju')">
                                    <label class="action-label label-setuju shadow-sm" for="setuju{{ $c->id }}">
                                        <i class="bi bi-check-circle-fill fs-4 d-block mb-1"></i> Setujui Pengajuan
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" name="aksi" value="tolak" id="tolak{{ $c->id }}" class="action-radio" onchange="toggleAction('{{ $c->id }}', 'tolak')">
                                    <label class="action-label label-tolak shadow-sm" for="tolak{{ $c->id }}">
                                        <i class="bi bi-x-circle-fill fs-4 d-block mb-1"></i> Tolak Pengajuan
                                    </label>
                                </div>
                            </div>

                            <div id="boxSetuju{{ $c->id }}" class="action-content p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4">
                                <label class="form-label fw-bold text-success mb-3"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload SK Cuti Resmi (Format PDF)</label>
                                <input type="file" name="file_sk_resmi" id="fileSK{{ $c->id }}" class="form-control form-control-lg bg-white shadow-sm border-success border-opacity-50" accept="application/pdf">
                                <small class="text-success opacity-75 d-block mt-2"><i class="bi bi-info-circle-fill me-1"></i> Dokumen SK ini akan otomatis dikirim ke beranda Admin Puskesmas asal.</small>
                            </div>

                            <div id="boxTolak{{ $c->id }}" class="action-content p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4">
                                <label class="form-label fw-bold text-danger mb-3"><i class="bi bi-pencil-square me-1"></i> Berikan Alasan Penolakan</label>
                                <textarea name="catatan" id="catatanTolak{{ $c->id }}" class="form-control bg-white shadow-sm border-danger border-opacity-50" rows="3" placeholder="Tuliskan dengan jelas mengapa pengajuan cuti ini ditolak..."></textarea>
                            </div>

                            <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold border text-secondary" data-bs-dismiss="modal">Batalkan</button>
                                <button type="submit" id="btnSubmitVerif{{ $c->id }}" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm" disabled>Simpan Keputusan <i class="bi bi-send-fill ms-2"></i></button>
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