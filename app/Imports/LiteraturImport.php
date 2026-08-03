<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Literatur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LiteraturImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Buku([
            'judul' => $row['judul'],
            'penulis_kontributor' => $row['penulis'],
            'abstrak' => $row['abstrak'],
            'penerbit' => $row['published'],
            'jenis_id' => 4,
            'doi' => $row['doi'],
            'status' => 'diterima'
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
