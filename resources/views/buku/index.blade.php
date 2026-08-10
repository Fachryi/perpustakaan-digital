@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Buku</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Buku</h1><a class="btn btn-primary" href="/dashboard/buku/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah Buku</span></a>
        </div>
        <div class="card">
            <div class="card-header">
                <form action="" class="d-flex justify-content-between">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <input type="search" placeholder="Cari buku..." class="form-control form-control-sm" name="search" style="min-width: 220px;">
                        <select name="status" id="status" class="form-control-sm" onchange="this.form.submit()">
                            <option value="" @selected(request('status') == '')>Semua Buku</option>
                            <option value="tdktersedia" @selected(request('status') == 'tdktersedia')>Tidak Tersedia</option>
                            <option value="tersedia" @selected(request('status') == 'tersedia')>Tersedia</option>
                        </select>
                        @if (auth()->user()->role == 'admin')
                            <select name="jenis_id" id="jenis_id" class="form-control-sm" onchange="this.form.submit()">
                                <option value="">Semua jenis</option>
                                @foreach ($daftarJenis as $jenis)
                                    <option value="{{ $jenis->id }}" @selected(request('jenis_id') == $jenis->id)>{{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                    </div>

                    
                </form>

            </div>
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th data-priority="1">Judul</th>  
                        <th>Jumlah</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th data-priority="3">Jenis</th>
                        @if (auth()->user()->role == 'admin')
                            <th>Status</th>
                        @endif
                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarBuku as $buku)
                        <tr>
                            <td>{{ $buku->judul }}</td>
                            
                            <td>{{ $buku->jumlah }}</td>
                            <td>{{ $buku->pengarang }}</td>
                            
                            <td>{{ $buku->tahun_terbit }}</td>
                            <td>
                                @if ($buku->jenis_id == 1)
                                    <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id == 2)
                                    <span class="badge text-white fw-normal text-bg-success text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id == 3)
                                    <span class="badge text-white fw-normal text-bg-info text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id > 3)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                            </td>
                            @if (auth()->user()->role == 'admin')
                                <td>
                                    @if ($buku->jumlah == 0)
                                        <span
                                            class="badge text-white fw-normal text-bg-warning text-capitalize">Dipinjam</span>
                                    @endif
                                    @if ($buku->jumlah > 0)
                                        <span
                                            class="badge text-white fw-normal text-bg-success text-capitalize">Tersedia</span>
                                    @endif
                                           @if ($buku->status == 'habis')
                                        <span
                                            class="badge text-white fw-normal text-bg-danger text-capitalize">{{ $buku->status }}</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="btn-group-action">
                                    @if (auth()->user()->role == 'admin')
                                        @if ($buku->status == 'proses')
                                            <span data-coreui-toggle="tooltip" data-coreui-title="Approve buku"><a
                                                    class="btn btn-link btn-sm link-body-emphasis"
                                                    href="/buku/{{ $buku->id }}/terima"
                                                    onclick="return confirm('Apakah anda yakin ?')"
                                                    aria-label="Approve buku">
                                                    <svg class="icon icon-lg">
                                                        <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-check">
                                                        </use>
                                                    </svg></a>
                                            </span>
                                        @endif
                                    @endif

                                    <span data-coreui-toggle="tooltip" data-coreui-title="Detail buku"><a
                                            class="btn btn-link btn-sm link-body-emphasis"
                                            href="/dashboard/buku/{{ $buku->id }}" aria-label="Detail buku">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-search"></use>
                                            </svg></a></span>
                                    @if (auth()->user()->role == 'admin')
                                        <span data-coreui-toggle="tooltip" data-coreui-title="Ubah buku"><a
                                                class="btn btn-link btn-sm link-body-emphasis"
                                                href="/dashboard/buku/{{ $buku->id }}/edit" aria-label="Ubah buku">
                                                <svg class="icon icon-lg">
                                                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                                </svg></a></span>
                                    @endif
                                    @if ($buku->file)
                                        <span data-coreui-toggle="tooltip" data-coreui-title="Unduh buku"><a
                                                class="btn btn-link btn-sm link-body-emphasis"
                                                href="/storage/{{ $buku->file->file_name }}" target="_blank" download
                                                aria-label="Unduh buku">
                                                <svg class="icon icon-lg">
                                                    <use
                                                        xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-data-transfer-down">
                                                    </use>
                                                </svg></a></span>
                                    @endif
                                    @if (auth()->user()->role == 'admin')
                                        <span data-coreui-toggle="tooltip" data-coreui-title="Hapus buku">
                                            <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus buku"
                                                data-coreui-toggle="modal"
                                                data-coreui-target="#buku-delete-modal{{ $buku->id }}">
                                                <svg class="icon icon-lg">
                                                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-trash"></use>
                                                </svg>
                                            </btn>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $daftarBuku->links() }}
        </div>
        @foreach ($daftarBuku as $buku)
            <div class="modal fade" id="buku-delete-modal{{ $buku->id }}" tabindex="-1"
                aria-labelledby="buku-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/dashboard/buku/{{ $buku->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="buku-delete-modal-label">Hapus buku?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus buku dengan judul
                                    <strong>{{ $buku->judul }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button"
                                    data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    buku</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
