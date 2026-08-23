@extends('layouts.authentication')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/dashboard/buku">Buku</a>
            </li>
            <li class="breadcrumb-item active"><span>Detail</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Detail Buku</h1>
            <!--
                                                                                                                                  Flags:
                                                                                                                                    isDetail - Show detail view instead of form
                                                                                                                                    data - Filled form with data
                                                                                                                                  
                                                                                                                                  -->
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>ID Buku</h6>
                        <img src="{{ $buku->foto_url }}" alt="Foto Buku" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    <div class="col-8 align-self-center">
                        <h5>{{ $buku->judul }}</h5>
                        <p class="text-body-secondary">{{ $buku->sinopsis }}</p>
                    </div>
                </div>
                <div class="row border-top pt-3">
                    <div class="col-4">
                        <h6>Status</h6>
                        @if ($buku->status == 'tersedia')
                            <p><span class="badge text-bg-success">Tersedia</span></p>
                        @elseif ($buku->status == 'dipinjam')
                            <p><span class="badge text-bg-secondary">Dipinjam</span></p>
                        @elseif ($buku->status == 'proses')
                            <p><span class="badge text-bg-warning">Pengajuan</span></p>
                        @elseif ($buku->status == 'ditolak')
                            <p><span class="badge text-bg-danger">Pengajuan Ditolak</span></p>
                        @endif
                    </div>
                    <div class="col">
                        <h6>Stok</h6>
                        <p class="text-body-secondary">{{ $buku->jumlah }}</p>
                    </div>
                    <div class="col">
                        <h6>Penulis</h6>
                        <p class="text-body-secondary">{{ $buku->pengarang }}</p>
                    </div>
                    <div class="col">
                        <h6>Tahun Terbit</h6>
                            @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6>Kelas</h6>
                        <p class="mb-0">
                            @if($buku->kelas)
                                <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                    {{ $buku->kelas->nama }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-12">
                        <h6>Kategori</h6>
                        <p class="mb-0">
                            @if($buku->kategori)
                                <span class="badge text-white fw-normal text-bg-primary text-capitalize">
                                    {{ $buku->kategori->nama }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6>Jumlah Dilihat</h6>
                        <p class="mb-0">{{ $buku->view }}</p>
                    </div>


                    <div class="col-12">
                        <h6>Kata Kunci</h6>
                        <p class="mb-0 fst-italic">{{ $buku->keyword }}</p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <h6>Penulis</h6>
                        <p class="mb-0">{{ $buku->penulis }}</p>
                    </div>
                    {{-- <div class="col-12 col-sm-6">
                        <h6>Kontributor</h6>
                        <p class="mb-0">
                            @foreach ($buku->kontributor as $kontributor)
                                {{ $kontributor->user->nama ?? $kontributor->user_id }} ,
                            @endforeach
                        </p>
                    </div> --}}
                    {{-- <div class="col-12">
                        <h6>Jenis</h6>
                        <p class="mb-0 text-capitalize">{{ $buku->jenis->nama }}</p>
                    </div> --}}
                    <div class="col-12">
                        <h6>Penerbit</h6>
                        <p class="mb-0">{{ $buku->penerbit }}</p>
                    </div>

                    {{-- <div class="col-sm-6 col-12">
                        <h6>Program Studi</h6>
                        <p class="mb-0">{{ $buku->user->prodi->nama }}</p>
                    </div> --}}
                    <div class="col-12">
                        <h6>Berkas</h6><a class="icon-link" href="{{ $buku->file && $buku->file->file_name ? Storage::url($buku->file->file_name) : '#' }}" download
                            target="_blank">
                            <svg class="icon">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                            </svg>file.pdf</a>
                    </div>
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
