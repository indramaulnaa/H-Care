<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Puskesmas') - H-Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        .sidebar {
            min-height: 100vh; width: 280px; background: #ffffff;
            position: fixed; top: 0; left: 0;
            border-right: 1px solid #f1f5f9; z-index: 1000;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }
        .main-content { margin-left: 280px; padding: 30px; }
        .nav-link {
            color: #64748b; padding: 12px 20px; margin-bottom: 5px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center;
            transition: all 0.3s ease; font-size: 0.95rem;
        }
        .nav-link i { margin-right: 12px; font-size: 1.2rem; transition: transform 0.3s ease; }
        .nav-link:hover { background-color: #f1f5f9; color: #0d6efd; transform: translateX(5px); }
        .nav-link:hover i { transform: scale(1.1); }
        .nav-link.active { background-color: #eff6ff; color: #0d6efd; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1); }
        
        .logo-container { padding: 25px 20px 20px; border-bottom: 1px dashed #f1f5f9; margin-bottom: 25px; }
        .logo-img { width: 45px; height: auto; object-fit: contain; }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column">
        <div class="logo-container d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo_pemkab.png') }}" alt="Logo Kabupaten Batang" class="logo-img">
            <div>
                <h5 class="m-0 fw-bold text-dark" style="letter-spacing: -0.5px;">H-Care</h5>
                <small class="text-primary fw-medium" style="font-size: 11px;">{{ Auth::user()->nama_unit }}</small>
            </div>
        </div>
        
        <div class="px-3 flex-grow-1">
            <ul class="nav nav-pills flex-column mb-auto">
                
                <li class="nav-item">
                    <a href="{{ route('dashboard.puskesmas') }}" class="nav-link {{ Request::is('dashboard/puskesmas') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item mt-4 mb-2 text-muted px-3" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;">PELAYANAN</li>
                
                <li class="nav-item">
                    <a href="{{ route('puskesmas.cuti') }}" class="nav-link {{ Request::is('puskesmas/cuti*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-check-fill"></i> Pengajuan Cuti
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('puskesmas.pensiun') }}" class="nav-link {{ Request::is('puskesmas/pensiun*') ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i> E-Pensiun (1 Thn)
                    </a>
                </li>
                
                <li class="nav-item mt-4 mb-2 text-muted px-3" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;">KEPEGAWAIAN</li>
                
                <li class="nav-item">
                    <a href="{{ route('puskesmas.pegawai') }}" class="nav-link {{ Request::is('puskesmas/pegawai*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Data Pegawai
                    </a>
                </li>
            </ul>
        </div>

        <div class="mt-auto p-4 border-top" style="border-color: #f1f5f9 !important; background-color: #f8fafc;">
            <div class="mb-3">
                <strong class="d-block text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</strong>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light border text-danger w-100 fw-bold shadow-sm" style="border-radius: 10px; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='white'">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar Sistem
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