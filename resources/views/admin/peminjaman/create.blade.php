@extends('layouts.authentication')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pinjamkan Buku</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('peminjaman.store') }}">
                        @csrf


                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id"
                                required onchange="fillStudentDetails(this)">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}
                                        data-nis="{{ $user->nim_nip }}"
                                        data-nama="{{ $user->nama }}"
                                        data-kelas="{{ optional($user->kelas)->nama ?? '-' }}">
                                        {{ $user->nama }} ({{ $user->nim_nip }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih siswa yang akan meminjam buku.</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="nama_siswa" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama_siswa" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="kelas_siswa" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas_siswa" readonly>
                            </div>
                        </div>

                        <div id="user-info" class="alert alert-info d-none mb-3">
                            <h6>Informasi User:</h6>
                            <div id="user-details"></div>
                        </div>


                        <div class="mb-3">
                            <label for="buku_id" class="form-label">Pilih Buku <span class="text-danger">*</span></label>
                            <select class="form-select @error('buku_id') is-invalid @enderror" id="buku_id" name="buku_id"
                                required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($buku as $book)
                                    <option value="{{ $book->id }}" {{ old('buku_id') == $book->id ? 'selected' : '' }}
                                        data-penulis="{{ $book->pengarang }}" data-jenis="{{ $book->jenis->nama ?? '' }}">
                                        {{ $book->judul }} - {{ $book->pengarang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih buku yang akan dipinjamkan.</div>
                        </div>


                        <div id="book-info" class="alert alert-success d-none mb-3">
                            <h6>Informasi Buku:</h6>
                            <div id="book-details"></div>
                        </div>


                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                    id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih tanggal peminjaman (bisa tanggal lampau/manual).</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                    id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}" required>
                                @error('tanggal_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tentukan tanggal rencana pengembalian buku.</div>
                            </div>
                        </div>

                        <div id="duration-info" class="alert alert-warning d-none mb-3">
                            <i class="bi bi-clock"></i> <span id="duration-text"></span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Pinjamkan Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            window.fillStudentDetails = function(selectElement) {
                const userNameInput = document.getElementById('nama_siswa');
                const userClassInput = document.getElementById('kelas_siswa');
                const userInfo = document.getElementById('user-info');
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                if (selectElement.value && selectedOption) {
                    const nis = selectedOption.getAttribute('data-nis') || selectedOption.dataset.nis || '';
                    const nama = selectedOption.getAttribute('data-nama') || selectedOption.dataset.nama || selectedOption.textContent.split('(')[0].trim();
                    const kelas = selectedOption.getAttribute('data-kelas') || selectedOption.dataset.kelas || '-';

                    userNameInput.value = nama;
                    userClassInput.value = kelas;
                    document.getElementById('user-details').innerHTML = `
                <strong>NIS:</strong> ${nis}<br>
                <strong>Nama:</strong> ${nama}<br>
                <strong>Kelas:</strong> ${kelas}
            `;
                    userInfo.classList.remove('d-none');
                } else {
                    userNameInput.value = '';
                    userClassInput.value = '';
                    userInfo.classList.add('d-none');
                }
            };

            document.addEventListener('DOMContentLoaded', function() {
                const userSelect = document.getElementById('user_id');
                const bookSelect = document.getElementById('buku_id');
                const pinjamDateInput = document.getElementById('tanggal_pinjam');
                const returnDateInput = document.getElementById('tanggal_kembali');
                const bookInfo = document.getElementById('book-info');
                const durationInfo = document.getElementById('duration-info');

                // Initialize Select2 for searchable dropdowns
                if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                    $('#user_id').select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Ketik nama atau NIS siswa untuk mencari --',
                        allowClear: true,
                        width: '100%'
                    }).on('change select2:select select2:clear', function() {
                        window.fillStudentDetails(this);
                    });

                    $('#buku_id').select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Ketik judul atau pengarang buku untuk mencari --',
                        allowClear: true,
                        width: '100%'
                    }).on('change select2:select select2:clear', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (this.value && selectedOption) {
                            const penulis = selectedOption.dataset.penulis || '';
                            const jenis = selectedOption.dataset.jenis || '';
                            document.getElementById('book-details').innerHTML = `
                                <strong>Judul:</strong> ${selectedOption.text.split(' - ')[0]}<br>
                                <strong>Penulis:</strong> ${penulis}<br>
                                <strong>Jenis:</strong> ${jenis}
                            `;
                            bookInfo.classList.remove('d-none');
                        } else {
                            bookInfo.classList.add('d-none');
                        }
                    });
                } else {
                    if (userSelect) {
                        userSelect.addEventListener('change', function() {
                            window.fillStudentDetails(this);
                        });
                    }

                    if (bookSelect) {
                        bookSelect.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            if (this.value) {
                                const penulis = selectedOption.dataset.penulis || '';
                                const jenis = selectedOption.dataset.jenis || '';
                                document.getElementById('book-details').innerHTML = `
                                    <strong>Judul:</strong> ${selectedOption.text.split(' - ')[0]}<br>
                                    <strong>Penulis:</strong> ${penulis}<br>
                                    <strong>Jenis:</strong> ${jenis}
                                `;
                                bookInfo.classList.remove('d-none');
                            } else {
                                bookInfo.classList.add('d-none');
                            }
                        });
                    }
                }

                if (userSelect && userSelect.value) {
                    window.fillStudentDetails(userSelect);
                }

                function calculateDuration() {
                    if (pinjamDateInput.value && returnDateInput.value) {
                        const pinjamDate = new Date(pinjamDateInput.value);
                        const returnDate = new Date(returnDateInput.value);
                        const diffTime = returnDate - pinjamDate;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays >= 0) {
                            document.getElementById('duration-text').textContent =
                                `Durasi peminjaman: ${diffDays} hari`;
                            durationInfo.classList.remove('d-none');
                        } else {
                            document.getElementById('duration-text').textContent =
                                `Tanggal kembali tidak boleh sebelum tanggal pinjam`;
                            durationInfo.classList.remove('d-none');
                        }
                    } else {
                        durationInfo.classList.add('d-none');
                    }
                }

                pinjamDateInput.addEventListener('change', function() {
                    returnDateInput.min = this.value;
                    calculateDuration();
                });

                returnDateInput.addEventListener('change', calculateDuration);

                // Set default return date (7 days from pinjam date) if empty
                if (!returnDateInput.value && pinjamDateInput.value) {
                    const defaultDate = new Date(pinjamDateInput.value);
                    defaultDate.setDate(defaultDate.getDate() + 7);
                    returnDateInput.value = defaultDate.toISOString().split('T')[0];
                }
                returnDateInput.min = pinjamDateInput.value;
                calculateDuration();

                function loadUserStats(userId) {
                    fetch(`/api/users/${userId}/stats`)
                        .then(response => response.json())
                        .then(data => {})
                        .catch(error => console.log('Stats loading failed'));
                }
            });
        </script>
    @endpush
@endsection
