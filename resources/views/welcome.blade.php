<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'H-Care Batang') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-section {
            /* Background gambar Rumah Sakit/Gedung Pemerintahan */
            background: linear-gradient(rgba(25, 135, 84, 0.9), rgba(20, 108, 67, 0.8)), url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh; /* Full layar */
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3.5rem;
            color: #198754; /* Warna Hijau Dinkes */
            margin-bottom: 1rem;
        }
        .btn-cta {
            background-color: #fff;
            color: #198754;
            font-weight: 700;
            padding: 12px 35px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-cta:hover {
            background-color: #f0f0f0;
            color: #146c43;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                🏥 H-Care Batang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                @if(Auth::user()->role == 'admin_dinkes')
                                    <a href="{{ route('dashboard.dinkes') }}" class="btn btn-outline-light rounded-pill px-4">Ke Dashboard</a>
                                @else
                                    <a href="{{ route('dashboard.puskesmas') }}" class="btn btn-outline-light rounded-pill px-4">Ke Dashboard</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-light text-success fw-bold rounded-pill px-4 shadow-sm">Login Pegawai</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">✨ Sistem Kepegawaian Terintegrasi</span>
                    <h1 class="display-3 fw-bold mb-4">Pelayanan Administrasi <br> Tanpa Batas Jarak</h1>
                    <p class="lead mb-5 opacity-75">
                        Solusi digital Dinas Kesehatan Kabupaten Batang untuk pengelolaan Cuti Pegawai dan Monitoring Pensiun (E-Pensiun) yang cepat, transparan, dan efisien.
                    </p>
                    
                    @auth
                         @if(Auth::user()->role == 'admin_dinkes')
                            <a href="{{ route('dashboard.dinkes') }}" class="btn btn-cta">Buka Dashboard Dinkes</a>
                         @else
                            <a href="{{ route('dashboard.puskesmas') }}" class="btn btn-cta">Buka Dashboard Puskesmas</a>
                         @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-cta">Masuk Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <section class="py-5 bg-light">
        <div class="container mt-4 mb-5">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h6 class="text-success fw-bold text-uppercase ls-2">Fitur Unggulan</h6>
                    <h2 class="fw-bold">Mengapa H-Care?</h2>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4 text-center bg-white">
                        <div class="card-body">
                            <div class="feature-icon">🚀</div>
                            <h4 class="card-title fw-bold mb-3">E-Cuti Online</h4>
                            <p class="card-text text-muted">
                                Pegawai Puskesmas tidak perlu lagi mengantar berkas fisik ke Dinas. Cukup upload, pantau status, dan download SK Cuti dari mana saja.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4 text-center bg-white">
                        <div class="card-body">
                            <div class="feature-icon">📅</div>
                            <h4 class="card-title fw-bold mb-3">Notifikasi Pensiun</h4>
                            <p class="card-text text-muted">
                                Sistem secara otomatis menghitung usia pensiun dan memberikan peringatan dini kepada Admin Dinkes & Puskesmas.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4 text-center bg-white">
                        <div class="card-body">
                            <div class="feature-icon">📂</div>
                            <h4 class="card-title fw-bold mb-3">Arsip Digital</h4>
                            <p class="card-text text-muted">
                                Semua dokumen persyaratan pensiun dan permohonan cuti tersimpan aman dalam format digital (PDF) dan mudah dicari kembali.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Dinas Kesehatan Kabupaten Batang. <br> <small class="text-white-50">Dikembangkan oleh Mahasiswa Magang</small></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>