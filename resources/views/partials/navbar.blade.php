        <!-- ** Vars-->
        <nav class="navbar navbar-expand-md justify-content-between fixed-top bg-body-secondary">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center" href="/welcome"><img class="me-2"
                        src="/assets/smp.png" alt="Logo ITH" />Digilib SMPN 1 Parangloe</a><button
                    class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-links"
                    aria-controls="navbar-links" aria-label="Toggle navigation">
                    <span class="bi-list fs-2"></span>
                </button>
                <div class="collapse navbar-collapse gap-3 justify-content-end" id="navbar-links">
                    <div class="navbar-nav order-md-last flex-grow-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('#') ? 'active' : '' }}" href="/faq"
                                aria-current="page">FAQ</a>
                        </li>
                        <li class="nav-item">
                           
                            @auth
                            @if(auth()->user()->role === 'siswa')
                                @php
                                    $notifCount = \App\Models\PeminjamanBuku::where('user_id', auth()->id())
                                        ->where('status', 'dipinjam')
                                        ->where('approval', 'approved')
                                        ->where('tanggal_kembali', '<', now())
                                        ->count();
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link position-relative" href="/welcome#notifikasi">
                                        <i class="bi-bell fs-5"></i>
                                        @if($notifCount > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $notifCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Hi, {{ auth()->user()->nama }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(auth()->user()->role === 'siswa')
                                    <li>
                                        <a class="dropdown-item" href="/siswa/dashboard">
                                            <i class="bi-speedometer2 me-2"></i>Dashboard Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="/logout"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="/logout" method="GET" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                        </li>
                    </div>
                    <!-- Set default value-->
                    <form class="d-flex gap-2" role="search" action="/search">
                        <input class="form-control" id="search-bar" name="term" type="search"
                            placeholder="Cari Buku" aria-label="Cari Buku" value="{{ request('term') }}" /><button
                            class="btn btn-info d-flex gap-2" type="submit">
                            <i class="bi-search"></i>Cari
                        </button>
                    </form>
                </div>
            </div>
        </nav>
