@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/welcome">Perpustakaan</a></li>
            <li class="breadcrumb-item active"><span>Dashboard Saya</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        @include('sweetalert::alert')

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="mb-1">Selamat Datang, {{ auth()->user()->nama }}</h1>
                <p class="text-muted mb-0">NIS: {{ auth()->user()->nim_nip }} &bull; Kelas: {{ auth()->user()->kelas?->nama ?? '-' }}</p>
            </div>
            <a href="/welcome" class="btn btn-outline-primary">
                <i class="bi-search me-1"></i> Cari & Pinjam Buku
            </a>
        </div>

        @php
            $peminjamanTerlambat = \App\Models\PeminjamanBuku::with(['buku', 'denda'])
                ->where('user_id', auth()->id())
                ->where('status', 'dipinjam')
                ->where('approval', 'approved')
                ->where('tanggal_kembali', '<', now())
                ->get();

            $totalDendaBelumBayar = \App\Models\Denda::whereHas('peminjaman', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status', 'unpaid')
                ->sum('jumlah');

            $daftarPeminjaman = \App\Models\PeminjamanBuku::with('buku')
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();
        @endphp

        @if($peminjamanTerlambat->count() > 0 || $totalDendaBelumBayar > 0)
        <div class="row g-3 mb-4">
            @if($peminjamanTerlambat->count() > 0)
            <div class="col-md-6">
                <div class="card border-danger shadow-sm">
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
                                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') }}</td>
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
                <div class="card border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark d-flex align-items-center gap-2">
                        <i class="bi-cash-stack"></i>
                        <strong>Denda Belum Dibayar</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total denda yang harus dibayar:</span>
                            <h4 class="mb-0 text-danger fw-bold">Rp {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}</h4>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Silakan hubungi petugas perpustakaan untuk melunasi denda.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">Riwayat & Status Peminjaman Saya</h5>
            </div>
            <div class="card-body p-0">
                @if($daftarPeminjaman->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status Validasi</th>
                            <th>Status Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarPeminjaman as $peminjaman)
                            <tr>
                                <td>
                                    <a href="/buku/{{ $peminjaman->buku_id }}" class="fw-bold text-decoration-none">
                                        {{ $peminjaman->buku->judul ?? '-' }}
                                    </a>
                                    <br><small class="text-muted">{{ $peminjaman->buku->kode_buku ?? '' }}</small>
                                </td>
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($peminjaman->approval == 'approved')
                                        <span class="badge bg-success"><i class="bi-check-circle me-1"></i>Disetujui Admin</span>
                                    @elseif($peminjaman->approval == 'rejected')
                                        <span class="badge bg-danger"><i class="bi-x-circle me-1"></i>Ditolak Admin</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi-hourglass-split me-1"></i>Menunggu Validasi</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $peminjaman->status === 'dipinjam' ? 'info' : 'secondary' }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($peminjaman->status == 'dipinjam' && $peminjaman->approval == 'approved')
                                        <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" type="submit" onclick="return confirm('Kembalikan buku {{ addslashes($peminjaman->buku->judul ?? '') }}?')">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @elseif($peminjaman->approval == 'pending')
                                        <span class="text-muted small">Menunggu persetujuan admin</span>
                                    @elseif($peminjaman->approval == 'rejected')
                                        <span class="text-danger small">Ditolak</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi-journal-x display-4 mb-2"></i>
                    <p class="mb-0">Anda belum pernah meminjam buku.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
