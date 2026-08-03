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

<body>
    @include('sweetalert::alert')
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card p-4">
                        <form class="card-body" method="POST" action="/login">
                            @csrf
                            <h1>Login</h1>
                            <p class="text-body-secondary">Silahkan login untuk memulai aplikasi Digilib SMP Negeri 1 Parangloe.</p>
                            <div class="input-group mb-3"><span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                    </svg></span>
                                <input class="form-control" name="username" type="text" placeholder="NIS/NIP">
                            </div>
                            <div class="input-group mb-4"><span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                    </svg></span>
                                <input class="form-control" type="password" name="password" placeholder="Password">
                            </div>
                            <button class="btn btn-info px-4 float-end" type="submit">Login</button>

                        </form>
                    </div>
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
