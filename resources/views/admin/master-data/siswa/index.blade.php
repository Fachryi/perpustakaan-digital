@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item active"><span>Siswa</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Siswa</h1><a class="btn btn-primary" href="/master-data/siswa/create">
                <svg class="icon me-sm-2">
                    <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-plus"></use>
                </svg><span class="d-none d-sm-inline">Tambah siswa</span></a>
        </div>
        <div class="card">
            <table class="table table-striped align-middle" style="width: 100%">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th data-priority="1">Nama</th>
                        <th>Kelas</th>
                        <th data-priority="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarsiswa as $siswa)
                        <tr>
                            <td>{{ $siswa->nim_nip }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                            <td>
                                <div class="btn-group-action"><span data-coreui-toggle="tooltip"
                                        data-coreui-title="Ubah siswa"><a class="btn btn-link btn-sm link-body-emphasis"
                                            href="/master-data/siswa/{{ $siswa->id }}/edit"
                                            aria-label="Ubah siswa">
                                            <svg class="icon icon-lg">
                                                <use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-pencil"></use>
                                            </svg>
                                        </a></span>
                                    <span data-coreui-toggle="tooltip" data-coreui-title="Hapus siswa">
                                        <btn class="btn btn-link btn-sm link-danger" aria-label="Hapus siswa"
                                            data-coreui-toggle="modal"
                                            data-coreui-target="#siswa-delete-modal{{ $siswa->id }}">
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
            {{ $daftarsiswa->links() }}
        </div>
        @foreach ($daftarsiswa as $siswa)
            <div class="modal fade" id="siswa-delete-modal{{ $siswa->id }}" tabindex="-1"
                aria-labelledby="siswa-delete-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="/master-data/siswa/{{ $siswa->id }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="siswa-delete-modal-label">Hapus siswa?</h5>
                                <button class="btn-close" type="button" data-coreui-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">
                                    Hapus siswa dengan nama
                                    <strong>{{ $siswa->nama }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" type="submit" data-coreui-dismiss="modal"
                                    data-toast-toggle="">Hapus
                                    siswa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
