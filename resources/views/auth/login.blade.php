<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | H-Care Dinas Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #eef2f7; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0;
        }
        
        /* Desain Kartu Utama */
        .login-card { 
            border: none; 
            border-radius: 24px; 
            overflow: hidden; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
            background: #fff; 
            width: 100%; 
            max-width: 1000px; 
            display: flex; 
            flex-direction: row; 
        }

        /* Sisi Kiri: Branding & Informasi */
        .login-left { 
            background: linear-gradient(135deg, #0d6efd 0%, #20c997 100%); 
            padding: 60px; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            position: relative; 
            overflow: hidden; 
            width: 50%; 
        }
        /* Ornamen Lingkaran Transparan */
        .login-left::before { content: ''; position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        .login-left::after { content: ''; position: absolute; bottom: -100px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        
        /* Sisi Kanan: Form Login */
        .login-right { 
            padding: 60px; 
            width: 50%; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        }

        /* Kustomisasi Input Form yang Diperbaiki (Anti Menghilang) */
        .custom-input-group { 
            display: flex; 
            align-items: center; 
            background-color: #f8f9fa; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            transition: all 0.3s; 
            overflow: hidden;
        }
        .custom-input-group:focus-within { 
            background-color: #fff; 
            border-color: #0d6efd; 
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); 
        }
        .custom-input-group .icon-box { 
            padding: 14px 15px; 
            color: #94a3b8; 
            transition: all 0.3s;
        }
        .custom-input-group:focus-within .icon-box { color: #0d6efd; }
        
        .custom-input-group input { 
            border: none; 
            background: transparent; 
            padding: 14px 15px 14px 0; 
            width: 100%; 
            outline: none; 
            color: #334155; 
            font-weight: 500; 
        }
        .custom-input-group .toggle-btn { 
            background: transparent; 
            border: none; 
            padding: 14px 15px; 
            color: #94a3b8; 
            cursor: pointer; 
            outline: none; 
        }
        .custom-input-group .toggle-btn:hover { color: #0d6efd; }
        
        /* Tombol Login Canggih */
        .btn-login { background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); border: none; padding: 14px; font-weight: 600; font-size: 16px; border-radius: 12px; transition: all 0.3s; }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3); }

        /* Responsif untuk Layar HP */
        @media (max-width: 768px) { 
            .login-left { display: none; } 
            .login-right { width: 100%; padding: 40px 30px; } 
            .login-card { border-radius: 16px; max-width: 450px; margin: 20px; } 
        }

        /* Animasi Meluncur Ke Atas */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center animate-fade-up">
        <div class="login-card">
            
            <div class="login-left">
                <div style="z-index: 1;">
                    <div class="bg-white rounded p-3 d-inline-flex mb-4 shadow-sm">
                        <i class="bi bi-hospital text-primary fs-2 lh-1"></i>
                    </div>
                    <h2 class="fw-bold mb-2">H-Care System</h2>
                    <h4 class="fw-light mb-4 text-white-50">Sistem Cuti & E-Pensiun</h4>
                    <p style="line-height: 1.8; font-size: 15px; opacity: 0.9;">
                        Pusat kendali administrasi kepegawaian modern terintegrasi untuk seluruh unit Puskesmas di lingkungan Dinas Kesehatan Kabupaten Batang.
                    </p>
                    
                    <div class="mt-5 pt-4 border-top border-light border-opacity-25">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-check fs-2 text-white opacity-75"></i>
                            <div>
                                <h6 class="m-0 fw-bold">Akses Aman & Terenkripsi</h6>
                                <small class="text-white-50">Sistem mematuhi standar keamanan data</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="login-right">
                
                <div class="text-center mb-4 d-md-none">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 d-inline-flex mb-2">
                        <i class="bi bi-hospital fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark m-0">H-Care Batang</h5>
                </div>

                <div class="mb-5">
                    <h3 class="fw-bold text-dark mb-2">Selamat Datang 👋</h3>
                    <p class="text-muted" style="font-size: 15px;">Silakan masuk menggunakan kredensial akun Anda.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger rounded-3 mb-4 d-flex align-items-center shadow-sm">
                        <i class="bi bi-exclamation-octagon-fill me-3 fs-5"></i>
                        <div style="font-size: 14px; font-weight: 500;">{{ $errors->first() }}</div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Username</label>
                        <div class="custom-input-group shadow-sm">
                            <div class="icon-box"><i class="bi bi-person-fill"></i></div>
                            <input type="text" name="username" placeholder="Contoh: pkmbandar" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Password</label>
                        <div class="custom-input-group shadow-sm">
                            <div class="icon-box"><i class="bi bi-lock-fill"></i></div>
                            <input type="password" name="password" id="passwordInput" placeholder="Masukkan kata sandi" required>
                            <button type="button" class="toggle-btn" onclick="togglePassword()" title="Lihat Password">
                                <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login text-white mt-3 shadow-sm">
                        Masuk ke Sistem <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center mt-5 pt-4">
                    <p class="text-muted m-0" style="font-size: 12px; font-weight: 500;">&copy; {{ date('Y') }} Dinas Kesehatan Kabupaten Batang.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            var passInput = document.getElementById("passwordInput");
            var icon = document.getElementById("toggleIcon");
            
            if (passInput.type === "password") {
                passInput.type = "text";
                icon.classList.remove("bi-eye-slash-fill");
                icon.classList.add("bi-eye-fill");
            } else {
                passInput.type = "password";
                icon.classList.remove("bi-eye-fill");
                icon.classList.add("bi-eye-slash-fill");
            }
        }
    </script>

</body>
</html>