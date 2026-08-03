@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('admin.denda.index') }}">Denda</a>
            </li>
            <li class="breadcrumb-item active"><span>Tambah Denda</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">Tambah Denda</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.denda.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Peminjaman</label>
                        <select name="peminjaman_buku_id" class="form-select @error('peminjaman_buku_id') is-invalid @enderror" required>
                            <option value="">Pilih peminjaman</option>
                            @foreach ($peminjaman as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('peminjaman_buku_id') == $item->id ? 'selected' : '' }}>
                                    #{{ $item->id }} - {{ $item->user->nama }} / {{ $item->buku->judul }}
                                </option>
                            @endforeach
                        </select>
                        @error('peminjaman_buku_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Denda</label>
                        <input type="number" name="jumlah" step="0.01" min="0"
                            class="form-control @error('jumlah') is-invalid @enderror"
                            value="{{ old('jumlah', '0.00') }}" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar"
                            class="form-control @error('tanggal_bayar') is-invalid @enderror"
                            value="{{ old('tanggal_bayar') }}">
                        @error('tanggal_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.denda.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Denda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
