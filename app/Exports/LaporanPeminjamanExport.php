<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $tanggalAwal;
    protected $tanggalAkhir;

    public function __construct($data, $tanggalAwal, $tanggalAkhir)
    {
        $this->data = $data;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIS',
            'Judul Buku',
            'Pengarang',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user->nama ?? '-',
            $row->user->nim_nip ?? '-',
            $row->buku->judul ?? '-',
            $row->buku->pengarang ?? '-',
            $row->created_at->format('d/m/Y H:i'),
            $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y H:i') : '-',
            ucfirst($row->status),
            $row->keterangan ?? '-',
        ];
    }
}