@extends('layouts.authentication')
@section('breadcrumb')
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <h1 class="mb-4">Selamat Datang, {{ auth()->user()->nama }}</h1>

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
        @endphp

        @if($peminjamanTerlambat->count() > 0 || $totalDendaBelumBayar > 0)
        <div class="row g-3 mb-4">
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
        @endif

        <h2 class="mb-3">Daftar Peminjaman Buku</h2>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(auth()->user()->peminjamanBuku as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                <td>{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                                <td>{{ $peminjaman->status }}</td>
                                <td>
                                    @if($peminjaman->approval == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($peminjaman->approval == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($peminjaman->status == 'dipinjam' && $peminjaman->approval == 'approved')
                                    <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit">Kembalikan</button>
                                    </form>
                                    @elseif($peminjaman->approval == null)
                                        <span class="text-muted">Menunggu persetujuan admin</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
