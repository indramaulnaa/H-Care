<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H-Care | Dinas Kesehatan Kabupaten Batang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        
        /* NAVBAR CUSTOM & LOGO */
        .navbar-custom { background-color: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .nav-link { font-weight: 500; color: #475569 !important; transition: color 0.3s; margin: 0 10px; }
        .nav-link:hover { color: #0d6efd !important; }
        .btn-login-nav { background: transparent; border: 2px solid #0d6efd; color: #0d6efd !important; border-radius: 50px; padding: 6px 24px; font-weight: 600; transition: all 0.3s; }
        .btn-login-nav:hover { background: #0d6efd; color: white !important; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2); }

        /* HERO SECTION (BACKGROUND BARU ELEGAN) */
        .hero-section { 
            position: relative; 
            padding: 170px 0 180px 0; 
            background-color: #ffffff;
            text-align: center;
            overflow: hidden;
        }
        
        /* Pola Garis Halus (Grid Pattern) */
        .hero-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                linear-gradient(rgba(13, 110, 253, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(13, 110, 253, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 1;
        }

        /* Cahaya Melayang (Mesh Gradient Soft) */
        .hero-glow-1 { position: absolute; top: -10%; left: -5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(13,110,253,0.08) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; z-index: 0; }
        .hero-glow-2 { position: absolute; bottom: -10%; right: -5%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(32,201,151,0.08) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; z-index: 0; }

        .hero-content { position: relative; z-index: 2; }
        .hero-badge { background: #e0f0ff; color: #0d6efd; padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.1); }
        .hero-title { font-size: 4rem; font-weight: 800; color: #1e293b; line-height: 1.2; margin-bottom: 20px; letter-spacing: -1px; }
        .hero-title span { color: #0d6efd; }
        .hero-subtitle { font-size: 1.15rem; color: #64748b; max-width: 700px; margin: 0 auto 40px; line-height: 1.6; }
        
        .hero-btn { padding: 14px 35px; font-size: 1.1rem; font-weight: 600; border-radius: 12px; transition: all 0.3s; display: inline-flex; align-items: center; gap: 10px; }
        .btn-hero-primary { background: #0d6efd; color: white; border: none; box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3); }
        .btn-hero-primary:hover { background: #0b5ed7; color: white; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(13, 110, 253, 0.4); }
        .btn-hero-outline { background: white; border: 1px solid #e2e8f0; color: #475569; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .btn-hero-outline:hover { border-color: #cbd5e1; color: #1e293b; transform: translateY(-3px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); }

        /* STATISTIK OVERLAP */
        .stats-wrapper { margin-top: -80px; position: relative; z-index: 10; }
        .stat-card { background: white; border-radius: 20px; padding: 35px 20px; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; transition: transform 0.3s; height: 100%; }
        .stat-card:hover { transform: translateY(-10px); }
        .stat-number { font-size: 3rem; font-weight: 800; color: #0d6efd; margin-bottom: 5px; line-height: 1; }
        .stat-label { font-size: 1rem; font-weight: 600; color: #64748b; margin: 0; }

        /* ================= TIMELINE SECTION ================= */
        .timeline-section { padding: 100px 0 80px; background: #f8f9fa; }
        .section-heading { text-align: center; margin-bottom: 40px; }
        .section-heading h2 { font-weight: 800; color: #1e293b; margin-bottom: 15px; }
        .section-heading p { color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 0 auto; }

        .timeline-tabs { display: flex; justify-content: center; gap: 15px; margin-bottom: 50px; flex-wrap: wrap; }
        .timeline-tab-btn { background: white; border: 2px solid #e2e8f0; color: #475569; padding: 12px 30px; border-radius: 50px; font-weight: 600; font-size: 1rem; transition: all 0.3s; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .timeline-tab-btn:hover { border-color: #cbd5e1; color: #1e293b; transform: translateY(-2px); }
        .timeline-tab-btn.active { background: #0d6efd; border-color: #0d6efd; color: white; box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3); }

        .timeline-content { display: none; animation: fadeIn 0.5s ease forwards; }
        .timeline-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .timeline { position: relative; max-width: 1000px; margin: 0 auto; }
        .timeline::after { content: ''; position: absolute; width: 4px; background-color: #e2e8f0; top: 0; bottom: 0; left: 50%; margin-left: -2px; border-radius: 4px; }
        .timeline-item { padding: 10px 50px; position: relative; width: 50%; }
        .timeline-item.left { left: 0; }
        .timeline-item.right { left: 50%; }
        
        .timeline-item::after { content: ''; position: absolute; width: 24px; height: 24px; right: -12px; background-color: #0d6efd; border: 4px solid #fff; top: 30px; border-radius: 50%; z-index: 1; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2); transition: all 0.3s; }
        .timeline-item.right::after { left: -12px; }
        .timeline-item:hover::after { transform: scale(1.2); }
        
        .timeline-card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #edf2f7; transition: transform 0.3s; position: relative; }
        .timeline-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(13, 110, 253, 0.1); border-color: #bfdbfe; }
        .timeline-title { font-weight: 800; color: #1e293b; font-size: 1.25rem; margin-bottom: 15px; }
        .timeline-list { padding-left: 20px; color: #475569; font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px; }
        .timeline-list li { margin-bottom: 10px; }
        
        .timeline-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 15px; }
        .badge-status { padding: 6px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; }
        .badge-blue { background: #eff6ff; color: #3b82f6; }
        .badge-warning { background: #fef3c7; color: #f59e0b; }
        .badge-info { background: #e0e7ff; color: #4f46e5; }
        .badge-success { background: #dcfce7; color: #10b981; }
        .role-tag { font-size: 0.8rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; }

        @media screen and (max-width: 768px) {
            .timeline::after { left: 31px; }
            .timeline-item { width: 100%; padding-left: 70px; padding-right: 15px; }
            .timeline-item.left::after, .timeline-item.right::after { left: 19px; }
            .timeline-item.right { left: 0%; }
        }

        /* JENIS CUTI SECTION */
        .jenis-cuti-section { padding: 80px 0 100px; background: white; }
        .cuti-card { background: #f8fafc; border-radius: 16px; padding: 25px; border: 1px solid #e2e8f0; transition: all 0.3s; display: flex; align-items: flex-start; gap: 20px; height: 100%; }
        .cuti-card:hover { background: white; box-shadow: 0 15px 30px rgba(0,0,0,0.06); border-color: #cbd5e1; transform: translateY(-5px); }
        .cuti-icon { width: 60px; height: 60px; border-radius: 14px; background: #e0f0ff; color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; }
        .cuti-title { font-weight: 700; color: #1e293b; font-size: 1.1rem; margin-bottom: 8px; }
        .cuti-desc { color: #64748b; font-size: 0.9rem; line-height: 1.6; margin: 0; }

        /* FOOTER */
        .footer { background: #0f172a; color: white; padding: 60px 0 30px; }
        .footer-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 20px; color: #fff; }
        .footer p { color: #94a3b8; font-size: 0.95rem; line-height: 1.7; }
        .footer-bottom { border-top: 1px solid #1e293b; padding-top: 30px; margin-top: 40px; text-align: center; color: #64748b; font-size: 0.9rem; }

        /* ANIMASI MASUK */
        .fade-in-up { animation: fadeInUp 0.8s ease forwards; opacity: 0; transform: translateY(30px); }
        .delay-1 { animation-delay: 0.2s; } .delay-2 { animation-delay: 0.4s; } .delay-3 { animation-delay: 0.6s; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) { .hero-title { font-size: 2.8rem; } }
    </style>
</head>
<body>

    @php
        $totalPegawai = \App\Models\Pegawai::count() ?? 0;
        $cutiProses = \App\Models\PengajuanCuti::whereIn('status', ['menunggu', 'diproses'])->count() ?? 0;
        $cutiBerjalan = \App\Models\PengajuanCuti::where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', \Carbon\Carbon::today())
            ->whereDate('tanggal_selesai', '>=', \Carbon\Carbon::today())
            ->count() ?? 0;
    @endphp

    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none" href="#">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/cd/Lambang_Kabupaten_Batang.png" alt="Logo Batang" style="width: 42px; height: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                <div class="d-flex flex-column justify-content-center">
                    <span style="font-weight: 800; font-size: 1.4rem; line-height: 1.1; color: #0d6efd;">H-Care</span>
                    <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; margin-top: 2px;">Dinas Kesehatan Kab. Batang</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link fw-bold text-primary" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline-alur">Alur Pengajuan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jenis-cuti">Jenis Cuti</a></li>
                </ul>
                <div class="d-flex mt-3 mt-lg-0">
                    @auth
                        <a href="{{ Auth::user()->role == 'admin_dinkes' ? route('dashboard.dinkes') : route('dashboard.puskesmas') }}" class="btn btn-login-nav">
                            Dashboard <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login-nav">
                            <i class="bi bi-person-fill me-1"></i> Login / Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        
        <div class="container hero-content">
            <div class="fade-in-up">
                <div class="hero-badge">
                    <i class="bi bi-stars me-2"></i> Sistem Manajemen Terpadu
                </div>
                <h1 class="hero-title">H-CARE <span>BATANG</span></h1>
                <p class="hero-subtitle">Sistem Pelayanan Cuti dan Pensiun Elektronik Dinas Kesehatan Kabupaten Batang untuk pengelolaan yang transparan, cepat, dan efisien.</p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('login') }}" class="btn hero-btn btn-hero-primary">
                        <i class="bi bi-rocket-takeoff-fill"></i> Masuk Sistem
                    </a>
                    <a href="#jenis-cuti" class="btn hero-btn btn-hero-outline">
                        <i class="bi bi-journal-text"></i> Pelajari Fitur Lengkap
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container stats-wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 fade-in-up delay-1">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($totalPegawai, 0, ',', '.') }}</div>
                    <p class="stat-label">Pegawai Terdaftar</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in-up delay-2">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($cutiProses, 0, ',', '.') }}</div>
                    <p class="stat-label">Cuti Diproses Dinkes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-in-up delay-3">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($cutiBerjalan, 0, ',', '.') }}</div>
                    <p class="stat-label">Cuti Menjalankan Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <section id="timeline-alur" class="timeline-section">
        <div class="container">
            <div class="section-heading fade-in-up">
                <h2>Pilih Layanan untuk Melihat Timeline:</h2>
                <p>Panduan langkah demi langkah administrasi dari Puskesmas hingga Dinas Kesehatan.</p>
            </div>

            <div class="timeline-tabs fade-in-up delay-1">
                <button class="timeline-tab-btn active" id="btn-cuti" onclick="switchTimeline('cuti')">
                    <i class="bi bi-calendar2-check me-2"></i> Alur E-Cuti
                </button>
                <button class="timeline-tab-btn" id="btn-pensiun" onclick="switchTimeline('pensiun')">
                    <i class="bi bi-hourglass-split me-2"></i> Alur E-Pensiun
                </button>
            </div>

            <div id="content-cuti" class="timeline-content active">
                <div class="timeline">
                    <div class="timeline-item right">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Pengajuan Dikirim</h4>
                            <ul class="timeline-list">
                                <li>Memilih profil pegawai dari Master Data terpusat.</li>
                                <li>Mengisi rentang tanggal cuti dan alasan pengajuan.</li>
                                <li>Surat pengantar fisik / surat dokter diunggah ke sistem.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="role-tag">ADMIN PUSKESMAS</span>
                                <span class="badge-status badge-blue">Langkah 1</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item left">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Verifikasi Dinas Kesehatan</h4>
                            <ul class="timeline-list">
                                <li>Dinkes menarik berkas (Status: <i>Diproses</i>) mencegah pembatalan sepihak.</li>
                                <li>Dinkes mengecek keabsahan dokumen PDF yang dilampirkan.</li>
                                <li>Keputusan dibuat: Setuju atau Tolak.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="badge-status badge-warning">Langkah 2</span>
                                <span class="role-tag">ADMIN DINKES</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item right">
                        <div class="timeline-card">
                            <h4 class="timeline-title">SK Cuti Terbit</h4>
                            <ul class="timeline-list">
                                <li>Sistem mencatat riwayat cuti secara permanen.</li>
                                <li>Dinkes mengunggah file SK Elektronik resmi.</li>
                                <li>Puskesmas dapat mengunduh arsip SK langsung di sistem.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="role-tag">SISTEM H-CARE</span>
                                <span class="badge-status badge-success">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-pensiun" class="timeline-content">
                <div class="timeline">
                    <div class="timeline-item left">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Permintaan Akses</h4>
                            <ul class="timeline-list">
                                <li>Sistem memperingatkan pegawai yang usianya mendekati BUP (Batas Usia Pensiun).</li>
                                <li>Puskesmas mengeklik "Minta Akses" untuk membuka portal unggah dokumen kepada Dinkes.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="badge-status badge-blue">Pra-Tahap 1</span>
                                <span class="role-tag">ADMIN PUSKESMAS</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item right">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Verifikasi Tahap 1 (Syarat Dasar)</h4>
                            <ul class="timeline-list">
                                <li>Dinkes membukakan gembok akses pengunggahan.</li>
                                <li>Puskesmas wajib mengunggah <strong>SK CPNS</strong> dan <strong>SK Pangkat</strong>.</li>
                                <li>Dinkes memverifikasi. Jika lolos, lanjut ke Tahap 2.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="role-tag">PUSKESMAS & DINKES</span>
                                <span class="badge-status badge-warning">Langkah 1</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item left">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Verifikasi Tahap 2 (Syarat Akhir)</h4>
                            <ul class="timeline-list">
                                <li>Setelah Tahap 1 lulus, form unggah baru akan terbuka untuk Puskesmas.</li>
                                <li>Puskesmas wajib mengunggah dokumen <strong>Karpeg</strong> (Kartu Pegawai).</li>
                                <li>Dinkes memverifikasi kelengkapan terakhir.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="badge-status badge-info">Langkah 2</span>
                                <span class="role-tag">PUSKESMAS & DINKES</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item right">
                        <div class="timeline-card">
                            <h4 class="timeline-title">Arsip Elektronik (Selesai)</h4>
                            <ul class="timeline-list">
                                <li>Pensiun disetujui sepenuhnya oleh Dinas Kesehatan.</li>
                                <li>Semua dokumen tersimpan rapi sebagai arsip digital permanen di server H-Care.</li>
                            </ul>
                            <div class="timeline-footer">
                                <span class="role-tag">SISTEM H-CARE</span>
                                <span class="badge-status badge-success">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="jenis-cuti" class="jenis-cuti-section">
        <div class="container">
            <div class="section-heading fade-in-up">
                <h2>Berbagai Jenis Cuti yang Didukung</h2>
                <p>Sistem H-Care mendukung seluruh kategori cuti kepegawaian sesuai dengan peraturan Badan Kepegawaian Negara.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 fade-in-up delay-1">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-calendar-check"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti Tahunan</h4>
                            <p class="cuti-desc">Diberikan bagi PNS yang telah bekerja sekurang-kurangnya 1 (satu) tahun secara terus menerus.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 fade-in-up delay-1">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-heart-pulse"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti Sakit</h4>
                            <p class="cuti-desc">Bagi pegawai yang sakit lebih dari 1 hari wajib melampirkan surat keterangan dokter/faskes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 fade-in-up delay-2">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-person-hearts"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti Melahirkan</h4>
                            <p class="cuti-desc">Diberikan untuk persalinan anak pertama, kedua, dan ketiga dengan durasi yang telah ditetapkan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 fade-in-up delay-2">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-exclamation-circle"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti Alasan Penting</h4>
                            <p class="cuti-desc">Diberikan saat keluarga inti sakit keras/meninggal dunia, atau melangsungkan pernikahan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 fade-in-up delay-3">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-briefcase"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti Besar</h4>
                            <p class="cuti-desc">Diberikan bagi PNS yang telah bekerja paling singkat 5 (lima) tahun secara terus menerus.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 fade-in-up delay-3">
                    <div class="cuti-card">
                        <div class="cuti-icon"><i class="bi bi-airplane"></i></div>
                        <div>
                            <h4 class="cuti-title">Cuti di Luar Tanggungan</h4>
                            <p class="cuti-desc">Cuti di luar tanggungan negara dapat diberikan untuk alasan pribadi yang sangat mendesak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/cd/Lambang_Kabupaten_Batang.png" alt="Logo Batang" style="width: 45px;">
                        <div class="d-flex flex-column">
                            <span style="font-weight: 800; font-size: 1.3rem; line-height: 1.1; color: #fff;">H-Care</span>
                            <span style="font-size: 0.65rem; font-weight: 600; color: #94a3b8; letter-spacing: 0.5px; text-transform: uppercase; margin-top: 2px;">Dinas Kesehatan Kab. Batang</span>
                        </div>
                    </div>
                    <p>Sistem Layanan Administrasi Kepegawaian (Cuti & Pensiun) terintegrasi untuk 21 Unit Puskesmas di Kabupaten Batang.</p>
                </div>
                <div class="col-lg-3 offset-lg-1 mb-4 mb-lg-0">
                    <h5 class="footer-title">Navigasi</h5>
                    <ul class="list-unstyled" style="line-height: 2.2;">
                        <li><a href="#beranda" class="text-white-50 text-decoration-none">Beranda Utama</a></li>
                        <li><a href="#timeline-alur" class="text-white-50 text-decoration-none">Alur Pengajuan</a></li>
                        <li><a href="#jenis-cuti" class="text-white-50 text-decoration-none">Panduan Cuti</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="footer-title">Hubungi Kami</h5>
                    <ul class="list-unstyled text-white-50" style="line-height: 2;">
                        <li><i class="bi bi-geo-alt-fill me-2 text-primary"></i> Jl. Jend. Sudirman No.XXX, Kab. Batang</li>
                        <li><i class="bi bi-envelope-fill me-2 text-primary"></i> dinkes@batangkab.go.id</li>
                        <li><i class="bi bi-telephone-fill me-2 text-primary"></i> (0285) 391034</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Dinas Kesehatan Kabupaten Batang. Dikembangkan untuk efisiensi birokrasi pemerintahan.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efek Navbar saat di-scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = '0 4px 15px rgba(0,0,0,0.05)';
            }
        });

        // Logika Perpindahan Timeline (Cuti / Pensiun)
        function switchTimeline(type) {
            document.querySelectorAll('.timeline-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.timeline-content').forEach(content => content.classList.remove('active'));

            document.getElementById('btn-' + type).classList.add('active');
            document.getElementById('content-' + type).classList.add('active');
        }
    </script>
</body>
</html>