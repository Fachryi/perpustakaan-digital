<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Literatur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Buku([
            'kelas_id' => $row['kelas'],
            'judul' => $row['judul'],
            'sinopsis' => $row['sinopsis'],
            'jumlah' => $row['jumlah'],
            'pengarang' => $row['pengarang'],
            'penerbit' => $row['penerbit'],
            'tahun_terbit' => $row['tahunterbit'],
            'jenis_id' => $row['jenis'],
            'status' => 'tersedia'
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
