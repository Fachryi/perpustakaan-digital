@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Kelas</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Kelas</h1><a class="btn btn-primary" href="/master-data/kelas/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah kelas</span></a>
        </div>
        <div class="card">
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th data-priority="1">Nama</th>
                        {{-- <th>Gambar</th> --}}
                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarKelas as $kelas)
                        <tr>
                            <td>{{ $kelas->nama }} </td>
                            {{-- <td><img src="/storage/{{ $kelas->gambar }}" alt="" width="200px"></td> --}}
                            <td>
                                <div class="btn-group-action"><span data-coreui-toggle="tooltip"
                                        data-coreui-title="Ubah kelas"><a class="btn btn-link btn-sm link-body-emphasis"
                                            href="/master-data/kelas/{{ $kelas->id }}/edit" aria-label="Ubah kelas">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                            </svg>
                                        </a></span>
                                    <span data-coreui-toggle="tooltip" data-coreui-title="Hapus kelas">
                                        <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus kelas"
                                            data-coreui-toggle="modal"
                                            data-coreui-target="#kelas-delete-modal{{ $kelas->id }}">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-trash"></use>
                                            </svg>
                                        </btn>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $daftarKelas->links() }}
        </div>
        @foreach ($daftarKelas as $kelas)
            <div class="modal fade" id="kelas-delete-modal{{ $kelas->id }}" tabindex="-1"
                aria-labelledby="kelas-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/master-data/kelas/{{ $kelas->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="kelas-delete-modal-label">Hapus kelas?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus kelas dengan nama
                                    <strong>{{ $kelas->nama }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    kelas</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
