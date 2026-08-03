<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\PeminjamanBuku;
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

            // Check if user has overdue books
            $overdueBooks = PeminjamanBuku::where('user_id', $userId)
                ->active()
                ->where('tanggal_kembali', '<', Carbon::now())
                ->count();

            if ($overdueBooks > 0) {
                throw new \Exception('User memiliki buku yang terlambat dikembalikan. Harap kembalikan terlebih dahulu.');
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

    public function returnBook($peminjamanId, $currentUserId)
    {
        return DB::transaction(function () use ($peminjamanId, $currentUserId) {
            $peminjaman = PeminjamanBuku::findOrFail($peminjamanId);

            // Check authorization (user can only return their own books or admin can return any)
            $isAdmin = auth()->user()->role === 'admin';
            if ($peminjaman->user_id !== $currentUserId && !$isAdmin) {
                throw new \Exception('Unauthorized');
            }

            if ($peminjaman->status !== 'dipinjam' || $peminjaman->approval !== 'approved') {
                throw new \Exception('Peminjaman tidak valid untuk dikembalikan');
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
}
