<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama' => 'Buku Pelajaran', 'deskripsi' => 'Buku untuk keperluan pelajaran sekolah'],
            ['nama' => 'Novel',          'deskripsi' => 'Buku fiksi bergenre novel'],
            ['nama' => 'Agama',          'deskripsi' => 'Buku bernuansa keagamaan'],
            ['nama' => 'Umum',           'deskripsi' => 'Buku pengetahuan umum'],
            ['nama' => 'Referensi',      'deskripsi' => 'Kamus, ensiklopedia, dan sejenisnya'],
            ['nama' => 'Kamus',          'deskripsi' => 'Kamus bahasa dan istilah'],
        ];

        foreach ($kategori as $item) {
            Kategori::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
