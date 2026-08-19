<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'       => 'batas_hari_pinjam',
                'value'     => '3',
                'deskripsi' => 'Batas maksimal hari peminjaman buku (hari)',
            ],
            [
                'key'       => 'denda_per_hari',
                'value'     => '1000',
                'deskripsi' => 'Denda keterlambatan pengembalian per hari (Rupiah)',
            ],
            [
                'key'       => 'denda_kehilangan_default',
                'value'     => '50000',
                'deskripsi' => 'Nominal denda kehilangan buku default (Rupiah)',
            ],
        ];

        foreach ($settings as $item) {
            Pengaturan::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
