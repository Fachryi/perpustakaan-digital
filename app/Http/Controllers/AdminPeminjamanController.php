<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;

class AdminPeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = PeminjamanBuku::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }


    public function approve($id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);
        $peminjaman->update([
            'approval' => 'approved',
            'approval_by' => auth()->id(),
        ]);
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject($id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);
        $peminjaman->update([
            'approval' => 'rejected',
            'approval_by' => auth()->id(),
        ]);
        return back()->with('success', 'Peminjaman ditolak.');
    }
}
