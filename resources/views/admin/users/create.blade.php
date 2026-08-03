@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/admin/users">admin</a>
            </li>
            <li class="breadcrumb-item active"><span>Tambah</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Tambah Admin</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif
            <form action="/admin/users" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="nama">Nama </label>
                        <input class="form-control" id="nama" placeholder="Prasetya abdi putra" required
                            name="nama">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="nim_nip">Username</label>
                        <input class="form-control" id="nim_nip" placeholder="Username" required name="nim_nip">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" placeholder="*******" required name="password"
                            type="password">
                    </div>


                </div>
                <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
                    <button class="btn btn-outline-secondary" type="button" onclick="history.back()">Kembali</button>
                    <button class="btn btn-primary" type="submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>
@endsection
