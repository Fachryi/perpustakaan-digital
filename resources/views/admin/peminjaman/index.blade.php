@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Peminjaman</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Peminjaman</h1><a class="btn btn-primary" href="{{ route('admin.peminjaman.create') }}">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah Peminjaman</span></a>
        </div>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="d-flex justify-content-between flex-wrap gap-2">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <input type="search" name="search" class="form-control form-control-sm" placeholder="Cari nama siswa atau judul buku"
                            value="{{ request('search') }}" style="min-width: 220px;">

                        <select class="form-control-sm" id="status" name="status">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                            </option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan</option>
                        </select>

                        @if (auth()->user()->isAdmin())
                            <select class="form-control-sm" id="user_id" name="user_id">
                                <option value="">Semua User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            Filter
                     </button>
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex justify-content-center align-items-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            @if ($peminjaman->count() > 0)
                <table class="table table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Denda</th>
                            <th>Status</th>

                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjaman as $item)
                            <tr class="{{ $item->isOverdue() ? 'table-danger' : '' }}">
                                
                                <td>
                                    <div class="fw-bold">{{ $item->user->nama }}</div>
                                    <small class="text-muted">{{ $item->user->email }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $item->buku->judul }}</div>
                                    <small class="text-muted">{{ $item->buku->penulis }}</small>
                                </td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>
                                    {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}
                                    @if ($item->isOverdue())
                                        <br><small class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Terlambat {{ abs($item->getDaysRemaining()) }} hari
                                        </small>
                                    @elseif($item->status === 'dipinjam' && $item->tanggal_kembali)
                                        <br><small class="text-info">
                                            {{ $item->getDaysRemaining() }} hari lagi
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $totalDenda = $item->denda->sum('jumlah');
                                        $latestDenda = $item->denda->sortByDesc('created_at')->first();
                                    @endphp
                                    @if ($totalDenda > 0)
                                        <div>{{ 'Rp ' . number_format($totalDenda, 0, ',', '.') }}</div>
                                        <span class="badge bg-{{ $latestDenda && $latestDenda->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $latestDenda && $latestDenda->status === 'paid' ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                                <td>
                                    @php
                                        $statusLabel = $item->status === 'lunas' ? 'dikembalikan' : $item->status;
                                        $badgeColor = $statusLabel === 'dipinjam' ? 'warning' : 'success';
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ ucfirst($statusLabel) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <span data-coreui-toggle="tooltip" data-coreui-title="Hapus">
                                            <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus"
                                                data-coreui-toggle="modal"
                                                data-coreui-target="#buku-delete-modal{{ $item->id }}">
                                                <svg class="icon icon-lg">
                                                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-trash"></use>
                                                </svg>
                                            </btn>
                                        </span>

                                        <div class="modal fade" id="buku-delete-modal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="bukuDeleteModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"
                                                            id="bukuDeleteModalLabel{{ $item->id }}">Konfirmasi Hapus
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-coreui-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus
                                                        <strong>{{ $item->judul ?? 'buku ini' }}</strong>?
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-coreui-dismiss="modal">Batal</button>
                                                        <form action="{{ route('peminjaman.destroy', $item->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($item->status === 'dipinjam' && $item->approval === 'approved')
                                            <span data-coreui-toggle="tooltip" data-coreui-title="Perpanjang peminjaman">
                                                <button type="button" class="btn btn-link btn-sm link-primary"
                                                    aria-label="Perpanjang peminjaman"
                                                    data-coreui-toggle="modal"
                                                    data-coreui-target="#buku-extend-modal{{ $item->id }}">
                                                    <svg class="icon icon-lg">
                                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                                                    </svg>
                                                </button>
                                            </span>

                                            <div class="modal fade" id="buku-extend-modal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="bukuExtendModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"
                                                                id="bukuExtendModalLabel{{ $item->id }}">Perpanjang Peminjaman</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-coreui-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form method="POST" action="{{ route('peminjaman.extend', $item->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tanggal Kembali Baru</label>
                                                                    <input type="date" name="tanggal_kembali"
                                                                        class="form-control"
                                                                        min="{{ date('Y-m-d') }}"
                                                                        value="{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('Y-m-d') : date('Y-m-d') }}"
                                                                        required>
                                                                </div>
                                                                <p class="text-muted mb-0">
                                                                    Pilih tanggal baru untuk memperpanjang masa peminjaman.
                                                                </p>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-coreui-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Perpanjang</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <span data-coreui-toggle="tooltip" data-coreui-title="Kembalikan buku">
                                                <button type="button" class="btn btn-link btn-sm link-success"
                                                    aria-label="Kembalikan buku"
                                                    data-coreui-toggle="modal"
                                                    data-coreui-target="#buku-return-modal{{ $item->id }}">
                                                    <svg class="icon icon-lg">
                                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-book"></use>
                                                    </svg>
                                                </button>
                                            </span>

                                            <div class="modal fade" id="buku-return-modal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="bukuReturnModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title"
                                                                id="bukuReturnModalLabel{{ $item->id }}">Konfirmasi Pengembalian</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-coreui-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin mengembalikan
                                                            <strong>{{ $item->buku->judul ?? 'buku ini' }}</strong>?
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-coreui-dismiss="modal">Batal</button>
                                                            <form method="POST"
                                                                action="{{ route('peminjaman.return', $item->id) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $peminjaman->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-4">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="text-muted">Tidak ada data peminjaman</h4>
            <p class="text-muted">Belum ada peminjaman yang sesuai dengan filter.</p>
        </div>
        @endif
    </div>
@endsection
