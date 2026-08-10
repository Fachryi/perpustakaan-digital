@extends('guest')

@section('content')
    <div class="flex-grow-1 container mt-6">
        <section class="py-5" id="literature-detail">

            @if ($buku->jenis_id == 1)
                <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                    {{ $buku->jenis->nama }}
                </span>
            @endif
            @if ($buku->jenis_id == 2)
                <span class="badge text-white fw-normal text-bg-info text-capitalize">
                    {{ $buku->jenis->nama }}
                </span>
            @endif
            @if ($buku->jenis_id == 3)
                <span class="badge text-white fw-normal text-bg-success text-capitalize">
                    {{ $buku->jenis->nama }}
                </span>
            @endif
            @if ($buku->jenis_id > 3)
                <span class="badge text-white fw-normal text-bg-secondary text-capitalize">
                    {{ $buku->jenis->nama }}
                </span>
            @endif

            <h1 class="mt-2">
                {{ $buku->judul }}
            </h1>
            <p class="mb-0">
                <span class="fw-bold">{{ $buku->pengarang }}</span>
            </p>
            <div class="d-flex gap-4 mt-4">
                <div class="flex-shrink-0">
                    <img src="{{ $buku->foto ? Storage::url($buku->foto) : '/images/book-placeholder.png' }}" alt="{{ $buku->judul }}" class="rounded shadow-sm" style="max-width: 220px; max-height: 300px; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <div class="py-1 my-0 border-bottom d-flex align-items-center justify-content-between">
                        <div class="hstack gap-2 align-items-center">
                            <i class="bi-eye fs-5"></i>{{ $buku->view }}
                        </div>
                        <div class="hstack gap-1">
                            @if ($buku->fileBuku && !empty($buku->fileBuku->file_name))
                                <a href="{{ $buku->fileBuku->file_name ? Storage::url($buku->fileBuku->file_name) : '#' }}" download class="btn btn-icon link-dark"><i
                                    class="bi-download"></i></a>
                            @endif 
                            @if (!$buku->fileBuku || empty($buku->fileBuku->file_name))
                                <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                                    Buku Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="literature-detail-content">
                <div>
                    <h6>Jumlah:</h6>
                    <p>
                        <b>{{ $buku->jumlah }}</b>
                        @if ($buku->status == "tidak tersedia")
                            <span class="badge text-white fw-normal text-bg-danger text-capitalize">
                                {{ $buku->status }}
                            </span>
                        @endif
                        @if ($buku->status == "tersedia")
                            <span class="badge text-white fw-normal text-bg-success text-capitalize">
                                {{ $buku->status }}
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <h6>Sinopsis:</h6>
                    <p>
                        {{ $buku->sinopsis }}
                    </p>
                </div>
                <div>
                    <h6>Abstrak:</h6>
                    <p>
                        {{ $buku->abstrak ?? '-' }}
                    </p>
                </div>

                <div>
                    <h6 class="d-inline">Tahun Terbit:</h6>
                    <p class="d-inline">{{ $buku->tahun_terbit }}</p>
                </div>
                <div>
                    <h6 class="d-inline">Penerbit:</h6>
                    <p class="d-inline">{{ $buku->penerbit }}</p>
                </div>
                <div>
                    <h6 class="d-inline">Kelas:</h6>
                    <p class="d-inline">{{ $buku->kelas?->nama }}</p>
                </div>
                {{-- <div>
                <h6 class="d-inline">Staff yang Input/Edit:</h6>
                <p class="d-inline">Ethelyn Guilliland</p>
            </div> --}}
            </div>
        </section>
    </div>
@endsection
