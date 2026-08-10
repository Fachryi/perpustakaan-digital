@extends('guest')
@section('content')
    <div class="flex-grow-1 container mt-6">
        <section class="py-5" id="search">
            <div
                class="d-flex justify-content-sm-start justify-content-between align-items-sm-end align-items-start flex-column flex-sm-row gap-3">
                <div class="flex-grow-1">
                    <h1>Hasil Pencarian</h1>
                    <p class="mb-0">
                        Ditemukan {{ $bukus->total() }} hasil dari <strong>{{ request()->get('term') }}</strong>
                    </p>
                    <p>
                        {{-- <Waktu pencarian: <strong>{{ $requestTime }} ms</strong> --}}
                    </p>
                </div>
                <div id="sort-wrapper">
                    {{-- <div class="form-floating">
                        <select class="form-select" id="sort" aria-labelledby="sort-label">
                            <option value="">Relevance</option>
                            <option value="date,asc">Newest</option>
                            <option value="date,desc">Oldest</option>
                            <option value="title,asc">Title A-Z</option>
                            <option value="title,asc">Title Z-A</option>
                        </select><label id="sort-label" for="sort">Urutan</label>
                    </div> --}}
                </div>
            </div>
            <hr class="my-4" />
            <div class="row g-4">
                <div class="col-lg-3 col-md-4" id="filter">
                    <div class="accordion-item accordion border-0 rounded-0">
                        <h6 class="mb-0 accordion-button collapsed border rounded" data-bs-toggle="collapse"
                            data-bs-target="#filter-content" aria-expanded="false" aria-controls="filter-content">
                            Filter
                        </h6>
                    </div>
                    <form class="accordion-collapse collapse" id="filter-content" action="">
                        <div>
                            <p class="mb-2">Jenis</p>

                            <div class="filter-list">

                                @foreach ($jenis as $j)
                                    @php

                                        if (request('jenis')) {
                                            $checked = array_filter(request('jenis'), function ($jenis) use ($j) {
                                                return $jenis == $j->id;
                                            })
                                                ? 'checked'
                                                : '';
                                        } else {
                                            $checked = '';
                                        }

                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" id="{{ $j->nama }}" type="checkbox"
                                            value="{{ $j->id }}" name="jenis[]" {{ $checked }} /><label
                                            class="form-check-label" for="{{ $j->nama }}">{{ $j->nama }}<span
                                                class="text-body-tertiary">
                                                ({{ \App\Models\Buku::where('status', 'tersedia')->where('jenis_id', $j->id)->count() }})
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button
                                class="btn btn-link link-dark p-0 mt-2 d-flex align-items-center gap-2 d-none text-start"
                                type="button">
                                <span>Lihat lebih banyak</span><i class="bi-chevron-down"></i>
                            </button>
                        </div>
                        <div>
                            <p class="mb-2">Kelas</p>
                            <div class="filter-list">
                                @foreach ($kelass as $kelas)
                                    @php
                                        if (request('kelas')) {
                                            $checked2 = array_filter(request('kelas'), function ($data) use ($kelas) {
                                                return $data == $kelas->id;
                                            })
                                                ? 'checked'
                                                : '';
                                        } else {
                                            $checked2 = '';
                                        }

                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" id="{{ $kelas->id }}" type="checkbox"
                                            value="{{ $kelas->id }}" name="kelas[]" {{ $checked2 }} /><label
                                            class="form-check-label" for="{{ $kelas->id }}">{{ $kelas->nama }}<span
                                                class="text-body-tertiary">
                                                ({{ \App\Models\Buku::where('status', 'tersedia')->where('kelas_id', $kelas->id)->count() }})
                                            </span></label>
                                    </div>
                                @endforeach
                            </div>
                            <button
                                class="btn btn-link link-dark p-0 mt-2 d-flex align-items-center gap-2 d-none text-start"
                                type="button">
                                <span>Lihat lebih banyak</span><i class="bi-chevron-down"></i>
                            </button>
                        </div>

                        <button class="btn btn-info w-100" type="submit">
                            Filter
                        </button>
                    </form>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="vstack gap-4">
                        @foreach ($bukus as $buku)
                            <article class="literature">
                                <div class="mb-2 d-flex gap-2 align-items-center">

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
                                    <p class="mb-0">{{ $buku->penerbit }} |
                                        {{ $buku->tahun_terbit }}</p>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $buku->foto ? '/storage/'.$buku->foto : '/images/book-placeholder.png' }}" alt="{{ $buku->judul }}" class="rounded" style="width: 80px; height: 110px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <a href="/buku/{{ $buku->id }}">
                                            <h5>
                                                {{ $buku->judul }}
                                        {{-- JUMLAH RANKING RULES --}}
                                        {{-- @php
                                            $termExplode = explode(
                                                ' ',
                                                str()->lower(request('term') ?? '@@'),
                                            );
                                            // dd($termExplode);
                                            $angka = 0;
                                            foreach ($termExplode as $key => $value) {
                                                $subStrCount = substr_count(str()->lower($buku->judul), $value);
                                                $angka = $angka + $subStrCount;
                                            }

                                            // echo substr_count(str()->lower($literatur->judul), request('term'));

                                        @endphp
                                        @php
                                            $termExplode = explode(
                                                ' ',
                                                str()->lower(request('term') ?? '@@'),
                                            );
                                            // dd($termExplode);
                                            $angkaAbstrak = 0;
                                            foreach ($termExplode as $key => $value) {
                                                $subStrCount = substr_count(str()->lower($literatur->abstrak), $value);
                                                $angkaAbstrak = $angkaAbstrak + $subStrCount;
                                            }

                                            echo '=' . $angka + $angkaAbstrak;
                                        @endphp --}}
                                        {{-- END JUMLAH RANKING RULES --}}
                                            </h5>
                                        </a>
                                        <p class="mb-0">
                                            <span class="fw-bold">{{ $buku->nama ?? $buku->pengarang }}</span>,

                                            <i class="bi-info-circle ms-2" data-bs-toggle="tooltip" data-bs-html="true"
                                                data-bs-title="&lt;div class='text-start'&gt;
        			                            &lt;b&gt;Penulis: &lt;/b&gt;{{ $buku->user->nama ?? $buku->pengarang }}&lt;br/&gt;
        			                            &lt;/div&gt;"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="content collapse" id="{{ 'buku-content-' . $buku->id }}">
                                    <p class="my-2">

                                        {{ $buku->sinopsis }}

                                    </p>

                                    <p>
                                        Jumlah
                                        <span class="fst-italic">{{ $buku->jumlah }}</span>
                                    </p>
                                </div>
                                <button class="btn btn-link link-dark p-0 mt-2 d-flex align-items-center gap-2 text-start"
                                    type="button" data-bs-toggle="collapse" data-bs-target="{{ '#buku-content-' . $buku->id }}"
                                    aria-expanded="false" aria-controls="{{ 'buku-content-' . $buku->id }}">
                                    <span>Lihat lebih banyak</span><i class="bi-chevron-down"></i>
                                </button>
                            </article>
                        @endforeach


                    </div>


                    <nav class="mt-4 pagination justify-content-center" aria-label="Navigasi halaman buku">
                        {{ $bukus->appends(['term' => request('term') ?? '@@'])->links() }}
                    </nav>
                </div>
            </div>
        </section>
    </div>
@endsection
