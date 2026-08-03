@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Jenis</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Jenis</h1><a class="btn btn-primary" href="/master-data/jenis/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah Jenis</span></a>
        </div>
        <div class="card">
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th data-priority="1">Nama</th>

                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarjenis as $jenis)
                        <tr>
                            <td>{{ $jenis->nama }}</td>
                            <td>
                                <div class="btn-group-action"><span data-coreui-toggle="tooltip"
                                        data-coreui-title="Ubah jenis"><a class="btn btn-link btn-sm link-body-emphasis"
                                            href="/master-data/jenis/{{ $jenis->id }}/edit" aria-label="Ubah jenis">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                            </svg>
                                        </a></span>
                                    <span data-coreui-toggle="tooltip" data-coreui-title="Hapus jenis">
                                        <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus jenis"
                                            data-coreui-toggle="modal"
                                            data-coreui-target="#jenis-delete-modal{{ $jenis->id }}">
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
            {{ $daftarjenis->links() }}
        </div>
        @foreach ($daftarjenis as $jenis)
            <div class="modal fade" id="jenis-delete-modal{{ $jenis->id }}" tabindex="-1"
                aria-labelledby="jenis-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/master-data/jenis/{{ $jenis->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="jenis-delete-modal-label">Hapus jenis?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus jenis dengan nama
                                    <strong>{{ $jenis->nama }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    jenis</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
