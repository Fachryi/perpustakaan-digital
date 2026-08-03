@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Peminjaman Terlambat</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Peminjaman Terlambat</h1>
        </div>
        <div class="card">
            @if ($overdueBooks->count() > 0)
                <table class="table table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($overdueBooks as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->user->nama ?? '-' }}</td>
                                <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}</td>
                                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}</td>
                                <td><span class="badge text-bg-danger">Terlambat {{ abs($peminjaman->getDaysRemaining()) }} hari</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $overdueBooks->links() }}
            @else
                <div class="card-body">
                    <p class="mb-0 text-muted">Tidak ada peminjaman yang terlambat.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
