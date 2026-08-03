@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/master-data/siswa">Siswa</a>
            </li>
            <li class="breadcrumb-item active"><span>Detail</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="d-flex mb-4 justify-content-between align-items-end flex-wrap gap-3">
            <h1 class="mb-0">Detail Siswa</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $siswa->nama }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td>{{ $siswa->nim_nip }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><span class="badge text-bg-primary">{{ ucfirst($siswa->role) }}</span></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $siswa->created_at ? $siswa->created_at->format('d F Y H:i') : '-' }}</td>
                    </tr>
                </table>
                <div class="d-flex gap-2 mt-4">
                    <a href="/master-data/siswa/{{ $siswa->id }}/edit" class="btn btn-primary">Ubah</a>
                    <a href="/master-data/siswa" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
