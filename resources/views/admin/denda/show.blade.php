@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('admin.denda.index') }}">Denda</a>
            </li>
            <li class="breadcrumb-item active"><span>Detail Denda</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">Detail Denda</h1>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">ID Denda</label>
                            <div class="form-control">{{ $denda->id }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peminjaman</label>
                            <div class="form-control">#{{ $denda->peminjaman->id ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Siswa</label>
                            <div class="form-control">{{ $denda->peminjaman->user->nama ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Buku</label>
                            <div class="form-control">{{ $denda->peminjaman->buku->judul ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <div class="form-control">{{ $denda->formatted_amount }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-control">{{ ucfirst($denda->status) }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bayar</label>
                            <div class="form-control">{{ optional($denda->tanggal_bayar)->format('d/m/Y') ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <div class="form-control">{{ $denda->keterangan ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.denda.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
