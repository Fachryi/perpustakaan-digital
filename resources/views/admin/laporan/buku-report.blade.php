@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
            <li class="breadcrumb-item active"><span>Laporan Buku</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Laporan Buku</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.laporan.buku') }}" class="d-flex justify-content-between gap-3 flex-wrap">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" 
                        style="max-width: 400px;" placeholder="Cari judul atau pengarang...">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Cari</button>
                        <a href="{{ route('admin.laporan.buku') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            @if ($bukuReport->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Jumlah</th>
                            <th>Jenis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bukuReport as $buku)
                            <tr>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->pengarang ?? '-' }}</td>
                                <td>{{ $buku->penerbit ?? '-' }}</td>
                                <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                                <td>{{ $buku->jumlah ?? 0 }}</td>
                                <td>{{ $buku->jenis->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $buku->status === 'tersedia' ? 'success' : 'warning' }}">
                                        {{ ucfirst($buku->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="card-footer">
                    {{ $bukuReport->links() }}
                </div>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data buku
                </div>
            @endif
        </div>
    </div>
@endsection
