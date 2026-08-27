<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unique index jika ada
        try {
            Schema::table('buku', function (Blueprint $table) {
                $table->dropUnique('buku_kode_buku_unique');
            });
        } catch (\Exception $e) {
            // Index mungkin tidak ada, abaikan
        }

        // Update kode_buku semua buku berdasarkan kategori
        $kategori = DB::table('kategori')->get();
        $prefix = ['Mapel' => '001', 'Cerita' => '002', 'Novel' => '003'];

        foreach ($kategori as $kat) {
            $kode = $prefix[$kat->nama] ?? '001';
            DB::table('buku')->where('kategori_id', $kat->id)->update(['kode_buku' => $kode]);
        }

        // Buku tanpa kategori → default 001
        DB::table('buku')->whereNull('kategori_id')->update(['kode_buku' => '001']);
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->unique('kode_buku');
        });
    }
};
