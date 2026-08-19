<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Generate kode_buku untuk semua buku yang belum punya kode.
     * Migration ini aman dijalankan berulang kali (idempotent).
     */
    public function up(): void
    {
        // Ambil semua buku yang belum punya kode, urut by id
        $buku = DB::table('buku')
            ->whereNull('kode_buku')
            ->orWhere('kode_buku', '')
            ->orderBy('id')
            ->get(['id']);

        if ($buku->isEmpty()) {
            return; // Sudah semua punya kode, skip
        }

        // Cari nomor terakhir yang sudah ada
        $lastKode = DB::table('buku')
            ->whereNotNull('kode_buku')
            ->where('kode_buku', '!=', '')
            ->orderByDesc('kode_buku')
            ->value('kode_buku');

        $counter = 1;
        if ($lastKode && preg_match('/^BK-(\d+)$/', $lastKode, $m)) {
            $counter = (int) $m[1] + 1;
        }

        foreach ($buku as $b) {
            DB::table('buku')->where('id', $b->id)->update([
                'kode_buku' => 'BK-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            ]);
            $counter++;
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback — kode buku adalah tambahan data
    }
};
