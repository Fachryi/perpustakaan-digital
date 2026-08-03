@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Guru</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Guru</h1><a class="btn btn-primary" href="/master-data/guru/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah guru</span></a>
        </div>
        <div class="card">
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th data-priority="1">Nama</th>
                       
                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarguru as $guru)
                        <tr>
                            <td>{{ $guru->nim_nip }}</td>
                            <td>{{ $guru->nama }}</td>
                            
                            <td>
                                <div class="btn-group-action"><span data-coreui-toggle="tooltip"
                                        data-coreui-title="Ubah guru"><a class="btn btn-link btn-sm link-body-emphasis"
                                            href="/master-data/guru/{{ $guru->id }}/edit" aria-label="Ubah guru">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                            </svg>
                                        </a></span>
                                    <span data-coreui-toggle="tooltip" data-coreui-title="Hapus guru">
                                        <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus guru"
                                            data-coreui-toggle="modal"
                                            data-coreui-target="#guru-delete-modal{{ $guru->id }}">
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
            {{ $daftarguru->links() }}
        </div>
        @foreach ($daftarguru as $guru)
            <div class="modal fade" id="guru-delete-modal{{ $guru->id }}" tabindex="-1"
                aria-labelledby="guru-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/master-data/guru/{{ $guru->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="guru-delete-modal-label">Hapus guru?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus guru dengan nama
                                    <strong>{{ $guru->nama }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    guru</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
