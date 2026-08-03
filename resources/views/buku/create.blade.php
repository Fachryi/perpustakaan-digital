@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/dashboard/buku">Buku</a>
            </li>
            <li class="breadcrumb-item active"><span>Tambah</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Tambah Buku</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif
            <form action="/dashboard/buku" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="judul">Judul</label>
                        <input class="form-control" id="judul"
                            placeholder="Judul Buku" name="judul">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="sinopsis">Sinopsis</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" rows="4"
                            placeholder="Sinopsis"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="abstrak">Abstrak</label>
                        <textarea class="form-control" id="abstrak" name="abstrak" rows="4"
                            placeholder="Abstrak buku"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="foto">Foto Buku</label>
                        <input class="form-control" id="foto" name="foto" type="file" accept="image/*">
                        <div class="form-text">Format: JPG, JPEG, PNG, WebP. Maks 2MB.</div>
                        <div id="foto-preview" class="mt-2"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="jumlah">Jumlah</label>
                        <input class="form-control" id="jumlah" name="jumlah"
                            placeholder="jumlah">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="pengarang">Pengarang</label>
                        <input class="form-control" id="pengarang" name="pengarang"
                            placeholder="Pengarang">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="penerbit">Penerbit</label>
                        <input class="form-control" id="penerbit" name="penerbit"
                            placeholder="Penerbit">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="tahun_terbit">Tahun Terbit</label>
                        <input class="form-control" id="tahun_terbit" name="tahun_terbit"
                            placeholder="2020">
                    </div>
                    @if (auth()->user()->role !== 'siswa')
                        <div class="col-12">
                            <label class="form-label" id="jenis-label" for="jenis">Jenis Koleksi</label>
                            <select class="form-select" id="jenis" name="jenis_koleksi" aria-labelledby="jenis-label">
                                @foreach ($daftarJenis as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                     @if (auth()->user()->role !== 'siswa')
                        <div class="col-12">
                            <label class="form-label" id="kelas-label" for="kelas">Kelas</label>
                            <select class="form-select" id="kelas" name="kelas" aria-labelledby="kelas-label">
                                @foreach ($daftarKelas as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- <div class="col-sm-6 col-12">
                        <label class="form-label" id="fakultas-label" for="fakultas">Fakultas</label>
                        <select class="form-select" id="fakultas" name="fakultas" aria-labelledby="fakultas-label">
                            <option value="">Pilih Fakultas</option>
                            @foreach ($daftarFakultas as $fakultas)
                                <option value="{{ $fakultas }}">{{ $fakultas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-12">
                        <label class="form-label" id="program-studi-label" for="program-studi">Program Studi</label>
                        <select class="form-select" id="program-studi" name="program_studi"
                            aria-labelledby="program-studi-label">

                        </select>
                    </div> --}}
                    <div class="col-12">
                        <label class="form-label" for="file">Berkas</label>
                        <input class="form-control" id="file" name="file" type="file" accept="application/pdf"
                            value="test">
                        <div class="form-text">
                            File yang telah terunggah:
                            <a target="_blank"></a>
                        </div>
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

@section('script')
    <script>
        $(document).ready(function() {
            $('#foto').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#foto-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 150px;" />');
                    }
                    reader.readAsDataURL(file);
                }
            });
        })
    </script>
@endsection
