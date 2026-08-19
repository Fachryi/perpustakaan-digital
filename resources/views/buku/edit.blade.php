@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="/dashboard/buku">buku</a>
            </li>
            <li class="breadcrumb-item active"><span>Edit</span>
            </li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4 mb-4">
            <h1 class="mb-4">Edit buku</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif
            <form action="/dashboard/buku/{{ $buku->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="kode_buku">ID Buku</label>
                        <input class="form-control bg-light" id="kode_buku" name="kode_buku"
                            value="{{ $buku->kode_buku ?? 'Belum digenerate' }}" readonly>
                        <div class="form-text">ID buku digenerate otomatis oleh sistem.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <input class="form-control" id="judul" value="{{ $buku->judul }}"
                            placeholder="Judul Buku" name="judul">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="abstrak">Abstrak</label>
                        <textarea class="form-control" id="abstrak" name="abstrak" rows="4"
                            placeholder="Abstrak buku">{{ $buku->abstrak }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="foto">Foto Buku</label>
                        <input class="form-control" id="foto" name="foto" type="file" accept="image/*">
                        <div class="form-text">Format: JPG, JPEG, PNG, WebP. Maks 2MB.</div>
                        @if ($buku->foto)
                            <div class="mt-2">
                                <p class="mb-1">Foto saat ini:</p>
                                <img src="{{ $buku->foto ? Storage::url($buku->foto) : '/images/book-placeholder.png' }}" alt="Foto Buku" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <div id="foto-preview" class="mt-2"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="jumlah">Jumlah</label>
                        <input class="form-control" id="jumlah" name="jumlah"
                            placeholder="jumlah" value="{{ $buku->jumlah }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="pengarang">Pengarang</label>
                        <input class="form-control" id="pengarang" name="pengarang"
                            placeholder="Pengarang" value="{{ $buku->pengarang }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="penerbit">Penerbit</label>
                        <input class="form-control" id="penerbit" name="penerbit"
                            placeholder="Penerbit" value="{{ $buku->penerbit }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="tahun_terbit">Tahun Terbit</label>
                        <input class="form-control" id="tahun_terbit" name="tahun_terbit"
                            placeholder="2020" value="{{ $buku->tahun_terbit }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" id="jenis-label" for="jenis">Jenis Koleksi</label>
                        <select class="form-select" id="jenis" name="jenis_koleksi" aria-labelledby="jenis-label">
                            @foreach ($daftarJenis as $jenis)
                                <option @selected($jenis->id == $buku->jenis_id) value="{{ $jenis->id }}">{{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" id="kelas-label" for="kelas">Kelas</label>
                        <select class="form-select" id="kelas" name="kelas" aria-labelledby="kelas-label">
                            @foreach ($daftarKelas as $kelas)
                                <option @selected($kelas->id == $buku->kelas_id) value="{{ $kelas->id }}">{{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="kategori_id">Kategori Buku</label>
                        <select class="form-select" id="kategori_id" name="kategori_id">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($daftarKategori as $kat)
                                <option value="{{ $kat->id }}" @selected($kat->id == $buku->kategori_id)>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label" for="file">Berkas</label>
                        <input class="form-control" id="file" name="file" type="file" accept="application/pdf">
                        @if ($buku->file)
                            <div class="form-text">
                                File yang telah terunggah:
                                <a target="_blank" href="{{ $buku->file && $buku->file->file_name ? Storage::url($buku->file->file_name) : '#' }}">{{ basename($buku->file->file_name ?? '') }}</a>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
                    <button class="btn btn-outline-secondary" type="button" onclick="history.back()">Kembali</button>
                    <button class="btn btn-primary" type="submit">Edit</button>
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
