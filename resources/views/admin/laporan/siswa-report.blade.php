@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
            <li class="breadcrumb-item active"><span>Laporan Siswa</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Laporan Siswa</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.laporan.siswa') }}" class="d-flex justify-content-between gap-3 flex-wrap">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" 
                        style="max-width: 400px;" placeholder="Cari nama atau NIS...">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Cari</button>
                        <a href="{{ route('admin.laporan.siswa') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            @if ($siswaReport->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>Total Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswaReport as $siswa)
                            <tr>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nim_nip }}</td>
                                <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                <td>{{ $siswa->email ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $siswa->peminjamanBuku()->count() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="card-footer">
                    {{ $siswaReport->links() }}
                </div>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data siswa
                </div>
            @endif
        </div>
    </div>
@endsection
