@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
            <li class="breadcrumb-item active"><span>Laporan Anggota</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Laporan Anggota / Siswa</h1>
            <a href="{{ route('admin.laporan.download') }}?jenis_laporan=anggota&format=pdf"
               class="btn btn-success" target="_blank">
                <svg class="icon me-1"><use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-file"></use></svg>
                Download PDF
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.laporan.siswa') }}"
                      class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control form-control-sm" style="max-width: 280px;"
                               placeholder="Cari nama atau NIS...">
                        <select name="kelas_id" class="form-select form-select-sm" style="max-width: 160px;">
                            <option value="">Semua Kelas</option>
                            @foreach($daftarKelas as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    Kelas {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                        <a href="{{ route('admin.laporan.siswa') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            @if ($siswaReport->count() > 0)
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Jml Pinjam</th>
                            <th>Total Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswaReport as $no => $siswa)
                            <tr>
                                <td>{{ $siswaReport->firstItem() + $no }}</td>
                                <td class="fw-semibold">{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nim_nip }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $siswa->kelas->nama ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $siswa->peminjaman_buku_count }}x
                                    </span>
                                </td>
                                <td>
                                    @if(($siswa->total_denda ?? 0) > 0)
                                        <span class="text-danger fw-semibold">
                                            Rp {{ number_format($siswa->total_denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($siswa->status_aktif ?? false)
                                        <span class="badge bg-warning text-dark">Sedang Meminjam</span>
                                    @else
                                        <span class="badge bg-success">Bebas Pinjam</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="card-footer">
                    {{ $siswaReport->withQueryString()->links() }}
                </div>
            @else
                <div class="card-body text-center text-muted py-4">
                    Tidak ada data siswa ditemukan.
                </div>
            @endif
        </div>
    </div>
@endsection
