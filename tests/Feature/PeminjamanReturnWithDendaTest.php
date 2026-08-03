<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\PeminjamanBuku;
use App\Models\User;
use App\Services\PeminjamanBukuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanReturnWithDendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_return_book_blocked_when_user_has_unpaid_fine(): void
    {
        $siswa = User::create([
            'nama' => 'Siswa Test',
            'nim_nip' => '123456',
            'password' => bcrypt('password'),
            'role' => 'siswa'
        ]);

        $buku1 = Buku::create([
            'judul' => 'Buku Pertama',
            'sinopsis' => 'Sinopsis 1',
            'jumlah' => 5,
            'pengarang' => 'Penulis 1',
            'penerbit' => 'Penerbit 1',
            'tahun_terbit' => '2023',
            'status' => 'tersedia'
        ]);

        $buku2 = Buku::create([
            'judul' => 'Buku Kedua',
            'sinopsis' => 'Sinopsis 2',
            'jumlah' => 3,
            'pengarang' => 'Penulis 2',
            'penerbit' => 'Penerbit 2',
            'tahun_terbit' => '2023',
            'status' => 'tersedia'
        ]);

        // Peminjaman 1
        $pinjam1 = PeminjamanBuku::create([
            'user_id' => $siswa->id,
            'buku_id' => $buku1->id,
            'tanggal_pinjam' => now()->subDays(10),
            'tanggal_kembali' => now()->subDays(3),
            'status' => 'dipinjam',
            'approval' => 'approved'
        ]);

        // Buat denda unpaid untuk peminjaman 1
        Denda::create([
            'peminjaman_buku_id' => $pinjam1->id,
            'jumlah' => 5000,
            'status' => 'unpaid'
        ]);

        // Peminjaman 2
        $pinjam2 = PeminjamanBuku::create([
            'user_id' => $siswa->id,
            'buku_id' => $buku2->id,
            'tanggal_pinjam' => now()->subDays(2),
            'tanggal_kembali' => now()->addDays(5),
            'status' => 'dipinjam',
            'approval' => 'approved'
        ]);

        $service = app(PeminjamanBukuService::class);

        // Ekspektasi: Pengembalian pinjam2 diblokir karena ada denda unpaid di pinjam1
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Buku tidak dapat dikembalikan');

        $this->actingAs($siswa);
        $service->returnBook($pinjam2->id, $siswa->id);
    }

    public function test_return_book_success_when_fines_are_paid(): void
    {
        $siswa = User::create([
            'nama' => 'Siswa Lunas',
            'nim_nip' => '654321',
            'password' => bcrypt('password'),
            'role' => 'siswa'
        ]);

        $buku = Buku::create([
            'judul' => 'Buku Lunas',
            'sinopsis' => 'Sinopsis',
            'jumlah' => 2,
            'pengarang' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => '2023',
            'status' => 'tersedia'
        ]);

        $pinjam = PeminjamanBuku::create([
            'user_id' => $siswa->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali' => now()->addDays(2),
            'status' => 'dipinjam',
            'approval' => 'approved'
        ]);

        $service = app(PeminjamanBukuService::class);
        $this->actingAs($siswa);

        $returned = $service->returnBook($pinjam->id, $siswa->id);

        $this->assertEquals('dikembalikan', $returned->fresh()->status);
    }
}
