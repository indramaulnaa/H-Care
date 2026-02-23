@extends('layouts.puskesmas')
@section('title', 'Pengajuan Cuti')
@section('content')

    <style>
        .form-section { background-color: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 1px solid #f0f0f0; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; color: #212529; }
        .readonly-input { background-color: #f8f9fa !important; color: #6c757d; border: 1px solid #e9ecef; }
        
        /* Upload Area */
        .upload-zone {
            border: 2px dashed #a5c3e8; border-radius: 12px; padding: 40px 20px;
            text-align: center; background-color: #f8fbff; cursor: pointer; transition: all 0.3s;
        }
        .upload-zone:hover { background-color: #eaf2fb; border-color: #0d6efd; }
        .upload-icon { font-size: 3rem; color: #20c997; margin-bottom: 10px; }
        .custom-file-btn { border: 1px solid #20c997; color: #20c997; background: transparent; padding: 8px 24px; border-radius: 8px; font-weight: 500; display: inline-block; margin-top: 15px;}
        .custom-file-btn:hover { background: #20c997; color: #fff; }
        #fileInput { display: none; }

        /* Custom Autocomplete List */
        .custom-autocomplete-wrapper { position: relative; }
        .custom-autocomplete-list {
            position: absolute; top: 100%; left: 0; z-index: 1050; width: 100%; max-height: 200px;
            overflow-y: auto; background-color: #fff; border: 1px solid #86b7fe; border-top: none;
            border-radius: 0 0 8px 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: none;
        }
        .pegawai-option { padding: 10px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background-color 0.2s; }
        .pegawai-option:last-child { border-bottom: none; }
        .pegawai-option:hover { background-color: #f8fbff; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0">Riwayat Pengajuan Cuti</h4>
            <small class="text-muted">Pantau dan kelola riwayat pengajuan cuti pegawai Puskesmas.</small>
        </div>
        <button class="btn btn-primary px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCuti">
            <i class="bi bi-plus-lg"></i> Buat Pengajuan
        </button>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger border-0 shadow-sm">{{ $errors->first() }}</div> @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="{{ route('puskesmas.cuti') }}" method="GET" class="row g-2 align-items-center">
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
                        <option value="">-- Semua Tahun --</option>
                        @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
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
                    <a href="{{ route('puskesmas.cuti') }}" class="btn btn-sm btn-light border text-muted">
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
                            <th class="ps-4">Nama Pegawai</th>
                            <th>Unit Kerja</th>
                            <th>Jenis Cuti</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatCuti as $c)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $c->pegawai->nama_lengkap }}</div>
                                <small class="text-muted">NIP: {{ $c->pegawai->nip }}</small>
                            </td>
                            <td>{{ $c->pegawai->unit_kerja }}</td>
                            
                            <td>
                                {{ $c->jenis_cuti }}<br>
                                <small class="text-muted" style="font-size: 12px;">
                                    {{ \Carbon\Carbon::parse($c->tanggal_mulai)->translatedFormat('d M') }} - 
                                    {{ \Carbon\Carbon::parse($c->tanggal_selesai)->translatedFormat('d M Y') }}
                                </small>
                            </td>
                            
                            <td>
                                <a href="{{ asset('storage/'.$c->file_permohonan) }}" target="_blank" class="text-decoration-none text-danger fw-bold small">
                                    <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                                </a>
                            </td>
                            <td>
                                @if($c->status == 'menunggu') <span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill">Pending</span>
                                @elseif($c->status == 'disetujui') <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Disetujui</span>
                                @else <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Ditolak</span> @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($c->status == 'disetujui' && $c->file_sk_resmi)
                                    <a href="{{ asset('storage/'.$c->file_sk_resmi) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-download"></i> Download SK
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pengajuan cuti yang sesuai dengan pencarian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCuti" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data" class="modal-content" style="background-color: #f8f9fa;">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div>
                        <h4 class="modal-title fw-bold">Formulir Pengajuan Cuti Pegawai</h4>
                        <p class="text-muted small m-0">Lengkapi formulir berikut untuk mengajukan cuti pegawai</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="form-section shadow-sm">
                        <div class="form-section-title">1. Data Pegawai</div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Nama Pegawai</label>
                            
                            <div class="custom-autocomplete-wrapper" id="searchPegawaiWrapper">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                    <input type="text" id="searchPegawaiInput" class="form-control border-start-0 ps-0" placeholder="Ketik nama atau NIP pegawai..." autocomplete="off">
                                </div>
                                <input type="hidden" name="id_pegawai" id="hiddenIdPegawai" required>
                                
                                <div id="customDropdownList" class="custom-autocomplete-list">
                                    @foreach($semuaPegawai as $p)
                                        <div class="pegawai-option" data-id="{{ $p->id }}" data-nama="{{ $p->nama_lengkap }}" data-nip="{{ $p->nip }}" data-jabatan="{{ $p->jabatan }}" data-unit="{{ $p->unit_kerja }}">
                                            <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $p->nip }} • {{ $p->jabatan }}</small>
                                        </div>
                                    @endforeach
                                    <div id="noResultItem" class="p-3 text-center text-muted" style="display: none;">Nama tidak ditemukan</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">NIP</label>
                                <input type="text" id="autoNip" class="form-control readonly-input" readonly placeholder="Terisi otomatis...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Jabatan</label>
                                <input type="text" id="autoJabatan" class="form-control readonly-input" readonly placeholder="Terisi otomatis...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Unit Kerja</label>
                                <input type="text" id="autoUnit" class="form-control readonly-input" readonly placeholder="Terisi otomatis...">
                            </div>
                        </div>
                    </div>

                    <div class="form-section shadow-sm">
                        <div class="form-section-title">2. Detail Cuti</div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jenis Cuti</label>
                            <select name="jenis_cuti" class="form-select" required>
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
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label small fw-bold">Alasan Cuti <span class="text-muted fw-normal fst-italic">(Opsional)</span></label>
                            <textarea name="alasan" class="form-control" rows="3" placeholder="Tuliskan alasan pengajuan cuti..."></textarea>
                        </div>
                    </div>

                    <div class="form-section shadow-sm">
                        <div class="form-section-title">3. Upload Dokumen</div>
                        <label class="form-label small fw-bold">Upload Surat Permohonan (PDF/JPG)</label>
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-upload upload-icon"></i>
                            <h6 class="fw-bold mt-2 text-dark">Seret dan lepas file di sini</h6>
                            <p class="text-muted small">atau klik tombol di bawah untuk memilih file</p>
                            <p class="text-muted" style="font-size: 11px;">Format yang didukung: PDF, JPG (Maksimal 5MB)</p>
                            <span class="custom-file-btn"><i class="bi bi-box-arrow-in-up"></i> Pilih File</span>
                        </div>
                        <input type="file" name="file_permohonan" id="fileInput" accept=".pdf, .jpg, .jpeg, .png" required>
                        <div id="fileNameDisplay" class="mt-3 text-success fw-bold text-center" style="display: none;">
                            <i class="bi bi-check-circle-fill"></i> File siap diupload: <span id="fileNameText"></span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4 bg-transparent">
                    <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" style="background-color: #20c997; border-color: #20c997;">Ajukan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // LOGIC CUSTOM AUTOCOMPLETE
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

            // LOGIC UPLOAD FILE
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
                } else {
                    fileNameDisplay.style.display = 'none';
                    uploadZone.style.borderColor = '#a5c3e8';
                    uploadZone.style.backgroundColor = '#f8fbff';
                }
            });
        });
    </script>
@endsection