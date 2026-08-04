@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item active"><span>Home</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <h1 class="mb-4">Selamat Datang, {{ auth()->user()->name }}</h1>
        
        <!-- Statistik Umum -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white small">Total Buku</div>
                            <div class="fs-5 fw-semibold">{{ $totalBuku }}</div>
                        </div>
                        <svg class="icon icon-lg">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-book"></use>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-success">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white small">Total Siswa</div>
                            <div class="fs-5 fw-semibold">{{ $totalSiswa }}</div>
                        </div>
                        <svg class="icon icon-lg">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-people"></use>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white small">Total Peminjaman</div>
                            <div class="fs-5 fw-semibold">{{ $totalPeminjaman }}</div>
                        </div>
                        <svg class="icon icon-lg">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white small">Total Denda</div>
                            <div class="fs-5 fw-semibold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                        </div>
                        <svg class="icon icon-lg">
                            <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-money"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Denda -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card border-success">
                    <div class="card-body pb-0">
                        <div class="text-success small">Denda Terbayar</div>
                        <div class="fs-5 fw-semibold text-success">Rp {{ number_format($dendaTerbayar, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card border-warning">
                    <div class="card-body pb-0">
                        <div class="text-warning small">Denda Belum Dibayar</div>
                        <div class="fs-5 fw-semibold text-warning">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card border-info">
                    <div class="card-body pb-0">
                        <div class="text-info small">Persentase Terbayar</div>
                        <div class="fs-5 fw-semibold text-info">{{ $totalDenda > 0 ? round(($dendaTerbayar / $totalDenda) * 100, 2) : 0 }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Peminjaman -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card border-primary">
                    <div class="card-body pb-0">
                        <div class="text-primary small">Peminjaman Aktif</div>
                        <div class="fs-5 fw-semibold text-primary">{{ $peminjamanAktif }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card border-success">
                    <div class="card-body pb-0">
                        <div class="text-success small">Peminjaman Dikembalikan</div>
                        <div class="fs-5 fw-semibold text-success">{{ $peminjamanKembali }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card border-danger">
                    <div class="card-body pb-0">
                        <div class="text-danger small">Peminjaman Terlambat</div>
                        <div class="fs-5 fw-semibold text-danger">{{ $peminjamanTerlambat }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Peminjaman Terbaru -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Peminjaman Terbaru</h5>
            </div>
            @if ($laporanPeminjaman->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanPeminjaman->take(10) as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->buku->judul ?? '-' }}</td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>{{ optional($item->tanggal_kembali)->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'dikembalikan' ? 'success' : ($item->status === 'dipinjam' ? 'primary' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data peminjaman
                </div>
            @endif
        </div>

        <!-- Tabel Denda Terbaru -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Denda Terbaru</h5>
            </div>
            @if ($laporanDenda->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanDenda->take(10) as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td>{{ $item->peminjaman->user->nama ?? '-' }}</td>
                                <td>{{ $item->peminjaman->buku->judul ?? '-' }}</td>
                                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($item->tanggal_bayar)->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data denda
                </div>
            @endif
        </div>
    </div>
@endsection
