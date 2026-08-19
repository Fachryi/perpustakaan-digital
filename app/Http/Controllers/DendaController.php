<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\PeminjamanBuku;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Denda::with(['peminjaman.user', 'peminjaman.buku']);

        if ($request->search) {
            $query->whereHas('peminjaman.user', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nim_nip', 'like', "%{$request->search}%");
            })->orWhereHas('peminjaman.buku', function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                    ->orWhere('pengarang', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $denda = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.denda.index', compact('denda'));
    }

    public function create()
    {
        $peminjaman = PeminjamanBuku::with(['user', 'buku'])
            ->where('status', 'dipinjam')
            ->where('approval', 'approved')
            ->orderByDesc('created_at')
            ->get();

        $dendaPerHari       = (int) Pengaturan::getValue('denda_per_hari', 1000);
        $dendaKehilangan    = (int) Pengaturan::getValue('denda_kehilangan_default', 50000);

        return view('admin.denda.create', compact('peminjaman', 'dendaPerHari', 'dendaKehilangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_buku_id' => 'required|exists:peminjaman_buku,id',
            'jenis_denda'        => 'required|in:keterlambatan,kehilangan',
            'jumlah'             => 'required_if:jenis_denda,kehilangan|numeric|min:0|nullable',
            'status'             => 'required|in:unpaid,paid',
            'tanggal_bayar'      => 'nullable|date',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        $peminjaman  = PeminjamanBuku::findOrFail($request->peminjaman_buku_id);
        $jenisDenda  = $request->jenis_denda;
        $hariTerlambat = 0;
        $jumlah        = 0;

        if ($jenisDenda === 'keterlambatan') {
            // Hitung hari terlambat otomatis
            $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
            $hariTerlambat  = max(0, (int) $tanggalKembali->diffInDays(Carbon::now(), false));
            // Jika belum terlambat, tetap bisa dikenakan dengan hari 0
            $dendaPerHari   = (int) Pengaturan::getValue('denda_per_hari', 1000);
            $jumlah         = $hariTerlambat * $dendaPerHari;
        } else {
            // Kehilangan: admin input manual
            $jumlah        = (float) $request->jumlah;
            $hariTerlambat = 0;
        }

        if ($request->status === 'paid' && ! $request->tanggal_bayar) {
            $tanggalBayar = now()->format('Y-m-d');
        } else {
            $tanggalBayar = $request->tanggal_bayar;
        }

        Denda::create([
            'peminjaman_buku_id' => $request->peminjaman_buku_id,
            'jumlah'             => $jumlah,
            'status'             => $request->status,
            'tanggal_bayar'      => $tanggalBayar,
            'keterangan'         => $request->keterangan,
            'jenis_denda'        => $jenisDenda,
            'hari_terlambat'     => $hariTerlambat,
        ]);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Denda berhasil ditambahkan.');
    }

    public function show(Denda $denda)
    {
        $denda->load(['peminjaman.user', 'peminjaman.buku']);

        return view('admin.denda.show', compact('denda'));
    }

    public function confirmPayment(Denda $denda)
    {
        if ($denda->status === 'paid') {
            return redirect()->back()->with('info', 'Denda sudah berstatus lunas.');
        }

        $denda->update([
            'status' => 'paid',
            'tanggal_bayar' => now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil dikonfirmasi.');
    }

    public function destroy(Denda $denda)
    {
        $denda->delete();

        return redirect()->route('admin.denda.index')
            ->with('success', 'Denda berhasil dihapus.');
    }
}
