<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\BukuKontributor;
use App\Models\Denda;
use App\Models\FileBuku;
use App\Models\Jenis;
use App\Models\Kelas;
use App\Models\PeminjamanBuku;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Pengaturan dan Kategori
        $this->call(PengaturanSeeder::class);
        $this->call(KategoriSeeder::class);

        // 1. Seed Kelas
        $kelas7 = Kelas::firstOrCreate(['nama' => 'VII']);
        $kelas8 = Kelas::firstOrCreate(['nama' => 'VIII']);
        $kelas9 = Kelas::firstOrCreate(['nama' => 'IX']);

        // 2. Seed Users
        $admin = User::firstOrCreate(
            ['nim_nip' => 'admin'],
            [
                'nama' => 'Administrator Perpustakaan',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'kelas_id' => null,
            ]
        );

        $guru1 = User::firstOrCreate(
            ['nim_nip' => '197207151999031004'],
            [
                'nama' => 'Hasnah, S.Pd., M.Pd',
                'password' => bcrypt('password'),
                'role' => 'guru',
                'kelas_id' => $kelas7->id,
            ]
        );

        $guru2 = User::firstOrCreate(
            ['nim_nip' => '199312052019021004'],
            [
                'nama' => 'ADINDA PUTRI KARTIKA, S.Pd',
                'password' => bcrypt('password'),
                'role' => 'guru',
                'kelas_id' => $kelas8->id,
            ]
        );

        $siswa1 = User::firstOrCreate(
            ['nim_nip' => '3136650762'],
            [
                'nama' => 'Prasetya Utama',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'kelas_id' => $kelas7->id,
            ]
        );

        $siswa2 = User::firstOrCreate(
            ['nim_nip' => '3136650763'],
            [
                'nama' => 'Siti Nurhaliza',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'kelas_id' => $kelas8->id,
            ]
        );

        $siswa3 = User::firstOrCreate(
            ['nim_nip' => '3136650764'],
            [
                'nama' => 'Ahmad Rizky',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'kelas_id' => $kelas9->id,
            ]
        );

        // 3. Seed Jenis / Kategori Buku
        $jenisPdf = Jenis::firstOrCreate(['nama' => 'Buku PDF']);
        $jenisPaket = Jenis::firstOrCreate(['nama' => 'Buku Paket']);
        $jenisNonfiksi = Jenis::firstOrCreate(['nama' => 'Buku Nonfiksi']);
        $jenisReferensi = Jenis::firstOrCreate(['nama' => 'Buku Referensi']);
        $jenisNovel = Jenis::firstOrCreate(['nama' => 'Novel & Sastra']);
        $jenisSains = Jenis::firstOrCreate(['nama' => 'Sains & Teknologi']);

        // 4. Seed Buku
        $bukuData = [
            [
                'judul' => 'Matematika untuk SMP/MTs Kelas VII',
                'sinopsis' => 'Buku panduan pembelajaran matematika kurikulum merdeka untuk siswa kelas VII SMP.',
                'abstrak' => 'Membahas bab bilangan bulat, aljabar, persamaan linear, dan geometri dasar.',
                'pengarang' => 'Kemendikbudristek',
                'penerbit' => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit' => '2022',
                'jumlah' => 15,
                'view' => 45,
                'status' => 'tersedia',
                'kelas_id' => $kelas7->id,
                'jenis_id' => $jenisPaket->id,
                'file_name' => 'matematika_kelas_7.pdf',
            ],
            [
                'judul' => 'Ilmu Pengetahuan Alam (IPA) Kelas VIII',
                'sinopsis' => 'Buku pelajaran IPA terpadu membahas fisika dasar, biologi, dan kimia lingkungan.',
                'abstrak' => 'Topik mencakup sistem pencernaan, struktur tumbuhan, gelombang bunyi, dan cahaya.',
                'pengarang' => 'Dr. Bambang Setiaji',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => '2023',
                'jumlah' => 10,
                'view' => 32,
                'status' => 'tersedia',
                'kelas_id' => $kelas8->id,
                'jenis_id' => $jenisPaket->id,
                'file_name' => 'ipa_kelas_8.pdf',
            ],
            [
                'judul' => 'Bahasa Indonesia: Pengungkap Gagasan Kelas IX',
                'sinopsis' => 'Meningkatkan literasi membaca dan menulis kritis bagi siswa kelas IX.',
                'abstrak' => 'Panduan menyusun teks laporan percobaan, pidato persuasif, dan cerpen.',
                'pengarang' => 'Eko Suroso, M.Pd',
                'penerbit' => 'Yudhistira',
                'tahun_terbit' => '2021',
                'jumlah' => 12,
                'view' => 28,
                'status' => 'tersedia',
                'kelas_id' => $kelas9->id,
                'jenis_id' => $jenisPaket->id,
                'file_name' => 'bahasa_indonesia_9.pdf',
            ],
            [
                'judul' => 'Laskar Pelangi',
                'sinopsis' => 'Kisah perjuangan sepuluh anak di Belitung dalam mengejar mimpi dan pendidikan.',
                'abstrak' => 'Novel inspiratif karya Andrea Hirata tentang persahabatan dan semangat belajar.',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => '2005',
                'jumlah' => 5,
                'view' => 120,
                'status' => 'tersedia',
                'kelas_id' => null,
                'jenis_id' => $jenisNovel->id,
                'file_name' => 'laskar_pelangi.pdf',
            ],
            [
                'judul' => 'Ensiklopedia Sains Modern untuk Remaja',
                'sinopsis' => 'Pengetahuan populer seputar alam semesta, teknologi AI, dan tubuh manusia.',
                'abstrak' => 'Dilengkapi ilustrasi menarik dan penjelasan sains populer untuk pelajar.',
                'pengarang' => 'Prof. Rahmad Hidayat',
                'penerbit' => 'Gramedia Utama',
                'tahun_terbit' => '2024',
                'jumlah' => 8,
                'view' => 89,
                'status' => 'tersedia',
                'kelas_id' => null,
                'jenis_id' => $jenisSains->id,
                'file_name' => 'ensiklopedia_sains.pdf',
            ],
            [
                'judul' => 'Kamus Besar Bahasa Indonesia (KBBI) Edisi V',
                'sinopsis' => 'Kamus rujukan utama ragam bahasa Indonesia baku.',
                'abstrak' => 'Referensi kosakata terlengkap untuk keperluan akademis dan umum.',
                'pengarang' => 'Badan Pengembangan Bahasa',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => '2020',
                'jumlah' => 4,
                'view' => 64,
                'status' => 'tersedia',
                'kelas_id' => null,
                'jenis_id' => $jenisReferensi->id,
                'file_name' => 'kbbi_v.pdf',
            ],
            [
                'judul' => 'Sejarah Perkembangan Komputer dan Internet',
                'sinopsis' => 'Perjalanan inovasi teknologi dari mesin hitung mekanis hingga era komputasi awan.',
                'abstrak' => 'Buku edukasi nonfiksi sejarah sains dan teknologi informasi.',
                'pengarang' => 'Ir. Heru Susanto',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => '2022',
                'jumlah' => 6,
                'view' => 41,
                'status' => 'tersedia',
                'kelas_id' => null,
                'jenis_id' => $jenisNonfiksi->id,
                'file_name' => 'sejarah_komputer.pdf',
            ],
        ];

        foreach ($bukuData as $item) {
            $fileName = $item['file_name'];
            unset($item['file_name']);

            $buku = Buku::firstOrCreate(
                ['judul' => $item['judul']],
                $item
            );

            // Seed FileBuku
            FileBuku::firstOrCreate(
                ['buku_id' => $buku->id],
                [
                    'file_name' => $fileName,
                    'file_size' => '3.5 MB',
                    'file_type' => 'pdf',
                ]
            );

            // Seed BukuKontributor
            BukuKontributor::firstOrCreate([
                'user_id' => $guru1->id,
                'buku_id' => $buku->id,
            ]);
        }

        // 5. Seed PeminjamanBuku & Denda
        $buku1 = Buku::where('judul', 'like', '%Matematika%')->first();
        $buku2 = Buku::where('judul', 'like', '%IPA%')->first();
        $buku3 = Buku::where('judul', 'like', '%Laskar Pelangi%')->first();

        // Peminjaman 1: Aktif (dipinjam)
        $peminjaman1 = PeminjamanBuku::firstOrCreate(
            [
                'user_id' => $siswa1->id,
                'buku_id' => $buku1->id,
                'status' => 'dipinjam',
            ],
            [
                'tanggal_pinjam' => now()->subDays(3)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(4)->format('Y-m-d'),
                'approval' => 'approved',
                'approval_by' => $admin->id,
            ]
        );

        // Peminjaman 2: Terlambat / Ada Denda (unpaid)
        $peminjaman2 = PeminjamanBuku::firstOrCreate(
            [
                'user_id' => $siswa2->id,
                'buku_id' => $buku2->id,
                'status' => 'dipinjam',
            ],
            [
                'tanggal_pinjam' => now()->subDays(14)->format('Y-m-d'),
                'tanggal_kembali' => now()->subDays(7)->format('Y-m-d'),
                'approval' => 'approved',
                'approval_by' => $admin->id,
            ]
        );

        Denda::firstOrCreate(
            ['peminjaman_buku_id' => $peminjaman2->id],
            [
                'jumlah' => 14000.00,
                'status' => 'unpaid',
                'tanggal_bayar' => null,
                'keterangan' => 'Terlambat pengembalian 7 hari (Rp 2.000 / hari)',
            ]
        );

        // Peminjaman 3: Sudah Dikembalikan & Denda Lunas
        $peminjaman3 = PeminjamanBuku::firstOrCreate(
            [
                'user_id' => $siswa3->id,
                'buku_id' => $buku3->id,
                'status' => 'lunas',
            ],
            [
                'tanggal_pinjam' => now()->subDays(20)->format('Y-m-d'),
                'tanggal_kembali' => now()->subDays(10)->format('Y-m-d'),
                'approval' => 'approved',
                'approval_by' => $admin->id,
            ]
        );

        Denda::firstOrCreate(
            ['peminjaman_buku_id' => $peminjaman3->id],
            [
                'jumlah' => 6000.00,
                'status' => 'paid',
                'tanggal_bayar' => now()->subDays(2)->format('Y-m-d'),
                'keterangan' => 'Denda terlambat 3 hari telah dibayar',
            ]
        );
    }
}

