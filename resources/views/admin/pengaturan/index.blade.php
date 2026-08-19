@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active"><span>Pengaturan Sistem</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-4 mb-4">
        <h1 class="mb-4">Pengaturan Sistem</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Pengaturan Peminjaman --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <svg class="icon text-primary"><use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-book"></use></svg>
                    <h5 class="card-title mb-0">Pengaturan Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="batas_hari_pinjam">
                                Batas Maksimal Hari Pinjam
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('batas_hari_pinjam') is-invalid @enderror"
                                    id="batas_hari_pinjam" name="batas_hari_pinjam" min="1" max="365"
                                    value="{{ old('batas_hari_pinjam', $pengaturan['batas_hari_pinjam']->value ?? 3) }}"
                                    required>
                                <span class="input-group-text">hari</span>
                                @error('batas_hari_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Batas hari yang diperbolehkan untuk meminjam buku. Default: 3 hari.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengaturan Denda --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <svg class="icon text-warning"><use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-money"></use></svg>
                    <h5 class="card-title mb-0">Pengaturan Denda</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="denda_per_hari">
                                Denda Keterlambatan per Hari
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('denda_per_hari') is-invalid @enderror"
                                    id="denda_per_hari" name="denda_per_hari" min="0"
                                    value="{{ old('denda_per_hari', $pengaturan['denda_per_hari']->value ?? 1000) }}"
                                    required>
                                <span class="input-group-text">/ hari</span>
                                @error('denda_per_hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Nominal denda untuk setiap hari keterlambatan pengembalian buku.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="denda_kehilangan_default">
                                Denda Kehilangan Buku (Default)
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('denda_kehilangan_default') is-invalid @enderror"
                                    id="denda_kehilangan_default" name="denda_kehilangan_default" min="0"
                                    value="{{ old('denda_kehilangan_default', $pengaturan['denda_kehilangan_default']->value ?? 50000) }}"
                                    required>
                                @error('denda_kehilangan_default')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Nominal denda default jika siswa menghilangkan buku. Bisa diubah saat mencatat denda.</div>
                        </div>
                    </div>

                    {{-- Preview Kalkulasi --}}
                    <div class="alert alert-info mt-3 mb-0" id="preview-denda">
                        <strong>Preview:</strong>
                        Jika terlambat <span id="preview-hari">3</span> hari →
                        Denda = <span id="preview-nominal">Rp 3.000</span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg class="icon me-1"><use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-save"></use></svg>
                    Simpan Pengaturan
                </button>
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
<script>
    function updatePreview() {
        const dendaPerHari = parseInt(document.getElementById('denda_per_hari').value) || 0;
        const batasHari    = parseInt(document.getElementById('batas_hari_pinjam').value) || 3;
        const contohHari   = batasHari + 2; // contoh: 2 hari melebihi batas
        const total        = contohHari * dendaPerHari;
        document.getElementById('preview-hari').textContent    = contohHari;
        document.getElementById('preview-nominal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('denda_per_hari').addEventListener('input', updatePreview);
    document.getElementById('batas_hari_pinjam').addEventListener('input', updatePreview);
    updatePreview();
</script>
@endsection
