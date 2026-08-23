@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/dashboard/buku">Buku</a></li>
            <li class="breadcrumb-item active"><span>Detail</span></li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Detail Buku</h1>
            <form>
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6>Kode Buku</h6>
                        <p class="mb-2"><span class="badge bg-primary fs-6">{{ $buku->kode_buku ?? '-' }}</span></p>
                        <h6>Foto Buku</h6>
                        <img src="{{ $buku->foto_url }}" alt="Foto Buku" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <h5>{{ $buku->judul }}</h5>
                        <p class="text-body-secondary">{{ $buku->sinopsis }}</p>
                        @if($buku->abstrak)
                            <h6>Abstrak</h6>
                            <p class="text-body-secondary small">{{ $buku->abstrak }}</p>
                        @endif
                    </div>
                </div>
                <div class="row border-top pt-3 mt-3">
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Status</h6>
                        @if ($buku->status == 'tersedia')
                            <span class="badge text-bg-success">Tersedia</span>
                        @elseif ($buku->status == 'dipinjam')
                            <span class="badge text-bg-secondary">Dipinjam</span>
                        @elseif ($buku->status == 'proses')
                            <span class="badge text-bg-warning">Pengajuan</span>
                        @elseif ($buku->status == 'ditolak')
                            <span class="badge text-bg-danger">Pengajuan Ditolak</span>
                        @endif
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Stok</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->jumlah }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Pengarang</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->pengarang ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Penerbit</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->penerbit ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Tahun Terbit</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->tahun_terbit ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Kelas</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->kelas->nama ?? 'Umum' }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Kategori</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->kategori->nama ?? ($buku->jenis->nama ?? '-') }}</p>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <h6>Dilihat</h6>
                        <p class="text-body-secondary mb-0">{{ $buku->view }} kali</p>
                    </div>
                    @if ($buku->fileBuku && $buku->fileBuku->file_name)
                        <div class="col-12 border-top pt-3 mt-2">
                            <h6>Berkas Digital</h6>
                            <a class="btn btn-outline-primary btn-sm mt-1" href="{{ $buku->fileBuku->file_url }}" target="_blank" download>
                                <svg class="icon me-1">
                                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                                </svg> Unduh / Baca File PDF
                            </a>
                        </div>
                    @endif
                </div>
            </form>
            @if(auth()->check() && auth()->user()->role == 'siswa')
                <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-primary" type="submit">Pinjam Buku</button>
                </form>
            @endif
        </div>
    </div>
@endsection
