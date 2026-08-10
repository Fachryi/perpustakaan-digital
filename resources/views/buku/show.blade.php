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
                    <div class="col-12">
                        <h6>Judul</h6>
                        <p class="mb-0">{{ $buku->judul }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Sinopsis</h6>
                        <p class="mb-0">{{ $buku->sinopsis }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Abstrak</h6>
                        <p class="mb-0">{{ $buku->abstrak ?? '-' }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Foto Buku</h6>
                        <img src="{{ $buku->foto ? '/storage/'.$buku->foto : '/images/book-placeholder.png' }}" alt="Foto Buku" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    <div class="col-12">
                        <h6>Jumlah</h6>
                        <p class="mb-0">{{ $buku->jumlah }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Pengarang</h6>
                        <p class="mb-0">{{ $buku->pengarang }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Penerbit</h6>
                        <p class="mb-0">{{ $buku->penerbit }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Tahun Terbit</h6>
                        <p class="mb-0">{{ $buku->tahun_terbit }}</p>
                    </div>
                    <div class="col-12">
                        <h6>Jenis Buku</h6>
                        <p class="mb-0">
                             @if ($buku->jenis_id == 1)
                                    <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id == 2)
                                    <span class="badge text-white fw-normal text-bg-success text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id == 3)
                                    <span class="badge text-white fw-normal text-bg-info text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                                @if ($buku->jenis_id > 3)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->jenis->nama }}
                                    </span>
                                @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6>Kelas</h6>
                        <p class="mb-0">
                             @if ($buku->kelas_id == 1)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->kelas->nama }}
                                    </span>
                                @endif
                                @if ($buku->kelas_id == 2)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->kelas->nama }}
                                    </span>
                                @endif
                                @if ($buku->kelas_id == 3)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->kelas->nama }}
                                    </span>
                                @endif
                                @if ($buku->kelas_id > 3)
                                    <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                                        {{ $buku->kelas->nama }}
                                    </span>
                                @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6>View</h6>
                        <p class="mb-0">{{ $buku->view_count }}</p>
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
                        <h6>Berkas</h6><a class="icon-link" href="/storage/{{ $buku->file->file_name ?? null }}" download
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
