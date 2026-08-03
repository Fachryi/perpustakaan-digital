@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/master-data/siswa">Siswa</a>
            </li>
            <li class="breadcrumb-item active"><span>Tambah</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Tambah siswa</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif
            <form action="/master-data/siswa" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="nama">Nama siswa</label>
                        <input class="form-control" id="nama" placeholder="Masukkan Nama" required
                            name="nama">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select required name="kelas_id" id="kelas_id" class="form-select">
                            @foreach ($daftarKelas as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="nis">NIS</label>
                        <input class="form-control" id="nis" placeholder="Masukkan NIS" required name="nis">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" placeholder="Masukkan Password" required name="password"
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
