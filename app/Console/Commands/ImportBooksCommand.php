<?php

namespace App\Console\Commands;

use App\Models\Buku;
use App\Models\FileBuku;
use App\Models\Jenis;
use App\Models\Kelas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportBooksCommand extends Command
{
    protected $signature = 'books:import {--source=} {--dry-run}';
    protected $description = 'Import buku PDF dari folder "fiksi dan nonfiksi" ke dalam database';

    /**
     * Mapping nama file → metadata buku
     */
    protected function bookMappings(): array
    {
        return [
            // ─── FIKSI ──────────────────────────────────────────────────────
            'SMP_Legenda Rawa Pening-Sigit-Fiks (1).pdf' => [
                'judul'       => 'Legenda Rawa Pening',
                'sinopsis'    => 'Cerita rakyat tentang legenda Rawa Pening dari Jawa Tengah.',
                'abstrak'     => 'Buku cerita rakyat Indonesia untuk jenjang SMP.',
                'pengarang'   => 'Sigit',
                'penerbit'    => 'Kemdikbud',
                'tahun_terbit'=> '2023',
                'jumlah'      => 5,
                'jenis'       => 'Novel & Sastra',
                'kelas'       => null,
            ],
            'SMP_Pak Abad, Pengobat Tradisional.pdf' => [
                'judul'       => 'Pak Abad, Pengobat Tradisional',
                'sinopsis'    => 'Kisah Pak Abad, seorang pengobat tradisional yang bijaksana di desanya.',
                'abstrak'     => 'Buku bacaan fiksi bertema kearifan lokal untuk siswa SMP.',
                'pengarang'   => 'Kemdikbud',
                'penerbit'    => 'Kemdikbud',
                'tahun_terbit'=> '2023',
                'jumlah'      => 5,
                'jenis'       => 'Novel & Sastra',
                'kelas'       => null,
            ],
            'SMP_Putri Nibung di Sarang Lamun.pdf' => [
                'judul'       => 'Putri Nibung di Sarang Lamun',
                'sinopsis'    => 'Cerita rakyat Kalimantan tentang Putri Nibung yang tinggal di sarang lamun.',
                'abstrak'     => 'Buku cerita rakyat Nusantara untuk siswa SMP.',
                'pengarang'   => 'Kemdikbud',
                'penerbit'    => 'Kemdikbud',
                'tahun_terbit'=> '2023',
                'jumlah'      => 5,
                'jenis'       => 'Novel & Sastra',
                'kelas'       => null,
            ],
            'SMP_Sai Ngugha Si Pemberani.pdf' => [
                'judul'       => 'Sai Ngugha Si Pemberani',
                'sinopsis'    => 'Kisah heroik Sai Ngugha, tokoh pemberani dari tradisi lisan Nusantara.',
                'abstrak'     => 'Bacaan fiksi bertema keberanian dan nilai budaya lokal untuk SMP.',
                'pengarang'   => 'Kemdikbud',
                'penerbit'    => 'Kemdikbud',
                'tahun_terbit'=> '2023',
                'jumlah'      => 5,
                'jenis'       => 'Novel & Sastra',
                'kelas'       => null,
            ],

            // ─── NON-FIKSI / BUKU PAKET ─────────────────────────────────────
            'Apa_Itu_Diksus.pdf' => [
                'judul'       => 'Apa Itu Diksus?',
                'sinopsis'    => 'Pengenalan tentang Pendidikan Khusus (Diksus) bagi siswa dan guru.',
                'abstrak'     => 'Buku referensi mengenai konsep dan praktik pendidikan khusus di Indonesia.',
                'pengarang'   => 'Kemdikbud',
                'penerbit'    => 'Kemdikbud',
                'tahun_terbit'=> '2023',
                'jumlah'      => 3,
                'jenis'       => 'Buku Referensi',
                'kelas'       => null,
            ],
            'BS_INFORMATIKA_VII.pdf' => [
                'judul'       => 'Informatika Kelas VII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa mata pelajaran Informatika Kurikulum Merdeka untuk kelas VII SMP.',
                'abstrak'     => 'Membahas literasi digital, berpikir komputasional, dan pengenalan perangkat TIK.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2021',
                'jumlah'      => 10,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VII',
            ],
            'Informatika_BS_KLS_VIII_Rev.pdf' => [
                'judul'       => 'Informatika Kelas VIII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Informatika Kurikulum Merdeka revisi untuk kelas VIII SMP.',
                'abstrak'     => 'Topik meliputi jaringan komputer, algoritma, dan pengolahan data.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 10,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VIII',
            ],
            'Informatika_BS_KLS_IX_Rev.pdf' => [
                'judul'       => 'Informatika Kelas IX (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Informatika Kurikulum Merdeka revisi untuk kelas IX SMP.',
                'abstrak'     => 'Pemrograman dasar, kecerdasan buatan, dan keamanan digital.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2023',
                'jumlah'      => 10,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'IX',
            ],
            'IPA_BS_KLS_VII_Rev (1).pdf' => [
                'judul'       => 'Ilmu Pengetahuan Alam Kelas VII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa IPA Kurikulum Merdeka revisi untuk kelas VII SMP.',
                'abstrak'     => 'Meliputi materi makhluk hidup, zat dan perubahannya, serta energi.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 12,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VII',
            ],
            'ilmu-pengetahuan-alam-untuk-smp-mts-kelas-vii-edisi-revisi.pdf' => [
                'judul'       => 'Ilmu Pengetahuan Alam untuk SMP/MTs Kelas VII (Edisi Revisi)',
                'sinopsis'    => 'Edisi revisi buku IPA terpadu Kurikulum 2013 untuk kelas VII SMP/MTs.',
                'abstrak'     => 'Mencakup topik klasifikasi makhluk hidup, fisika dasar, dan kimia sederhana.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 12,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VII',
            ],
            'Matematika-BS-KLS-VII.pdf' => [
                'judul'       => 'Matematika Kelas VII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Matematika Kurikulum Merdeka untuk kelas VII SMP.',
                'abstrak'     => 'Meliputi bilangan bulat, himpunan, aljabar, dan geometri dasar.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2021',
                'jumlah'      => 15,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VII',
            ],
            'Matematika-BS-KLS-VIII-Baru.pdf' => [
                'judul'       => 'Matematika Kelas VIII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Matematika Kurikulum Merdeka untuk kelas VIII SMP.',
                'abstrak'     => 'Membahas sistem persamaan linear, fungsi, teorema Pythagoras, dan statistika.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 15,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VIII',
            ],
            'Matematika_BS_KLS_IX.pdf' => [
                'judul'       => 'Matematika Kelas IX (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Matematika Kurikulum Merdeka untuk kelas IX SMP.',
                'abstrak'     => 'Topik meliputi bilangan berpangkat, transformasi, peluang, dan statistika lanjut.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2023',
                'jumlah'      => 15,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'IX',
            ],
            'PJOK_BS_KLS_VII.pdf' => [
                'judul'       => 'PJOK Kelas VII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Pendidikan Jasmani, Olahraga, dan Kesehatan untuk kelas VII SMP.',
                'abstrak'     => 'Meliputi gerak dasar olahraga, permainan, dan kesehatan remaja.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2021',
                'jumlah'      => 10,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VII',
            ],
            'PJOK_BS_KLS_VIII.pdf' => [
                'judul'       => 'PJOK Kelas VIII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa PJOK Kurikulum Merdeka untuk kelas VIII SMP.',
                'abstrak'     => 'Mencakup atletik, senam, aquatik, dan pola hidup sehat.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 10,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VIII',
            ],
            'Pendidikan-Pancasila-BS-KLS-VIII.pdf' => [
                'judul'       => 'Pendidikan Pancasila Kelas VIII (Buku Siswa)',
                'sinopsis'    => 'Buku siswa Pendidikan Pancasila Kurikulum Merdeka untuk kelas VIII SMP.',
                'abstrak'     => 'Membahas nilai-nilai Pancasila, norma hukum, dan kewarganegaraan aktif.',
                'pengarang'   => 'Kemendikbudristek',
                'penerbit'    => 'Pusat Kurikulum dan Perbukuan',
                'tahun_terbit'=> '2022',
                'jumlah'      => 12,
                'jenis'       => 'Buku Paket',
                'kelas'       => 'VIII',
            ],
        ];
    }

    public function handle(): int
    {
        $isDryRun  = $this->option('dry-run');
        $sourceDir = $this->option('source') ?: base_path('fiksi dan nonfiksi');

        if (! is_dir($sourceDir)) {
            $this->error("Folder tidak ditemukan: {$sourceDir}");
            return self::FAILURE;
        }

        if ($isDryRun) {
            $this->warn('=== DRY RUN MODE — tidak ada perubahan yang disimpan ===');
        }

        // Pastikan folder storage ada
        if (! $isDryRun) {
            Storage::disk('public')->makeDirectory('file');
        }

        $mappings  = $this->bookMappings();
        $files     = File::allFiles($sourceDir);
        $imported  = 0;
        $skipped   = 0;
        $missing   = 0;

        foreach ($files as $file) {
            if (strtolower($file->getExtension()) !== 'pdf') {
                continue;
            }

            $fileName = $file->getFilename();

            // Lewati file laporan
            if (str_contains(strtolower($fileName), 'laporan')) {
                $this->line("  <comment>SKIP</comment>  {$fileName} (laporan)");
                $skipped++;
                continue;
            }

            $meta = $mappings[$fileName] ?? null;
            if (! $meta) {
                $this->warn("  TIDAK ADA MAPPING: {$fileName}");
                $missing++;
                continue;
            }

            // Cek apakah judul sudah ada di DB
            if (Buku::where('judul', $meta['judul'])->exists()) {
                $this->line("  <comment>SKIP</comment>  {$meta['judul']} (sudah ada di database)");
                $skipped++;
                continue;
            }

            if ($isDryRun) {
                $this->info("  [DRY] Akan diimpor: {$meta['judul']}");
                $imported++;
                continue;
            }

            // Resolve jenis & kelas
            $jenis = $meta['jenis'] ? Jenis::where('nama', $meta['jenis'])->first() : null;
            $kelas = $meta['kelas'] ? Kelas::where('nama', $meta['kelas'])->first() : null;

            // Buat Buku
            $buku = Buku::create([
                'judul'        => $meta['judul'],
                'sinopsis'     => $meta['sinopsis'],
                'abstrak'      => $meta['abstrak'],
                'pengarang'    => $meta['pengarang'],
                'penerbit'     => $meta['penerbit'],
                'tahun_terbit' => $meta['tahun_terbit'],
                'jumlah'       => $meta['jumlah'],
                'jenis_id'     => $jenis?->id,
                'kelas_id'     => $kelas?->id,
                'status'       => 'tersedia',
                'view'         => 0,
            ]);

            // Salin file ke storage/app/public/file/
            $newFileName = $buku->id . '_' . $fileName;
            $filePath    = 'file/' . $newFileName;
            Storage::disk('public')->put($filePath, File::get($file));

            // Buat FileBuku
            FileBuku::create([
                'buku_id'   => $buku->id,
                'file_name' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => 'pdf',
            ]);

            $this->info("  <info>OK</info>  [{$meta['jenis']}] {$meta['judul']}");
            $imported++;
        }

        $this->newLine();
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['Berhasil diimpor', $imported],
                ['Dilewati (duplikat)', $skipped],
                ['Tidak ada mapping', $missing],
            ]
        );

        if ($missing > 0) {
            $this->warn('Ada file yang tidak memiliki mapping metadata. Periksa nama file.');
        }

        return self::SUCCESS;
    }
}
