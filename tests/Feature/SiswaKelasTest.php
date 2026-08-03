<?php

namespace Tests\Feature;

use App\Models\Kelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaKelasTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_existing_class_names_and_creates_missing_ones(): void
    {
        Kelas::create(['nama' => 'VII a']);

        $existingId = Kelas::resolveOrCreateIdFromName('VII a');
        $this->assertEquals(1, $existingId);

        $createdId = Kelas::resolveOrCreateIdFromName('VIII c');

        $this->assertDatabaseHas('kelas', ['nama' => 'VIII c']);
        $this->assertGreaterThan(0, $createdId);
    }
}
