@extends('layouts.authentication')


@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Peminjaman #{{ $peminjaman->id }}</h4>
                <span class="badge bg-{{ $peminjaman->status === 'dipinjam' ? 'warning' : 'success' }} fs-6">
                    {{ ucfirst($peminjaman->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                     User Information 
                    <div class="col-md-6">
                        <h5>Informasi Peminjam</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $peminjaman->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $peminjaman->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $peminjaman->user->role_name }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                     Book Information 
                    <div class="col-md-6">
                        <h5>Informasi Buku</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Judul:</strong></td>
                                <td>{{ $peminjaman->buku->judul }}</td>
                            </tr>
                            <tr>
                                <td><strong>Penulis:</strong></td>
                                <td>{{ $peminjaman->buku->penulis }}</td>
                            </tr>
                            <tr>
                                <td><strong>Penerbit:</strong></td>
                                <td>{{ $peminjaman->buku->penerbit ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis:</strong></td>
                                <td>{{ $peminjaman->buku->jenis->nama ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                 Borrowing Details 
                <div class="row">
                    <div class="col-md-6">
                        <h5>Detail Peminjaman</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tanggal Pinjam:</strong></td>
                                <td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kembali:</strong></td>
                                <td>
                                    {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}
                                    @if($peminjaman->isOverdue())
                                        <br><span class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Terlambat {{ abs($peminjaman->getDaysRemaining()) }} hari
                                        </span>
                                    @elseif($peminjaman->status === 'dipinjam' && $peminjaman->tanggal_kembali)
                                        <br><span class="text-info">
                                            {{ $peminjaman->getDaysRemaining() }} hari lagi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $peminjaman->status === 'dipinjam' ? 'warning' : 'success' }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Approval:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $peminjaman->approval === 'approved' ? 'success' : ($peminjaman->approval === 'rejected' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($peminjaman->approval) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5>Informasi Approval</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Disetujui oleh:</strong></td>
                                <td>{{ $peminjaman->approver->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td>{{ $peminjaman->created_at->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $peminjaman->updated_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                 Action Buttons 
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    
                    <div>
                        @if($peminjaman->status === 'dipinjam' && $peminjaman->approval === 'approved')
                            <form method="POST" action="{{ route('peminjaman.return', $peminjaman->id) }}" 
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Yakin ingin mengembalikan buku ini?')">
                                    <i class="bi bi-arrow-return-left"></i> Kembalikan Buku
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('buku.show', $peminjaman->buku->id) }}" class="btn btn-info">
                            <i class="bi bi-book"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
