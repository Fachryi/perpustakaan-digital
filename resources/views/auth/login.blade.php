<!DOCTYPE html><!--
    * CoreUI - Free Bootstrap Admin Template
    * @version v5.0.0
    * @link https://coreui.io/product/free-bootstrap-admin-template/
    * Copyright (c) 2024 creativeLabs Łukasz Holeczek
    * Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
    --><!--
    NOTE: Here's some reserved variable, DO NOT name global data one of these
    - isDetail
    - data
    -->
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Backoffice Digilib ITH">
    <title>Login Digilib SMP Negeri 1 Parangloe
    </title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="384x384" href="assets/favicon/frater.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/frater.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/frater.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/frater.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
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
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .login-wrapper {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .login-bg {
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

    .login-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(10, 20, 40, 0.65) 0%, rgba(5, 30, 60, 0.55) 100%);
        z-index: 1;
    }

    .login-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 400px;
        padding: 12px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        padding: 24px 30px 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,255,0.08) inset;
        color: #fff;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 14px;
    }

    .login-logo .lib-icon {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        background: transparent;
        filter: drop-shadow(0 4px 14px rgba(0, 0, 0, 0.5));
    }

    .login-logo .lib-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .login-logo h1 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 2px;
        letter-spacing: -0.3px;
    }

    .login-logo .school-name {
        font-size: 0.73rem;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
        line-height: 1.45;
    }

    .login-card .form-label {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.78rem;
        font-weight: 600;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }

    .login-card .input-group-text {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-right: none;
        color: rgba(255, 255, 255, 0.8);
        border-radius: 10px 0 0 10px;
    }

    .login-card .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-left: none;
        color: #fff;
        border-radius: 0 10px 10px 0;
        font-size: 0.88rem;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }

    .login-card .form-control::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .login-card .form-control:focus {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(59, 130, 246, 0.7);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        color: #fff;
        outline: none;
    }

    .login-card .input-group:focus-within .input-group-text {
        border-color: rgba(59, 130, 246, 0.7);
        background: rgba(59, 130, 246, 0.15);
    }

    .btn-login {
        width: 100%;
        padding: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 10px;
        background: linear-gradient(135deg, #3b82f6, #0ea5e9);
        border: none;
        color: #fff;
        letter-spacing: 0.3px;
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.45);
        transition: all 0.3s ease;
        margin-top: 4px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(59, 130, 246, 0.6);
        background: linear-gradient(135deg, #2563eb, #0284c7);
        color: #fff;
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .error-alert {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fca5a5;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .login-footer {
        text-align: center;
        margin-top: 14px;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.4);
        white-space: nowrap;
    }
</style>

<body>
    @include('sweetalert::alert')

    {{-- Background --}}
    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    {{-- Login Container --}}
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-card">

                {{-- Logo / Header --}}
                <div class="login-logo">
                    <div class="lib-icon">
                        <img src="/images/logo-sekolah.png" alt="Logo SMP Negeri 1 Parangloe">
                    </div>
                    <h1>Perpustakaan Digital</h1>
                    <p class="school-name">SMP Negeri 1 Parangloe &bull; Kabupaten Gowa<br><span style="color:rgba(255,255,255,0.5)">Silahkan masuk untuk melanjutkan</span></p>
                </div>

                {{-- Error Session --}}
                @if(session('error'))
                    <div class="error-alert">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="error-alert">
                        ⚠️ {{ $errors->first() }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="/login">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NIS / NIP</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg class="icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                </svg>
                            </span>
                            <input class="form-control" name="username" type="text" placeholder="Masukkan NIS atau NIP" value="{{ old('username') }}" autocomplete="username">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg class="icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            <input class="form-control" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password">
                        </div>
                    </div>
                    <button class="btn btn-login" type="submit">
                        Masuk &rarr;
                    </button>
                </form>

                <div class="text-center mt-3" style="font-size: 0.83rem; color: rgba(255,255,255,0.75);">
                    Belum punya akun? <a href="{{ route('register') }}" style="color: #60a5fa; font-weight: 600; text-decoration: none;">Mendaftar</a>
                </div>

                <div class="login-footer">
                    &copy; {{ date('Y') }} Perpustakaan Digital &mdash; SMP Negeri 1 Parangloe
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center" id="bs-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <p class="mb-0"></p>
                </div>
                <button class="btn-close me-2 m-auto" type="button" data-coreui-dismiss="toast"
                    aria-label="Tutup"></button>
            </div>
        </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="vendors/jquery/js/jquery.min.js"></script>
    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="vendors/simplebar/js/simplebar.min.js"></script>
    <script src="vendors/datatables.net/js/dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vendors/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <!-- Component init-->
    <script src="js/datatables.js"></script>
    <script src="js/tooltips.js"></script>
    <script src="js/toast.js"></script>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });
    </script>
    <script></script>

</body>

</html>
