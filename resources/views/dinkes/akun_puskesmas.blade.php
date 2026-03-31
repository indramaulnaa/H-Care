@extends('layouts.admin')
@section('title', 'Manajemen Akun Puskesmas')
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
        .badge-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-soft-secondary { background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* Filter Kotak */
        .filter-box { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 25px; }
        .filter-input { background-color: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 0.85rem; padding: 8px 12px; box-shadow: none !important; transition: all 0.3s; }
        .filter-input:focus { background-color: white; border-color: #0d6efd; }

        /* Modal & Modern Input */
        .modal-backdrop.show { opacity: 0.6 !important; backdrop-filter: blur(5px); background-color: #0f172a; }
        .modal.fade .modal-dialog { transform: scale(0.9) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); opacity: 1; }

        .modern-input-wrapper { display: flex; align-items: center; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 15px; transition: all 0.3s ease; }
        .modern-input-wrapper:focus-within { background-color: #ffffff; border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); transform: translateY(-2px); }
        .modern-input-icon { color: #94a3b8; font-size: 1.2rem; margin-right: 12px; transition: all 0.3s ease; }
        .modern-input-wrapper:focus-within .modern-input-icon { color: #0d6efd; }
        .modern-input-wrapper input, .modern-input-wrapper select { border: none; background: transparent; box-shadow: none !important; padding: 5px 0; width: 100%; color: #334155; font-weight: 500; outline: none; }
        
        .action-btn { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s; }
        .action-btn:hover { transform: scale(1.1); }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Manajemen Akun Puskesmas</h3>
            <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola hak akses sistem untuk 21 Puskesmas di Kabupaten Batang.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm btn-glow fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus-fill me-2 fs-5"></i> Buat Akun Baru
        </button>
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
        <form action="{{ route('dinkes.akun') }}" method="GET">
            <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
                
                <div class="d-flex align-items-center gap-2 text-primary fw-bold flex-shrink-0" style="font-size: 0.95rem;">
                    <i class="bi bi-search fs-5"></i> <span>Pencarian:</span>
                </div>
                
                <div class="flex-grow-1" style="max-width: 500px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control filter-input border-end-0" placeholder="Cari Nama Akun / Unit Puskesmas..." value="{{ $search ?? '' }}">
                        <button class="btn filter-input border-start-0" type="submit" style="background: #f8fafc; z-index: 0;"><i class="bi bi-search text-muted"></i></button>
                    </div>
                </div>

                @if($search)
                <div class="flex-shrink-0">
                    <a href="{{ route('dinkes.akun') }}" class="btn btn-light shadow-sm text-secondary border d-flex align-items-center justify-content-center" style="border-radius: 8px; padding: 7px 15px;" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
                @endif

            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm animate-fade-up delay-2" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table premium-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Profil Pengguna</th>
                            <th>Username Login</th>
                            <th>Puskesmas Bertugas</th>
                            <th>Status Akses</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akunPuskesmas as $akun)
                        <tr class="hover-row">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 42px; height: 42px; flex-shrink: 0; font-size: 1.1rem;">
                                        {{ substr($akun->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #1e293b; font-size: 0.95rem;">{{ $akun->name }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem;">Terdaftar: {{ $akun->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-secondary" style="font-family: monospace; font-size: 0.85rem; padding: 6px 10px;">
                                    <i class="bi bi-box-arrow-in-right text-muted"></i> {{ $akun->username }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-primary">
                                    <i class="bi bi-hospital"></i> {{ $akun->nama_unit }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-success">
                                    <i class="bi bi-shield-check"></i> Aktif
                                </span>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary rounded-circle shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $akun->id }}" title="Edit Akun">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-outline-danger rounded-circle shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $akun->id }}" title="Hapus Akun">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="p-4 rounded-4 bg-light d-inline-block animate-fade-up">
                                    <i class="bi bi-people text-secondary opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
                                    <span class="text-muted fw-medium" style="font-size: 1.1rem;">Belum ada akun Puskesmas yang terdaftar.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-white border-top">{{ $akunPuskesmas->links() }}</div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <div>
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-person-plus-fill me-2"></i> Buat Akun Puskesmas</h5>
                        <small class="text-white-50 mt-1 d-block">Berikan akses sistem kepada Admin Puskesmas baru.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('akun.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Unit Puskesmas Penempatan</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-hospital-fill modern-input-icon"></i>
                                <select name="nama_unit" required>
                                    <option value="" disabled selected>-- Pilih Puskesmas --</option>
                                    @foreach($listPuskesmas as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Lengkap Pengguna</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-person-badge-fill modern-input-icon"></i>
                                <input type="text" name="name" required placeholder="Contoh: Admin Puskesmas Bandar">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Username (Untuk Login)</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-box-arrow-in-right modern-input-icon"></i>
                                <input type="text" name="username" required placeholder="Contoh: pkmbandar">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Password Login</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-key-fill modern-input-icon"></i>
                                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter rahasia">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm btn-glow">Simpan Akun <i class="bi bi-send-fill ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($akunPuskesmas as $akun)
    <div class="modal fade" id="modalEdit{{ $akun->id }}" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header text-white border-0 p-4" style="background: linear-gradient(135deg, #475569 0%, #334155 100%);">
                    <div>
                        <h5 class="modal-title fw-bold m-0"><i class="bi bi-pencil-square me-2"></i> Edit Akun Puskesmas</h5>
                        <small class="text-white-50 mt-1 d-block">Perbarui data atau reset password pengguna.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none opacity-75" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('akun.update', $akun->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4 bg-white">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Unit Puskesmas Penempatan</label>
                            <div class="modern-input-wrapper">
                                <i class="bi bi-hospital-fill modern-input-icon"></i>
                                <select name="nama_unit" required>
                                    @foreach($listPuskesmas as $p)
                                        <option value="{{ $p }}" {{ $akun->nama_unit == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Lengkap Pengguna</label>
                            <div class="modern-input-wrapper"><i class="bi bi-person-badge-fill modern-input-icon"></i><input type="text" name="name" value="{{ $akun->name }}" required></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Username Login</label>
                            <div class="modern-input-wrapper"><i class="bi bi-box-arrow-in-right modern-input-icon"></i><input type="text" name="username" value="{{ $akun->username }}" required></div>
                        </div>
                        <div class="mb-2 p-3 border rounded-3 bg-light">
                            <label class="form-label small fw-bold text-danger text-uppercase mb-2" style="letter-spacing: 0.5px;">Reset Password (Opsional)</label>
                            <div class="modern-input-wrapper bg-white border-danger border-opacity-25"><i class="bi bi-key-fill text-danger opacity-75"></i><input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diganti"></div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;"><i class="bi bi-info-circle"></i> Isi hanya jika Admin Puskesmas bersangkutan lupa password.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4 bg-light" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill text-muted border shadow-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark px-5 fw-bold rounded-pill shadow-sm btn-glow">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHapus{{ $akun->id }}" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-x-fill fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Hapus Akun Ini?</h5>
                    <p class="text-muted small mb-4">Akun akses untuk <strong>{{ $akun->nama_unit }}</strong> akan dihapus. Mereka tidak akan bisa login lagi ke sistem.</p>
                    <form action="{{ route('akun.delete', $akun->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm py-2">Ya, Hapus Permanen</button>
                            <button type="button" class="btn btn-light w-100 fw-bold rounded-pill border text-muted py-2" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection