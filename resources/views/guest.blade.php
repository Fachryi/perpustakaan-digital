<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/assets/smp.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/styles/main.741d8512.css" />
    <title>Pengguna - Digilib SMPN 1 Parangloe </title>
</head>

<body class="min-vh-100 d-flex flex-column">
    @if (Request::is('/'))
        @include('partials.navbar-hero')
    @else
        @include('partials.navbar')
    @endif


    @yield('content')
    <footer class="bg-dark text-bg-dark pt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <h6 class="mb-3">Link Lainnya</h6>
                    <div class="d-flex flex-column gap-2">
                        <a class="link-light link-opacity-75 fw-light" href="https://ith.ac.id/" target="_blank"
                            rel="noopener noreferrer">SMP Negeri 1 Parangloe</a><a
                            class="link-light link-opacity-75 fw-light" href="/"
                            target="_blank" rel="noopener noreferrer">Perpustakaan Digital SMP Negeri 1 Parangloe</a><a
                            class="link-light link-opacity-75 fw-light" href="https://www.lipsum.com" target="_blank"
                            rel="noopener noreferrer">Link Lainnya</a><a class="link-light link-opacity-75 fw-light"
                            href="https://www.lipsum.com" target="_blank" rel="noopener noreferrer">Link Lainnya
                            Kedua</a><a class="link-light link-opacity-75 fw-light" href="https://www.lipsum.com"
                            target="_blank" rel="noopener noreferrer">Link Lainnya Terakhir</a>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h6 class="mb-3">Kontak</h6>
                    <div class="d-flex flex-column gap-2 text-light text-opacity-75 fw-light">
                        <p class="mb-0">
                            Phone:
                            <a class="link-light link-opacity-75" href="tel:0411092842" target="_blank"
                                rel="noopener noreferrer">0411 092842</a>
                        </p>
                        <p class="mb-0">
                            Whatsapp:
                            <a class="link-light link-opacity-75" href="https://wa.me/6282139292912" target="_blank"
                                rel="noopener noreferrer">+(62) 821 39292912</a>
                        </p>
                        <p class="mb-0">
                            Email:
                            <a class="link-light link-opacity-75" href="mailto:email@smpparangloe.ac.id" target="_blank"
                                rel="noopener noreferrer">email@smpparangloe.ac.id</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <h6 class="mb-3">Kontak</h6>
                    <div class="d-flex flex-column gap-2 text-light text-opacity-75 fw-light">
                        <p class="mb-0">
                            Lanna, Parangloe, Gowa Regency, South Sulawesi 92173
                        </p>
                        <iframe class="rounded-2"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d136007.22916966322!2d119.66164656809445!3d-5.252915733572207!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee9ad1804676f%3A0xc76ec7d033c877d4!2sSMP%20Negeri%201%20Parangloe!5e0!3m2!1sen!2sid!4v1775103518530!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <hr class="my-4" />
            <div
                class="pb-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-center gap-2 text-sm-start text-center">
                <p class="mb-0 fw-light">
                    &copy; 2026, SMPN 1 Parangloe
                </p>
                <div class="d-flex gap-1 flex-sm-nowrap flex-wrap justify-content-sm-end justify-content-center">
                    <a class="btn btn-icon link-light" href="https://facebook.com" target="_blank"
                        rel="noopener noreferrer"><i class="bi-facebook"></i></a><a class="btn btn-icon link-light"
                        href="https://instagram.com" target="_blank" rel="noopener noreferrer"><i
                            class="bi-instagram"></i></a><a class="btn btn-icon link-light" href="https://linkedin.com"
                        target="_blank" rel="noopener noreferrer"><i class="bi-linkedin"></i></a><a
                        class="btn btn-icon link-light" href="https://youtube.com" target="_blank"
                        rel="noopener noreferrer"><i class="bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="/scripts/main.9c8d564c.js"></script>
</body>

</html>
