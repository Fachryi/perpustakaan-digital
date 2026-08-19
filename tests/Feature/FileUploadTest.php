<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Jenis;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_pdf_and_foto_buku(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'nama' => 'Admin Test',
            'nim_nip' => '999888',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $jenis = Jenis::create(['nama' => 'Pelajaran']);
        $kelas = Kelas::create(['nama' => 'VII A']);

        $pdf = UploadedFile::fake()->create('buku_test.pdf', 100, 'application/pdf');
        $foto = UploadedFile::fake()->image('cover_test.jpg', 400, 600);

        $response = $this->actingAs($admin)->post('/dashboard/buku', [
            'judul' => 'Buku Uji Coba Upload',
            'sinopsis' => 'Sinopsis uji coba',
            'jumlah' => 10,
            'pengarang' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'tahun_terbit' => '2024',
            'jenis_koleksi' => $jenis->id,
            'kelas' => $kelas->id,
            'file' => $pdf,
            'foto' => $foto,
        ]);

        $response->assertRedirect('/dashboard/buku');

        $buku = Buku::where('judul', 'Buku Uji Coba Upload')->first();
        $this->assertNotNull($buku);
        $this->assertNotNull($buku->foto);

        Storage::disk('public')->assertExists($buku->foto);
        $this->assertNotNull($buku->fileBuku);
        Storage::disk('public')->assertExists($buku->fileBuku->file_name);
    }
}
