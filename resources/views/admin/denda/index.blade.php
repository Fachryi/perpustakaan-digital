@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Denda</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Denda</h1><a class="btn btn-primary" href="{{ route('admin.denda.create') }}">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah Denda</span></a>
        </div>

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.denda.index') }}" class="d-flex justify-content-between gap-3 flex-wrap">
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                            placeholder="Cari nama siswa atau judul buku">
                        <select class="form-control form-control-sm" name="status">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                        <a href="{{ route('admin.denda.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex justify-content-center align-items-center" >Reset</a>
                    </div>
                </form>
            </div>

            @if ($denda->count() > 0)
                <table class="table table-striped align-middle mb-0" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Peminjaman</th>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Jenis Denda</th>
                            <th>Hari Terlambat</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($denda as $item)
                            <tr>
                                <td>#{{ $item->peminjaman->id ?? '-' }}</td>
                                <td>{{ $item->peminjaman->user->nama ?? '-' }}</td>
                                <td>{{ $item->peminjaman->buku->judul ?? '-' }}</td>
                                <td>
                                    @if($item->jenis_denda === 'kehilangan')
                                        <span class="badge bg-danger">Kehilangan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Keterlambatan</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($item->hari_terlambat ?? 0) > 0)
                                        <span class="text-danger fw-semibold">{{ $item->hari_terlambat }} hari</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->formatted_amount }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($item->tanggal_bayar)->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-link btn-sm" href="{{ route('admin.denda.show', $item->id) }}"
                                            aria-label="Lihat denda">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-eye"></use>
                                            </svg>
                                        </a>
                                        @if ($item->status === 'unpaid')
                                            <button class="btn btn-link btn-sm link-success" type="submit"
                                                form="confirm-payment-{{ $item->id }}"
                                                aria-label="Konfirmasi pembayaran">
                                                <svg class="icon icon-lg">
                                                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-check"></use>
                                                </svg>
                                            </button>
                                        @endif
                                        <button class="btn btn-link btn-sm link-danger" type="button"
                                            data-coreui-toggle="modal" data-coreui-target="#denda-delete-modal{{ $item->id }}"
                                            aria-label="Hapus denda">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-trash"></use>
                                            </svg>
                                        </button>
                                    </div>

                                    @if ($item->status === 'unpaid')
                                        <form id="confirm-payment-{{ $item->id }}" action="{{ route('admin.denda.confirm-payment', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                    @endif

                                    <div class="modal fade" id="denda-delete-modal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="denda-delete-modal-label{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('admin.denda.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="denda-delete-modal-label{{ $item->id }}">Hapus Denda</h5>
                                                        <button class="btn-close btn-close-white" type="button"
                                                            data-coreui-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus denda untuk
                                                        <strong>{{ $item->peminjaman->user->nama ?? 'siswa ini' }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-coreui-dismiss="modal">Batal</button>
                                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $denda->links() }}
            @else
                <div class="p-4 text-center text-muted">Belum ada data denda.</div>
            @endif
        </div>
    </div>
@endsection
