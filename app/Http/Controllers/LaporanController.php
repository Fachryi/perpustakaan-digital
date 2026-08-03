<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanPeminjamanExport;
use App\Exports\LaporanPengembalianExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Statistik umum
        $totalBuku = Buku::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalPeminjaman = PeminjamanBuku::count();
        $totalDenda = Denda::sum('jumlah') ?? 0;
        $dendaTerbayar = Denda::where('status', 'paid')->sum('jumlah') ?? 0;
        $dendaBelumBayar = Denda::where('status', 'unpaid')->sum('jumlah') ?? 0;

        // Peminjaman per status
        $peminjamanAktif = PeminjamanBuku::where('status', 'dipinjam')->where('approval', 'approved')->count();
        $peminjamanKembali = PeminjamanBuku::where('status', 'dikembalikan')->count();
        $peminjamanTerlambat = PeminjamanBuku::where('status', 'dipinjam')
            ->where('approval', 'approved')
            ->where('tanggal_kembali', '<', now())
            ->count();

        // Laporan detail peminjaman
        $laporanPeminjaman = PeminjamanBuku::with(['user', 'buku'])
            ->orderByDesc('created_at')
            ->get();

        // Laporan detail denda
        $laporanDenda = Denda::with(['peminjaman.user', 'peminjaman.buku'])
            ->orderByDesc('created_at')
            ->get();

        // Filter berdasarkan periode jika ada
        if ($request->bulan && $request->tahun) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            $laporanPeminjaman = PeminjamanBuku::with(['user', 'buku'])
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->orderByDesc('created_at')
                ->get();

            $laporanDenda = Denda::with(['peminjaman.user', 'peminjaman.buku'])
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('admin.laporan.index', compact(
            'totalBuku',
            'totalSiswa',
            'totalPeminjaman',
            'totalDenda',
            'dendaTerbayar',
            'dendaBelumBayar',
            'peminjamanAktif',
            'peminjamanKembali',
            'peminjamanTerlambat',
            'laporanPeminjaman',
            'laporanDenda'
        ));
    }

    public function download(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:peminjaman,pengembalian,denda,terlambat',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'format' => 'required|in:pdf,excel',
        ]);

        $jenisLaporan = $request->jenis_laporan;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $format = $request->format;

        // Ambil data berdasarkan jenis laporan
        if ($jenisLaporan === 'peminjaman') {
            $data = PeminjamanBuku::with(['user', 'buku'])
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir . ' 23:59:59'])
                ->orderByDesc('created_at')
                ->get();

            $title = 'Laporan Peminjaman Buku';
            $filename = 'laporan_peminjaman_' . date('d-m-Y', strtotime($tanggalAwal)) . '_' . date('d-m-Y', strtotime($tanggalAkhir));
        } elseif ($jenisLaporan === 'pengembalian') {
            $data = PeminjamanBuku::with(['user', 'buku'])
                ->whereNotNull('tanggal_kembali')
                ->whereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir . ' 23:59:59'])
                ->orderByDesc('tanggal_kembali')
                ->get();

            $title = 'Laporan Pengembalian Buku';
            $filename = 'laporan_pengembalian_' . date('d-m-Y', strtotime($tanggalAwal)) . '_' . date('d-m-Y', strtotime($tanggalAkhir));
        } elseif ($jenisLaporan === 'denda') {
            $data = Denda::with(['peminjaman.user', 'peminjaman.buku'])
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir . ' 23:59:59'])
                ->orderByDesc('created_at')
                ->get();

            $title = 'Laporan Denda';
            $filename = 'laporan_denda_' . date('d-m-Y', strtotime($tanggalAwal)) . '_' . date('d-m-Y', strtotime($tanggalAkhir));
        } else {
            $data = PeminjamanBuku::with(['user', 'buku'])
                ->where('approval', 'approved')
                ->where('tanggal_kembali', '<', now())
                ->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
                    $q->whereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir . ' 23:59:59'])
                        ->orWhereNull('tanggal_kembali');
                })
                ->orderByDesc('tanggal_kembali')
                ->get();

            $title = 'Laporan Keterlambatan Pengembalian';
            $filename = 'laporan_terlambat_' . date('d-m-Y', strtotime($tanggalAwal)) . '_' . date('d-m-Y', strtotime($tanggalAkhir));
        }

        if ($format === 'excel') {
            if ($jenisLaporan === 'peminjaman') {
                return Excel::download(new LaporanPeminjamanExport($data, $tanggalAwal, $tanggalAkhir), $filename . '.xlsx');
            } else {
                return Excel::download(new LaporanPengembalianExport($data, $tanggalAwal, $tanggalAkhir), $filename . '.xlsx');
            }
        } else {
            // PDF
            $pdf = Pdf::loadView('admin.laporan.pdf', compact('data', 'title', 'tanggalAwal', 'tanggalAkhir', 'jenisLaporan'));
            return $pdf->download($filename . '.pdf');
        }
    }

    public function bukuReport(Request $request)
    {
        $query = Buku::query();

        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%")
                ->orWhere('pengarang', 'like', "%{$request->search}%");
        }

        $bukuReport = $query->orderBy('judul')->paginate(20)->withQueryString();

        return view('admin.laporan.buku-report', compact('bukuReport'));
    }

    public function siswaReport(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->search) {
            $query->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nim_nip', 'like', "%{$request->search}%");
        }

        $siswaReport = $query->with(['peminjamanBuku' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('admin.laporan.siswa-report', compact('siswaReport'));
    }
}
