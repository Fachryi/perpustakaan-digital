@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>admin</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">admin</h1><a class="btn btn-primary" href="/admin/users/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah admin</span></a>
        </div>
        <div class="card">
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th data-priority="1">Nama</th>
                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftaradmin as $admin)
                        <tr>
                            <td>{{ $admin->nim_nip }}</td>
                            <td>{{ $admin->nama }}</td>

                            <td>
                                <div class="btn-group-action"><span data-coreui-toggle="tooltip"
                                        data-coreui-title="Ubah admin"><a class="btn btn-link btn-sm link-body-emphasis"
                                            href="/admin/users/{{ $admin->id }}/edit" aria-label="Ubah admin">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                            </svg>
                                        </a></span>
                                    <span data-coreui-toggle="tooltip" data-coreui-title="Hapus admin">
                                        <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus admin"
                                            data-coreui-toggle="modal"
                                            data-coreui-target="#admin-delete-modal{{ $admin->id }}">
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
            {{ $daftaradmin->links() }}
        </div>
        @foreach ($daftaradmin as $admin)
            <div class="modal fade" id="admin-delete-modal{{ $admin->id }}" tabindex="-1"
                aria-labelledby="admin-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/admin/users/{{ $admin->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="admin-delete-modal-label">Hapus admin?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus admin dengan nama
                                    <strong>{{ $admin->nama }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    admin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
