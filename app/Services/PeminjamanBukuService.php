<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\PeminjamanBuku;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanBukuService
{
    public function createPeminjamanByAdmin($userId, $bukuId, $tanggalKembali, $adminId, $tanggalPinjam = null)
    {
        return DB::transaction(function () use ($userId, $bukuId, $tanggalKembali, $adminId, $tanggalPinjam) {
            $buku = Buku::findOrFail($bukuId);

            // Check if book is available
            if (!$buku->isAvailable()) {
                throw new \Exception('Buku tidak tersedia untuk dipinjam');
            }

            // Check if user already has this book borrowed
            $existingBorrowing = PeminjamanBuku::where('user_id', $userId)
                ->where('buku_id', $bukuId)
                ->where('status', 'dipinjam')
                ->where('approval', 'approved')
                ->first();

            if ($existingBorrowing) {
                throw new \Exception('User sudah meminjam buku ini');
            }

            // Check if user still has unreturned books
            $unreturnedBook = PeminjamanBuku::with(['user', 'buku', 'denda'])
                ->where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->first();

            if ($unreturnedBook) {
                throw new \Exception($this->blockedMessage($unreturnedBook));
            }

            $pinjamDate = $tanggalPinjam ? Carbon::parse($tanggalPinjam) : Carbon::now();

            // Create peminjaman (langsung approved karena admin yang meminjamkan)
            $peminjaman = PeminjamanBuku::create([
                'user_id' => $userId,
                'buku_id' => $bukuId,
                'tanggal_pinjam' => $pinjamDate,
                'tanggal_kembali' => $tanggalKembali,
                'status' => 'dipinjam',
                'approval' => 'approved',
                'approval_by' => $adminId
            ]);

            // Update book status to dipinjam
            if ($buku->jumlah > 0) {
                $buku->decrement('jumlah', 1); // stok berkurang 1
                if ($buku->jumlah <= 0) {
                    $buku->update(['status' => 'habis']);
                }
            } else {
                throw new \Exception("Stok buku sudah habis!");
            }

            return $peminjaman;
        });
    }

    public function pinjamBuku($userId, $bukuId)
    {
        return DB::transaction(function () use ($userId, $bukuId) {
            $buku = Buku::findOrFail($bukuId);

            if (!$buku->isAvailable()) {
                throw new \Exception('Buku tidak tersedia untuk dipinjam');
            }

            $unreturnedBook = PeminjamanBuku::with(['user', 'buku', 'denda'])
                ->where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->where('approval', 'approved')
                ->first();

            if ($unreturnedBook) {
                throw new \Exception($this->blockedMessage($unreturnedBook, true));
            }

            $existingBorrowing = PeminjamanBuku::where('user_id', $userId)
                ->where('buku_id', $bukuId)
                ->where('status', 'dipinjam')
                ->first();

            if ($existingBorrowing) {
                if ($existingBorrowing->approval === 'pending') {
                    throw new \Exception('Anda sudah mengajukan peminjaman buku ini. Harap menunggu validasi dari admin.');
                }
                if ($existingBorrowing->approval === 'approved') {
                    throw new \Exception('Anda sedang meminjam buku ini.');
                }
            }

            $batasHari = (int) Pengaturan::getValue('batas_hari_pinjam', 3);

            $peminjaman = PeminjamanBuku::create([
                'user_id'       => $userId,
                'buku_id'       => $bukuId,
                'tanggal_pinjam'=> Carbon::now(),
                'tanggal_kembali'=> Carbon::now()->addDays($batasHari),
                'status'        => 'dipinjam',
                'approval'      => 'pending',
                'approval_by'   => null
            ]);

            return $peminjaman;
        });
    }

    public function approvePeminjaman($peminjamanId, $adminId)
    {
        return DB::transaction(function () use ($peminjamanId, $adminId) {
            $peminjaman = PeminjamanBuku::with('buku')->findOrFail($peminjamanId);

            if ($peminjaman->approval === 'approved') {
                throw new \Exception('Peminjaman ini sudah disetujui sebelumnya.');
            }

            if (!$peminjaman->buku->isAvailable()) {
                throw new \Exception('Stok buku habis atau tidak tersedia untuk dipinjam.');
            }

            $batasHari = (int) Pengaturan::getValue('batas_hari_pinjam', 3);

            $peminjaman->update([
                'approval' => 'approved',
                'approval_by' => $adminId,
                'tanggal_pinjam' => Carbon::now(),
                'tanggal_kembali' => Carbon::now()->addDays($batasHari),
            ]);

            if ($peminjaman->buku->jumlah > 0) {
                $peminjaman->buku->decrement('jumlah', 1);
                if ($peminjaman->buku->jumlah <= 0) {
                    $peminjaman->buku->update(['status' => 'habis']);
                }
            }

            return $peminjaman;
        });
    }

    public function rejectPeminjaman($peminjamanId, $adminId)
    {
        return DB::transaction(function () use ($peminjamanId, $adminId) {
            $peminjaman = PeminjamanBuku::with('buku')->findOrFail($peminjamanId);

            if ($peminjaman->approval === 'approved') {
                if ($peminjaman->status === 'dipinjam') {
                    $peminjaman->buku->increment('jumlah', 1);
                    if ($peminjaman->buku->status === 'habis') {
                        $peminjaman->buku->update(['status' => 'tersedia']);
                    }
                }
            }

            $peminjaman->update([
                'approval' => 'rejected',
                'approval_by' => $adminId,
            ]);

            return $peminjaman;
        });
    }

    public function returnBook($peminjamanId, $currentUserId)
    {
        return DB::transaction(function () use ($peminjamanId, $currentUserId) {
            $peminjaman = PeminjamanBuku::with(['user', 'buku'])->findOrFail($peminjamanId);

            // Check authorization (user can only return their own books or admin can return any)
            $isAdmin = auth()->user()->role === 'admin';
            if ($peminjaman->user_id !== $currentUserId && !$isAdmin) {
                throw new \Exception('Unauthorized');
            }

            if ($peminjaman->status !== 'dipinjam' || $peminjaman->approval !== 'approved') {
                throw new \Exception('Peminjaman tidak valid untuk dikembalikan');
            }

            // Check if user has unpaid fines
            $unpaidFines = Denda::whereHas('peminjaman', function ($q) use ($peminjaman) {
                $q->where('user_id', $peminjaman->user_id);
            })->where('status', 'unpaid')->get();

            if ($unpaidFines->count() > 0) {
                $totalDenda = $unpaidFines->sum('jumlah');
                $formattedTotal = 'Rp ' . number_format($totalDenda, 0, ',', '.');
                $namaUser = $peminjaman->user->nama ?? 'Siswa';

                $pesan = $isAdmin
                    ? "Buku tidak dapat dikembalikan. Siswa {$namaUser} masih memiliki denda belum dibayar sebesar {$formattedTotal}. Harap konfirmasi/lunasi denda terlebih dahulu di menu Denda."
                    : "Buku tidak dapat dikembalikan. Anda masih memiliki denda yang belum dibayar sebesar {$formattedTotal}. Silakan hubungi petugas perpustakaan untuk melunasi denda terlebih dahulu.";

                throw new \Exception($pesan);
            }

            // Update peminjaman status
            $peminjaman->update([
                'status' => 'dikembalikan'
            ]);

            // Update book status back to available
            $peminjaman->buku->update(['status' => 'tersedia']);
            $peminjaman->buku->increment('jumlah', 1);

            return $peminjaman;
        });
    }

    public function extendBorrowing($peminjamanId, $newReturnDate, $userId)
    {
        return DB::transaction(function () use ($peminjamanId, $newReturnDate, $userId) {
            $peminjaman = PeminjamanBuku::findOrFail($peminjamanId);

            if ($peminjaman->user_id !== $userId && auth()->user()->role !== 'admin') {
                throw new \Exception('Unauthorized');
            }

            if ($peminjaman->status !== 'dipinjam' || $peminjaman->approval !== 'approved') {
                throw new \Exception('Peminjaman tidak valid untuk diperpanjang');
            }

            $newReturnCarbon = Carbon::parse($newReturnDate);
            if ($newReturnCarbon->isPast()) {
                throw new \Exception('Tanggal pengembalian harus di masa depan');
            }

            if ($newReturnCarbon->lessThanOrEqualTo(Carbon::parse($peminjaman->tanggal_kembali))) {
                throw new \Exception('Tanggal perpanjangan harus lebih lama dari tanggal kembali saat ini');
            }

            $peminjaman->update([
                'tanggal_kembali' => $newReturnDate
            ]);

            return $peminjaman;
        });
    }

    private function blockedMessage(PeminjamanBuku $unreturned, bool $self = false): string
    {
        $judul = $unreturned->buku->judul;
        $jatuhTempo = $unreturned->tanggal_kembali?->format('d M Y');
        $selisih = $unreturned->getDaysRemaining();
        $statusWaktu = $unreturned->isOverdue()
            ? 'terlambat ' . abs($selisih) . ' hari'
            : 'masih ' . max($selisih, 0) . ' hari';
        $denda = $unreturned->denda->where('status', 'unpaid')->first();
        $nominal = $denda ? $denda->formatted_amount : null;

        $awal = $self
            ? 'Anda diblokir sistem karena masih memiliki buku yang belum dikembalikan'
            : "Siswa {$unreturned->user->nama} diblokir sistem karena masih memiliki buku yang belum dikembalikan";

        $pesan = "{$awal}: \"{$judul}\" (jatuh tempo {$jatuhTempo}, {$statusWaktu}).";
        $pesan .= $nominal
            ? " Denda yang belum dibayar: {$nominal}."
            : ' Denda dikenakan sesuai ketentuan perpustakaan.';
        $pesan .= $self
            ? ' Silakan kembalikan buku dan lunasi denda terlebih dahulu.'
            : ' Harap buku dikembalikan dan denda dilunasi terlebih dahulu.';

        return $pesan;
    }
}
