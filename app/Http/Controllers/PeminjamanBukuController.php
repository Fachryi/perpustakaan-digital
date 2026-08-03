<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\PeminjamanBuku;
use App\Models\User;
use App\Services\PeminjamanBukuService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class PeminjamanBukuController extends Controller
{
    protected $peminjamanService;

    public function __construct(PeminjamanBukuService $peminjamanService)
    {
        $this->peminjamanService = $peminjamanService;
    }

    public function destroy($id)
    {
        $peminjaman = PeminjamanBuku::find($id);
        if ($peminjaman->status == 'dipinjam') {
            $peminjaman->buku->increment('jumlah', 1);
        }

        PeminjamanBuku::destroy($id);

        return redirect('/admin/peminjaman');
    }

    public function pinjam($bukuId)
    {
        try {
            $this->peminjamanService->pinjamBuku(auth()->id(), $bukuId);

            Alert::success('Berhasil', 'Buku berhasil dipinjam!');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());

            return redirect()->back();
        }
    }

    public function kembalikan($id)
    {
        try {
            $this->peminjamanService->returnBook($id, auth()->id());

            Alert::success('Berhasil', 'Buku berhasil dikembalikan!');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());

            return redirect()->back();
        }
    }

    public function index(Request $request)
    {
        $query = PeminjamanBuku::with(['user', 'buku', 'approver', 'denda']);
        // Filter berdasarkan search
        $search = trim($request->search);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim_nip', 'like', "%{$search}%");
                })->orWhereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('pengarang', 'like', "%{$search}%");
                });
            });
        }
        // Filter berdasarkan user (untuk user biasa hanya melihat peminjamannya)
        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan approval
        if ($request->approval) {
            $query->where('approval', $request->approval);
        }

        // Filter berdasarkan user_id (untuk admin)
        if ($request->user_id && auth()->user()->hasRole('admin')) {
            $query->where('user_id', $request->user_id);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = auth()->user()->isAdmin() ? User::regularUsers()->get() : collect();

        return view('admin.peminjaman.index', compact('peminjaman', 'users'));
    }

    public function create()
    {
        $users = User::regularUsers()->with('kelas')->get();
        $buku = Buku::available()->with('jenis')->get();

        return view('admin.peminjaman.create', compact('users', 'buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam'
        ]);

        try {
            $this->peminjamanService->createPeminjamanByAdmin(
                $request->user_id,
                $request->buku_id,
                $request->tanggal_kembali,
                auth()->id(),
                $request->tanggal_pinjam
            );

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Buku berhasil dipinjamkan ke user!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $peminjaman = PeminjamanBuku::with(['user', 'buku', 'approver'])
            ->findOrFail($id);

        // Check authorization
        if (request()->user()->hasRole('admin') && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function returnBook($id)
    {
        try {
            $this->peminjamanService->returnBook($id, auth()->id());

            return back()->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function extendBorrowing(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date|after:today',
        ]);

        try {
            $this->peminjamanService->extendBorrowing($id, $request->tanggal_kembali, auth()->id());

            return back()->with('success', 'Masa peminjaman berhasil diperpanjang!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function myBorrowings()
    {
        $activeBorrowings = PeminjamanBuku::with(['buku', 'approver'])
            ->where('user_id', auth()->id())
            ->active()
            ->get();

        $historyBorrowings = PeminjamanBuku::with(['buku', 'approver'])
            ->where('user_id', auth()->id())
            ->where('status', 'dikembalikan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.my-borrowings', compact('activeBorrowings', 'historyBorrowings'));
    }

    public function overdueBooks()
    {
        $overdueBooks = PeminjamanBuku::with(['user', 'buku'])
            ->active()
            ->where('tanggal_kembali', '<', Carbon::now())
            ->paginate(10);

        return view('peminjaman.overdue', compact('overdueBooks'));
    }

    // Update method getUsers untuk AJAX
    public function getUsersAjax(Request $request)
    {
        $users = User::regularUsers()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    // Update method getBukuAjax for AJAX
    public function getBukuAjax(Request $request)
    {
        $buku = Buku::available()
            ->with('jenis')
            ->when($request->search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%");
            })
            ->select('id', 'judul', 'pengarang', 'jenis_id')
            ->limit(10)
            ->get();

        return response()->json($buku);
    }
}
