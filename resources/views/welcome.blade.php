@extends('guest')
@section('content')
    <div class="flex-grow-1">
        <section class="text-white position-relative d-flex flex-column align-items-center justify-content-center"
            id="hero">
            <div class="position-relative z-1 container" id="hero-content">
                <h1 class="mb-3">
                    Perpustakaan Digital <br class="d-none d-sm-inline" />SMP Negeri 1 Parangloe
                </h1>
                <!-- Set default value-->
                <form class="d-flex gap-2" role="search" action="/search">
                    <input class="form-control" id="search-bar" name="term" type="search" placeholder="Cari buku"
                        aria-label="Cari Buku" value="" /><button class="btn btn-info  d-flex gap-2" type="submit">
                        <i class="bi-search"></i>Cari
                    </button>
                </form>
            </div>
            <picture class="position-absolute z-0 pe-none">
                <source srcset="assets/parangloe.png" type="image/webp" />
                <img class="object-fit-cover w-100 h-100" src="assets/parangloe.png" alt="Kampus" />
            </picture>
        </section>

        @if(auth()->check() && auth()->user()->role === 'siswa' && ($peminjamanTerlambat->count() > 0 || $totalDendaBelumBayar > 0))
        <section class="container pt-4" id="notifikasi">
            <div class="row g-3">
                @if($peminjamanTerlambat->count() > 0)
                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white d-flex align-items-center gap-2">
                            <i class="bi-exclamation-triangle-fill"></i>
                            <strong>Buku Terlambat Dikembalikan</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Terlambat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamanTerlambat as $p)
                                    <tr>
                                        <td>{{ $p->buku->judul ?? '-' }}</td>
                                        <td>{{ $p->tanggal_kembali->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ now()->diffInDays($p->tanggal_kembali) }} hari
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if($totalDendaBelumBayar > 0)
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark d-flex align-items-center gap-2">
                            <i class="bi-cash-stack"></i>
                            <strong>Denda Belum Dibayar</strong>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total denda yang harus dibayar:</span>
                                <h4 class="mb-0 text-danger">Rp {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}</h4>
                            </div>
                            <p class="text-muted small mt-2 mb-0">Silakan hubungi petugas perpustakaan untuk pembayaran denda.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif

        <section class="container py-5" id="literature">
            <h1 class="mb-4">Buku Terbaru</h1>
            <div class="row g-4 mb-4">
                @foreach ($daftarBuku->take(4) as $buku)
                    <article class="literature col-md-6">
                        <div class="mb-2 d-flex gap-2 align-items-center">
                            @if ($buku->jenis_id == 1)
                                <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                                    {{ $buku->jenis->nama }}
                                </span>
                            @endif
                            @if ($buku->jenis_id == 2)
                                <span class="badge text-white fw-normal text-bg-success text-capitalize">
                                    {{ $buku->jenis->nama }}
                                </span>
                            @endif
                            @if ($buku->jenis_id == 3)
                                <span class="badge text-white fw-normal text-bg-info text-capitalize">
                                    {{ $buku->jenis->nama }}
                                </span>
                            @endif
                            @if ($buku->jenis_id > 3)
                                <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                    {{ $buku->jenis->nama }}
                                </span>
                            @endif
                            <p class="mb-0">{{ $buku->tahun_terbit }}</p>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <img src="{{ $buku->foto_url }}" alt="{{ $buku->judul }}" class="rounded" style="width: 80px; height: 110px; object-fit: cover;">
                            </div>
                            <div>
                                <a href="/buku/{{ $buku->id }}">
                                    <h5>
                                        {{ $buku->judul }}
                                    </h5>
                                </a>
                                <p class="mb-0">
                                    <span class="fw-bold">{{ $buku->user->nama ?? $buku->pengarang }}</span>,
                                    <i class="bi-info-circle ms-2" data-bs-toggle="tooltip" data-bs-html="true"
                                        data-bs-title="&lt;div class='text-start'&gt;
                    &lt;b&gt;Penulis: &lt;/b&gt;{{ $buku->user->name ?? $buku->pengarang }}&lt;br/&gt;
                    
                &lt;/div&gt;"></i>
                                </p>
                                @if ($buku->abstrak)
                                    <p class="mb-0 text-muted small">{{ \Illuminate\Support\Str::limit($buku->abstrak, 100) }}</p>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach

            </div>
            <label class="btn btn-info" for="search-bar">Cari buku lain</label>
        </section>
        <section class="text-bg-primary bg-primary-subtle py-5" id="fakultas">
            <div
                class="container d-flex flex-md-row flex-column align-items-md-center align-items-start justify-items-end gap-4">
                <div>
                    <h1 class="mb-4">Kegiatan</h1>
                    <a class="btn btn-info" href="#">Lihat Semua</a>
                </div>
                <div id="image-collage">
                    <img src="assets/ps.png" /><img src="assets/silat.jpg" /><img src="assets/pr.png" />
                </div>
            </div>
        </section>
    </div>
@endsection
