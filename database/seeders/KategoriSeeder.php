<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama' => 'Mapel',  'deskripsi' => 'Buku mata pelajaran sekolah'],
            ['nama' => 'Cerita', 'deskripsi' => 'Buku cerita dan dongeng'],
            ['nama' => 'Novel',  'deskripsi' => 'Buku fiksi bergenre novel'],
        ];

        foreach ($kategori as $item) {
            Kategori::firstOrCreate(['nama' => $item['nama']], $item);
        }

        // Hapus kategori selain 3 yang valid (pindahkan buku ke null terlebih dulu)
        $validNama = ['Mapel', 'Cerita', 'Novel'];
        $toDelete = Kategori::whereNotIn('nama', $validNama)->get();
        foreach ($toDelete as $kat) {
            \App\Models\Buku::where('kategori_id', $kat->id)->update(['kategori_id' => null]);
            $kat->delete();
        }
    }
}
