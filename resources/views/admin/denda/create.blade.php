@extends('layouts.authentication')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.denda.index') }}">Denda</a></li>
            <li class="breadcrumb-item active"><span>Tambah Denda</span></li>
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
                <form action="{{ route('admin.denda.store') }}" method="POST" id="form-denda">
                    @csrf

                    {{-- Jenis Denda --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Denda <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_denda" id="jenis_keterlambatan"
                                    value="keterlambatan" {{ old('jenis_denda', 'keterlambatan') == 'keterlambatan' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenis_keterlambatan">
                                    ⏰ Keterlambatan Pengembalian
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_denda" id="jenis_kehilangan"
                                    value="kehilangan" {{ old('jenis_denda') == 'kehilangan' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenis_kehilangan">
                                    📚 Kehilangan Buku
                                </label>
                            </div>
                        </div>
                        @error('jenis_denda')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Peminjaman --}}
                    <div class="mb-3">
                        <label class="form-label">Peminjaman <span class="text-danger">*</span></label>
                        <select name="peminjaman_buku_id" id="peminjaman_buku_id"
                            class="form-select @error('peminjaman_buku_id') is-invalid @enderror" required>
                            <option value="">Pilih peminjaman aktif</option>
                            @foreach ($peminjaman as $item)
                                <option value="{{ $item->id }}"
                                    data-tgl-kembali="{{ $item->tanggal_kembali?->format('Y-m-d') }}"
                                    {{ old('peminjaman_buku_id') == $item->id ? 'selected' : '' }}>
                                    #{{ $item->id }} — {{ $item->user->nama }} /
                                    {{ $item->buku->judul }}
                                    (Kembali: {{ $item->tanggal_kembali?->format('d/m/Y') ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('peminjaman_buku_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Info Kalkulasi (Keterlambatan) --}}
                    <div id="info-keterlambatan" class="alert alert-warning mb-3" style="display:none;">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Tanggal Kembali:</strong>
                                <span id="info-tgl-kembali">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Hari Terlambat:</strong>
                                <span id="info-hari-terlambat" class="text-danger fw-bold">0 hari</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Denda Terhitung:</strong>
                                <span id="info-nominal" class="text-danger fw-bold">Rp 0</span>
                            </div>
                        </div>
                        <div class="form-text mt-1">
                            Denda per hari: Rp {{ number_format($dendaPerHari, 0, ',', '.') }}.
                            Jumlah denda dihitung otomatis oleh sistem.
                        </div>
                    </div>

                    {{-- Jumlah (hanya untuk kehilangan) --}}
                    <div class="mb-3" id="field-jumlah" style="display:none;">
                        <label class="form-label">Jumlah Denda Kehilangan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="jumlah" step="1" min="0"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ old('jumlah', $dendaKehilangan) }}">
                        </div>
                        <div class="form-text">Default denda kehilangan: Rp {{ number_format($dendaKehilangan, 0, ',', '.') }}</div>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="paid"   {{ old('status') == 'paid'   ? 'selected' : '' }}>Sudah Dibayar</option>
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
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                            rows="3">{{ old('keterangan') }}</textarea>
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

@section('script')
<script>
    const dendaPerHari    = {{ $dendaPerHari }};
    const dendaKehilangan = {{ $dendaKehilangan }};

    function updateUI() {
        const jenis      = document.querySelector('input[name="jenis_denda"]:checked')?.value;
        const infoBox    = document.getElementById('info-keterlambatan');
        const fieldJml   = document.getElementById('field-jumlah');
        const peminjamanSelect = document.getElementById('peminjaman_buku_id');
        const selected   = peminjamanSelect.options[peminjamanSelect.selectedIndex];
        const tglKembali = selected?.dataset.tglKembali;

        if (jenis === 'keterlambatan') {
            infoBox.style.display = 'block';
            fieldJml.style.display = 'none';
            if (tglKembali) {
                const now  = new Date();
                const due  = new Date(tglKembali);
                const diff = Math.max(0, Math.ceil((now - due) / (1000 * 60 * 60 * 24)));
                document.getElementById('info-tgl-kembali').textContent    = selected.dataset.tglKembali;
                document.getElementById('info-hari-terlambat').textContent = diff + ' hari';
                document.getElementById('info-nominal').textContent        = 'Rp ' + (diff * dendaPerHari).toLocaleString('id-ID');
            }
        } else {
            infoBox.style.display = 'none';
            fieldJml.style.display = 'block';
        }
    }

    document.querySelectorAll('input[name="jenis_denda"]').forEach(r => r.addEventListener('change', updateUI));
    document.getElementById('peminjaman_buku_id').addEventListener('change', updateUI);
    updateUI();
</script>
@endsection
