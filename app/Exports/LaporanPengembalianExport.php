<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPengembalianExport implements FromCollection, WithHeadings, WithMapping
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
            'Terlambat (Hari)',
            'Denda',
            'Status',
        ];
    }

    public function map($row): array
    {
        // Hitung keterlambatan
        $terlambat = 0;
        $denda = 0;
        
        if ($row->tanggal_kembali && $row->tanggal_kembali_rencana) {
            $tanggalKembali = \Carbon\Carbon::parse($row->tanggal_kembali);
            $tanggalRencana = \Carbon\Carbon::parse($row->tanggal_kembali_rencana);
            
            if ($tanggalKembali->greaterThan($tanggalRencana)) {
                $terlambat = $tanggalKembali->diffInDays($tanggalRencana);
                $denda = $terlambat * 1000; // Denda Rp 1000 per hari
            }
        }

        return [
            $row->id,
            $row->user->nama ?? '-',
            $row->user->nim_nip ?? '-',
            $row->buku->judul ?? '-',
            $row->buku->pengarang ?? '-',
            $row->created_at->format('d/m/Y H:i'),
            $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y H:i') : '-',
            $terlambat > 0 ? $terlambat : '-',
            $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : '-',
            ucfirst($row->status),
        ];
    }
}