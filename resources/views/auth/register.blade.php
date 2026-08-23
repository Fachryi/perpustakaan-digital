<!DOCTYPE html>
<html lang="id">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registrasi Siswa - Digilib SMP Negeri 1 Parangloe</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/frater.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="styles/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="styles/style.css" rel="stylesheet">
    <script src="js/config.js"></script>
    <script src="js/color-modes.js"></script>
</head>

<style>
    html, body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 30px 15px;
    }

    .register-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/images/library-bg.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 0;
    }

    .register-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(10, 20, 40, 0.75) 0%, rgba(5, 30, 60, 0.65) 100%);
        z-index: 1;
    }

    .register-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 480px;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        padding: 28px 32px 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,255,0.08) inset;
        color: #fff;
    }

    .register-logo {
        text-align: center;
        margin-bottom: 18px;
    }

    .register-logo .lib-icon {
        width: 75px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        background: transparent;
        filter: drop-shadow(0 4px 14px rgba(0, 0, 0, 0.5));
    }

    .register-logo .lib-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .register-logo h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 2px;
        letter-spacing: -0.3px;
    }

    .register-logo .school-name {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.65);
        margin: 0;
        line-height: 1.45;
    }

    .register-card .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.78rem;
        font-weight: 600;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }

    .register-card .input-group-text {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-right: none;
        color: rgba(255, 255, 255, 0.85);
        border-radius: 10px 0 0 10px;
    }

    .register-card .form-control,
    .register-card .form-select {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-left: none;
        color: #fff;
        border-radius: 0 10px 10px 0;
        font-size: 0.88rem;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }

    .register-card .form-select option {
        background: #1e293b;
        color: #fff;
    }

    .register-card .form-control::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .register-card .form-control:focus,
    .register-card .form-select:focus {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(59, 130, 246, 0.7);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        color: #fff;
        outline: none;
    }

    .register-card .input-group:focus-within .input-group-text {
        border-color: rgba(59, 130, 246, 0.7);
        background: rgba(59, 130, 246, 0.2);
    }

    .btn-register {
        width: 100%;
        padding: 10px;
        font-size: 0.92rem;
        font-weight: 600;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: #fff;
        letter-spacing: 0.3px;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
        margin-top: 8px;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(16, 185, 129, 0.55);
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
    }

    .error-alert {
        background: rgba(239, 68, 68, 0.25);
        border: 1px solid rgba(239, 68, 68, 0.45);
        color: #fca5a5;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .login-link {
        text-align: center;
        margin-top: 16px;
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .login-link a {
        color: #60a5fa;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
        color: #93c5fd;
    }
</style>

<body>
    @include('sweetalert::alert')

    <div class="register-bg"></div>
    <div class="register-overlay"></div>

    <div class="register-wrapper">
        <div class="register-container">
            <div class="register-card">

                <div class="register-logo">
                    <div class="lib-icon">
                        <img src="/images/logo-sekolah.png" alt="Logo SMP Negeri 1 Parangloe">
                    </div>
                    <h1>Registrasi Siswa Baru</h1>
                    <p class="school-name">Perpustakaan Digital SMP Negeri 1 Parangloe<br>Lengkapi data Anda untuk mendaftar akun</p>
                </div>

                @if($errors->any())
                    <div class="error-alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                </svg>
                            </span>
                            <input class="form-control" name="nama" type="text" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                        </div>
                    </div>

                    {{-- NIS / NIM --}}
                    <div class="mb-3">
                        <label class="form-label">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm14 3H1v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6z"/>
                                </svg>
                            </span>
                            <input class="form-control" name="nim_nip" type="text" placeholder="Masukkan NIS Anda" value="{{ old('nim_nip') }}" required>
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                                </svg>
                            </span>
                            <select name="kelas_id" class="form-select" required>
                                <option value="" disabled {{ old('kelas_id') ? '' : 'selected' }}>Pilih Kelas...</option>
                                @foreach($daftarKelas as $kelas)
                                    <option value="{{ is_object($kelas) ? $kelas->nama : $kelas }}" {{ old('kelas_id') == (is_object($kelas) ? $kelas->nama : $kelas) ? 'selected' : '' }}>
                                        Kelas {{ is_object($kelas) ? $kelas->nama : $kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            <input class="form-control" type="password" name="password" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <button class="btn btn-register" type="submit">
                        Daftar Akun Siswa &rarr;
                    </button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="/">Masuk di sini</a>
                </div>

            </div>
        </div>
    </div>

    <script src="vendors/jquery/js/jquery.min.js"></script>
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
</body>

</html>
