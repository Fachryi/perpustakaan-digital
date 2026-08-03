@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Peminjaman Saya</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Peminjaman Saya</h1>
        </div>

        <h5 class="mb-3">Sedang Dipinjam</h5>
        <div class="card mb-4">
            @if ($activeBorrowings->count() > 0)
                <table class="table table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeBorrowings as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}</td>
                                <td>
                                    {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}
                                    @if ($peminjaman->isOverdue())
                                        <br><span class="text-danger">Terlambat {{ abs($peminjaman->getDaysRemaining()) }} hari</span>
                                    @endif
                                </td>
                                <td><span class="badge text-bg-warning">{{ ucfirst($peminjaman->status) }}</span></td>
                                <td>
                                    <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit">Kembalikan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="card-body">
                    <p class="mb-0 text-muted">Tidak ada peminjaman aktif.</p>
                </div>
            @endif
        </div>

        <h5 class="mb-3">Riwayat</h5>
        <div class="card">
            @if ($historyBorrowings->count() > 0)
                <table class="table table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historyBorrowings as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}</td>
                                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}</td>
                                <td><span class="badge text-bg-success">{{ ucfirst($peminjaman->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $historyBorrowings->links() }}
            @else
                <div class="card-body">
                    <p class="mb-0 text-muted">Belum ada riwayat peminjaman.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
