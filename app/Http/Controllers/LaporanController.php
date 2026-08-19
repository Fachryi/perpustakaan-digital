<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'jenis_laporan' => 'required|in:peminjaman,pengembalian,denda,terlambat,anggota,buku',
            'tanggal_awal'  => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
            'format'        => 'required|in:pdf',
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

        if ($jenisLaporan === 'anggota') {
            // Laporan anggota — hanya PDF
            $data    = User::where('role', 'siswa')->with(['peminjamanBuku.denda', 'kelas'])->orderBy('nama')->get();
            $title   = 'Laporan Data Anggota';
            $pdf     = Pdf::loadView('admin.laporan.pdf-anggota', compact('data', 'title'));
            return $pdf->download('laporan_anggota_' . date('d-m-Y') . '.pdf');
        } elseif ($jenisLaporan === 'buku') {
            // Laporan data buku — hanya PDF
            $data    = Buku::with(['jenis', 'kelas', 'kategori'])->orderBy('judul')->get();
            $title   = 'Laporan Data Buku';
            $pdf     = Pdf::loadView('admin.laporan.pdf-buku', compact('data', 'title'));
            return $pdf->download('laporan_buku_' . date('d-m-Y') . '.pdf');
        } else {
            // PDF
            $pdf = Pdf::loadView('admin.laporan.pdf', compact('data', 'title', 'tanggalAwal', 'tanggalAkhir', 'jenisLaporan'));
            return $pdf->download($filename . '.pdf');
        }
    }

    public function bukuReport(Request $request)
    {
        $query = Buku::with(['jenis', 'kelas', 'kategori']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                    ->orWhere('pengarang', 'like', "%{$request->search}%")
                    ->orWhere('kode_buku', 'like', "%{$request->search}%");
            });
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

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswaReport = $query
            ->with([
                'kelas',
                'peminjamanBuku' => fn($q) => $q->orderBy('created_at', 'desc'),
                'peminjamanBuku.denda',
            ])
            ->withCount('peminjamanBuku')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        // Hitung total denda per siswa
        $siswaReport->each(function ($siswa) {
            $siswa->total_denda = $siswa->peminjamanBuku->flatMap->denda->sum('jumlah');
            $siswa->status_aktif = $siswa->peminjamanBuku->where('status', 'dipinjam')->count() > 0;
        });

        $daftarKelas = \App\Models\Kelas::all();

        return view('admin.laporan.siswa-report', compact('siswaReport', 'daftarKelas'));
    }

    public function anggotaReport(Request $request)
    {
        return $this->siswaReport($request);
    }
}
