<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dinkes') - H-Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            width: 260px;
            background: #fff;
            position: fixed;
            top: 0; left: 0;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        .nav-link {
            color: #6c757d;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }
        .nav-link:hover { background-color: #f8f9fa; color: #198754; }
        .nav-link.active {
            background-color: #e8f5e9;
            color: #198754;
            font-weight: 600;
        }
        .logo-area {
            padding: 25px 20px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-3">
        <div class="logo-area d-flex align-items-center gap-2">
            <div class="bg-success text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="bi bi-hospital fs-5"></i>
            </div>
            <div>
                <h5 class="m-0 fw-bold text-success">H-Care</h5>
                <small class="text-muted" style="font-size: 12px;">Admin Dinkes</small>
            </div>
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard.dinkes') }}" class="nav-link {{ Request::is('dashboard/dinkes') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item mt-3 mb-1 text-muted small fw-bold px-3">PELAYANAN</li>
            
            <li class="nav-item">
                <a href="{{ route('dinkes.cuti') }}" class="nav-link {{ Request::is('dinkes/cuti*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Verifikasi Cuti
                    @if(\App\Models\PengajuanCuti::where('status', 'menunggu')->count() > 0)
                        <span class="badge bg-danger ms-auto rounded-pill">{{ \App\Models\PengajuanCuti::where('status', 'menunggu')->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dinkes.pensiun') }}" class="nav-link {{ Request::is('dinkes/pensiun*') ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split"></i> E-Pensiun (Tahun Ini)
                </a>
            </li>

            <li class="nav-item mt-3 mb-1 text-muted small fw-bold px-3">MASTER DATA</li>

            <li class="nav-item">
                <a href="{{ route('dinkes.pegawai') }}" class="nav-link {{ Request::is('dinkes/pegawai*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Data Pegawai (Semua)
                </a>
            </li>
        </ul>

        <div class="mt-auto pt-4 border-top">
            <div class="d-flex align-items-center px-2 mb-3">
                <div class="flex-grow-1">
                    <strong class="d-block text-dark">{{ Auth::user()->name }}</strong>
                    <small class="text-muted">Dinas Kesehatan</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>