<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Backoffice Digilib ITH">
    <title>Home

        -
        Digilib SMPN 1 Parangloe
    </title>

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/smp.png">
    <link rel="icon" type="image/png" sizes="384x384" href="/assets/favicon/smp.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/favicon/smp.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/smp.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/smp.png">
    <link rel="manifest" href="/assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="/vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="/styles/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="/styles/style.css" rel="stylesheet">
    <script src="/js/config.js"></script>
    <script src="/js/color-modes.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

</head>

<body>
    @include('sweetalert::alert')
    <div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand">
                <a href="/" style="text-decoration:none">
                    <div class="sidebar-brand-full hstack gap-2 fw-bold">
                        <img height="32" src="/assets/brand/smp.png" alt="ITH Logo">SMPN 1 Parangloe
                    </div>
                    <img class="sidebar-brand-narrow" height="32" src="/assets/brand/smp.png" alt="ITH Logo">
                </a>
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark"
                aria-label="Close"
                onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
        </div>
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-item  "><a class="nav-link {{ Request::is('*dashboard') ? 'active' : '' }}"
                    href="/dashboard">
                    <svg class="nav-icon">
                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-home"></use>
                    </svg>Dashboard</a></li>
                    
           
            @if (auth()->user()->role === 'admin')
             <li class="nav-item">
                <a class="nav-link {{ Request::is('*literatur*') ? 'active' : '' }}"
                    href="/dashboard/buku">
                    <svg class="nav-icon">
                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-book"></use>
                    </svg>Buku</a>
                </li>
                <li class="nav-item"><a class="nav-link {{ Request::is('*peminjaman*') ? 'active' : '' }}"
                        href="/admin/peminjaman">
                        <svg class="nav-icon">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-book"></use>
                        </svg>Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('*denda*') ? 'active' : '' }}"
                        href="/admin/denda">
                        <svg class="nav-icon">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-money"></use>
                        </svg>Denda</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('*laporan*') ? 'active' : '' }}"
                        href="/admin/laporan">
                        <svg class="nav-icon">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                        </svg>Laporan</a></li>
            @endif
            @if (auth()->user()->role === 'admin')
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-storage"></use>
                        </svg>Master Data</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="/master-data/siswa/"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span>Siswa</a></li>
                        <li class="nav-item"><a class="nav-link" href="/master-data/guru/"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span>Staff/Guru</a></li>

                        <li class="nav-item"><a class="nav-link" href="/master-data/jenis/"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span>Jenis</a></li>

                        <li class="nav-item"><a class="nav-link" href="/master-data/kelas/"><span
                                    class="nav-icon"><span class="nav-icon-bullet"></span></span>Kelas</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (auth()->user()->role === 'superadmin')
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-settings"></use>
                        </svg>Admin</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="/admin/users"><span class="nav-icon"><span
                                        class="nav-icon-bullet"></span></span>Pengguna</a></li>
                    </ul>
                </li>
            @endif


        </ul>
        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky p-0 mb-4">
            <div class="container-fluid border-bottom px-4">
                <button class="header-toggler" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style="margin-inline-start: -14px">
                    <svg class="icon icon-lg">
                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
                    </svg>
                </button>
                <ul class="header-nav">
                    <li class="nav-item dropdown">
                        <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                            aria-expanded="false" data-coreui-toggle="dropdown">
                            <svg class="icon icon-lg theme-icon-active">
                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
                            </svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem">
                            <li>
                                <button class="dropdown-item d-flex align-items-center" type="button"
                                    data-coreui-theme-value="light">
                                    <svg class="icon icon-lg me-3">
                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                                    </svg>Light
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item d-flex align-items-center" type="button"
                                    data-coreui-theme-value="dark">
                                    <svg class="icon icon-lg me-3">
                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-moon"></use>
                                    </svg>Dark
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item d-flex align-items-center active" type="button"
                                    data-coreui-theme-value="auto">
                                    <svg class="icon icon-lg me-3">
                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-contrast"></use>
                                    </svg>Auto
                                </button>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item py-1">
                        <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <img class="avatar avatar-md text-bg-primary"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=random">

                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="/logout">
                                <svg class="icon me-2">
                                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                                </svg>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="container-fluid px-4">
                @yield('breadcrumb')
            </div>
        </header>
        <div class="body flex-grow-1">
            @yield('content')
        </div>
        <footer class="footer px-4 text-center justify-content-center">© 2026, SMPN 1 Parangloe</footer>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center" id="bs-toast" role="alert" aria-live="assertive"
            aria-atomic="true">
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
    <script src="/vendors/jquery/js/jquery.min.js"></script>
    <script src="/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="/vendors/simplebar/js/simplebar.min.js"></script>
    <script src="/vendors/datatables.net/js/dataTables.min.js"></script>
    <script src="/vendors/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/vendors/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <!-- Component init-->
    <!-- Scripts -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="/js/datatables.js"></script>
    <script src="/js/tooltips.js"></script>
    <script src="/js/toast.js"></script>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });
    </script>
    <script></script>
    @yield('script')
    @stack('script')
</body>

</html>
