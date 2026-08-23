@extends('guest')

@section('content')
    <div class="flex-grow-1 container mt-5 pt-4">
        @include('sweetalert::alert')
        
        <section class="py-4" id="literature-detail">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                @if ($buku->kode_buku)
                    <span class="badge bg-dark font-monospace px-3 py-2 fs-6">
                        {{ $buku->kode_buku }}
                    </span>
                @endif
                @if ($buku->kategori)
                    <span class="badge bg-primary px-3 py-2 fs-6">
                        {{ $buku->kategori->nama }}
                    </span>
                @endif
                @if ($buku->jenis)
                    <span class="badge text-white fw-normal text-bg-info text-capitalize px-3 py-2 fs-6">
                        {{ $buku->jenis->nama }}
                    </span>
                @endif
            </div>

            <h1 class="mt-2 fw-bold">
                {{ $buku->judul }}
            </h1>
            <p class="mb-3 text-muted fs-5">
                Pengarang: <span class="fw-bold text-dark">{{ $buku->pengarang ?? '-' }}</span>
            </p>

            <div class="row g-4 mt-2">
                <div class="col-md-4 col-lg-3">
                    <img src="{{ $buku->foto ? Storage::url($buku->foto) : '/images/book-placeholder.png' }}" alt="{{ $buku->judul }}" class="img-fluid rounded-3 shadow" style="width: 100%; max-height: 360px; object-fit: cover;">
                </div>
                <div class="col-md-8 col-lg-9 d-flex flex-column justify-content-between">
                    <div>
                        <div class="py-2 border-bottom d-flex align-items-center justify-content-between">
                            <div class="hstack gap-2 align-items-center text-muted">
                                <i class="bi-eye fs-5"></i> Dilihat {{ $buku->view }} kali
                            </div>
                            <div class="hstack gap-1">
                                @if ($buku->fileBuku && !empty($buku->fileBuku->file_name))
                                    <a href="{{ Storage::url($buku->fileBuku->file_name) }}" download class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1">
                                        <i class="bi-download"></i> Download Digital
                                    </a>
                                @endif 
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-6 col-sm-4">
                                <h6 class="text-muted mb-1 small">Stok Tersedia</h6>
                                <p class="mb-0 fs-5 fw-bold">
                                    {{ $buku->jumlah }} eksemplar
                                    @if ($buku->status == "habis" || $buku->jumlah <= 0)
                                        <span class="badge text-bg-danger text-capitalize ms-1">Habis</span>
                                    @elseif ($buku->status == "tersedia")
                                        <span class="badge text-bg-success text-capitalize ms-1">Tersedia</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-6 col-sm-4">
                                <h6 class="text-muted mb-1 small">Tahun Terbit</h6>
                                <p class="mb-0 fw-semibold">{{ $buku->tahun_terbit ?? '-' }}</p>
                            </div>
                            <div class="col-6 col-sm-4">
                                <h6 class="text-muted mb-1 small">Penerbit</h6>
                                <p class="mb-0 fw-semibold">{{ $buku->penerbit ?? '-' }}</p>
                            </div>
                            <div class="col-6 col-sm-4">
                                <h6 class="text-muted mb-1 small">Kelas Target</h6>
                                <p class="mb-0 fw-semibold">{{ $buku->kelas?->nama ?? 'Semua Kelas' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Section Peminjaman & Informasi Estimasi jika Stok Habis --}}
                    <div class="mt-4 pt-3 border-top">
                        @if($buku->jumlah > 0 && $buku->status === 'tersedia')
                            @auth
                                @if(auth()->user()->role === 'siswa')
                                    @if(isset($peminjamanSaya) && $peminjamanSaya)
                                        @if($peminjamanSaya->approval === 'pending')
                                            <div class="alert alert-warning d-flex align-items-center gap-3 p-3 rounded-3 shadow-sm" role="alert">
                                                <i class="bi-hourglass-split fs-2 text-warning flex-shrink-0"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Pengajuan Peminjaman Menunggu Validasi Admin</h6>
                                                    <p class="mb-0 small">Anda telah mengajukan peminjaman buku ini. Harap tunggu validasi dan persetujuan dari petugas perpustakaan.</p>
                                                </div>
                                            </div>
                                        @elseif($peminjamanSaya->approval === 'approved')
                                            <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 shadow-sm" role="alert">
                                                <i class="bi-check-circle-fill fs-2 text-success flex-shrink-0"></i>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Anda Sedang Meminjam Buku Ini</h6>
                                                    <p class="mb-0 small">Tanggal Pinjam: {{ $peminjamanSaya->tanggal_pinjam?->format('d/m/Y') }} &bull; Batas Pengembalian: <strong>{{ $peminjamanSaya->tanggal_kembali?->format('d F Y') }}</strong></p>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-semibold rounded-3 shadow d-inline-flex align-items-center gap-2">
                                                <i class="bi-book-half fs-5"></i> Pinjam Buku Ini
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @else
                                <a href="/" class="btn btn-primary btn-lg px-4 py-2 fw-semibold rounded-3 shadow d-inline-flex align-items-center gap-2">
                                    <i class="bi-box-arrow-in-right fs-5"></i> Masuk untuk Meminjam
                                </a>
                            @endauth
                        @else
                            {{-- Requirement 3: Info estimasi pengembalian jika stok habis --}}
                            <div class="alert alert-warning border-warning d-flex align-items-start gap-3 p-3 rounded-3 shadow-sm" role="alert">
                                <i class="bi-exclamation-triangle-fill fs-2 text-warning flex-shrink-0"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Stok Buku Saat Ini Habis</h6>
                                    @if(isset($estimasiKembali) && $estimasiKembali && $estimasiKembali->tanggal_kembali)
                                        @php
                                            $sisaHari = now()->diffInDays($estimasiKembali->tanggal_kembali, false);
                                        @endphp
                                        <p class="mb-0 text-dark small">
                                            Buku ini sedang dipinjam oleh siswa lain dan diperkirakan akan dikembalikan pada tanggal 
                                            <span class="badge bg-danger fs-6 px-2 py-1 mx-1">{{ $estimasiKembali->tanggal_kembali->format('d F Y') }}</span>
                                            @if($sisaHari > 0)
                                                ({{ $sisaHari }} hari lagi).
                                            @else
                                                (hari ini).
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-0 text-dark small">Buku ini sedang tidak tersedia untuk dipinjam saat ini. Silakan cek secara berkala.</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            @if($buku->abstrak)
            <div class="mt-4 pt-3 border-top">
                <h5 class="fw-bold">Abstrak</h5>
                <p class="text-muted">{{ $buku->abstrak }}</p>
            </div>
            @endif
        </section>
    </div>
@endsection
