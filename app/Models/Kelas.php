<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function resolveOrCreateIdFromName($kelas): int
    {
        if (is_numeric($kelas)) {
            return (int) $kelas;
        }

        $nama = trim($kelas);
        if ($nama === '') {
            return 0;
        }

        $kelasModel = static::whereRaw('LOWER(nama) = ?', [mb_strtolower($nama)])->first();

        if ($kelasModel) {
            return $kelasModel->id;
        }

        return static::create(['nama' => $nama])->id;
    }
}
