<nav class="navbar navbar-expand-md justify-content-between fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/welcome"><img class="me-2"
                src="assets/frater.png" alt="Logo ITH" />Digilib SMP Negeri 1 Parangloe</a><button class="navbar-toggler border-0"
            type="button" data-bs-toggle="collapse" data-bs-target="#navbar-links" aria-controls="navbar-links"
            aria-label="Toggle navigation">
            <span class="bi-list fs-2"></span>
        </button>
        <div class="collapse navbar-collapse gap-3 justify-content-end" id="navbar-links">
            <div class="navbar-nav order-md-last flex-grow-0">
                <li class="nav-item">
                    <a class="nav-link" href="/#">FAQ</a>
                </li>
                <li class="nav-item">
                    
                   @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Hi, {{ auth()->user()->nama }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
        </div>
    </div>
</nav>
