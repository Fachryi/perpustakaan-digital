@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
            <li class="breadcrumb-item active"><span>Laporan Data Buku</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Laporan Data Buku</h1>
            <a href="{{ route('admin.laporan.download') }}?jenis_laporan=buku&format=pdf"
               class="btn btn-success" target="_blank">
                <svg class="icon me-1"><use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-file"></use></svg>
                Download PDF
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.laporan.buku') }}"
                      class="d-flex justify-content-between gap-3 flex-wrap">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" style="max-width: 400px;"
                           placeholder="Cari judul, pengarang, atau kode buku...">
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
                            <th>#</th>
                            <th>Kode Buku</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bukuReport as $no => $buku)
                            <tr>
                                <td>{{ $bukuReport->firstItem() + $no }}</td>
                                <td>
                                    <span class="badge bg-secondary font-monospace">
                                        {{ $buku->kode_buku ?? '-' }}
                                    </span>
                                </td>
                                <td class="fw-semibold">
                                    <a href="{{ route('buku.show', $buku->id) }}">{{ $buku->judul }}</a>
                                </td>
                                <td>{{ $buku->pengarang ?? '-' }}</td>
                                <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                                <td>
                                    @if($buku->kategori)
                                        <span class="badge bg-primary">{{ $buku->kategori->nama }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $buku->jenis->nama ?? '-' }}</td>
                                <td>{{ $buku->jumlah ?? 0 }}</td>
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
                    {{ $bukuReport->withQueryString()->links() }}
                </div>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data buku ditemukan.
                </div>
            @endif
        </div>
    </div>
@endsection
