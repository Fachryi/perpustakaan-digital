@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/master-data/jenis">jenis</a>
            </li>
            <li class="breadcrumb-item active"><span>Edit</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Edit jenis</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif
            <form action="/master-data/jenis/{{ $jenis->id }}/update" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="nama">Nama jenis</label>
                        <input class="form-control" id="nama" placeholder="Disertasi" name="nama"
                            value="{{ $jenis->nama }}">
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
