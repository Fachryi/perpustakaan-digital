<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanBukuController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiswaController;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\PeminjamanBuku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

// Route untuk pinjam/kembalikan buku (hanya untuk siswa yang login)
Route::middleware(['auth'])->group(function () {
    Route::post('/buku/{buku}/pinjam', [PeminjamanBukuController::class, 'pinjam'])->name('buku.pinjam');
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanBukuController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::delete('/peminjaman/{id}/hapus', [PeminjamanBukuController::class, 'destroy'])->name('peminjaman.destroy');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    $daftarBuku = Buku::with('user')->get();

    $peminjamanTerlambat = collect();
    $totalDendaBelumBayar = 0;
    $notifCount = 0;

    if (auth()->check() && auth()->user()->role === 'siswa') {
        $peminjamanTerlambat = PeminjamanBuku::with(['buku', 'denda'])
            ->where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->where('approval', 'approved')
            ->where('tanggal_kembali', '<', now())
            ->get();

        $totalDendaBelumBayar = \App\Models\Denda::whereHas('peminjaman', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->where('status', 'unpaid')
            ->sum('jumlah');

        $notifCount = $peminjamanTerlambat->count();
    }

    return view('welcome', compact('daftarBuku', 'peminjamanTerlambat', 'totalDendaBelumBayar', 'notifCount'));
})->middleware('auth');

Route::get('/faq', function () {
    return view('faq');
})->middleware('auth');

Route::get('/search', SearchController::class)->middleware('auth');

Route::get('/buku/{buku}', function (Buku $buku) {
    $buku->increment('view');

    return view('buku-detail', compact('buku'));
})->middleware('auth');

Route::get('/', function () {
    return view('auth.login');
})
    ->name('login')
    ->middleware('guest');

Route::post('/login', function (Request $request) {
    if (Auth::attempt(['nim_nip' => $request->username, 'password' => $request->password])) {
        // The user is being remembered...
        if (auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin') {
            return redirect()->intended('/admin/dashboard');
        }

        if (auth()->user()->role == 'siswa') {
            return redirect()->intended('/welcome');
        }
    } else {
        Alert::error('Username / Password Salah');

        return back();
    }
});

Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect('/admin/dashboard');
    }
    if (auth()->user()->role == 'siswa') {
        return redirect('/siswa/dashboard');
    }

    return redirect('/');
})->middleware('auth');

Route::get('/siswa/dashboard', function () {
    return view('siswa.dashboard');
})->middleware('auth');

Route::get('/admin/dashboard', function () {
    // Statistik umum
    $buku = Buku::count();
    $siswa = User::where('role', 'siswa')->count();
    $guru = User::where('role', 'guru')->count();
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

    // Tabel Peminjaman Terbaru
    $laporanPeminjaman = PeminjamanBuku::with(['user', 'buku'])
        ->orderByDesc('created_at')
        ->get();

    // Tabel Denda Terbaru
    $laporanDenda = Denda::with(['peminjaman.user', 'peminjaman.buku'])
        ->orderByDesc('created_at')
        ->get();

    return view('admin.dashboard', compact(
        'buku', 'siswa', 'guru',
        'totalBuku', 'totalSiswa', 'totalPeminjaman', 'totalDenda',
        'dendaTerbayar', 'dendaBelumBayar',
        'peminjamanAktif', 'peminjamanKembali', 'peminjamanTerlambat',
        'laporanPeminjaman', 'laporanDenda'
    ));
})->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/buku', [BukuController::class, 'index']);
    Route::get('/dashboard/buku/create', [BukuController::class, 'create']);
    Route::get('/dashboard/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{buku}/terima', [BukuController::class, 'terima']);
    Route::get('/dashboard/buku/{buku}/edit', [BukuController::class, 'edit']);
    Route::put('/dashboard/buku/{buku}', [BukuController::class, 'update']);
    Route::post('/dashboard/buku', [BukuController::class, 'store']);
    Route::delete('/dashboard/buku/{buku}', [BukuController::class, 'destroy']);

    Route::get('/master-data/jenis', [JenisController::class, 'index']);
    Route::get('/master-data/jenis/create', [JenisController::class, 'create']);
    Route::post('/master-data/jenis', [JenisController::class, 'store']);
    Route::get('/master-data/jenis/{jenis}/show', [JenisController::class, 'show']);
    Route::get('/master-data/jenis/{jenis}/edit', [JenisController::class, 'edit']);
    Route::put('/master-data/jenis/{jenis}/update', [JenisController::class, 'update']);
    Route::delete('/master-data/jenis/{jenis}', [JenisController::class, 'destroy']);

    Route::get('/master-data/kelas', [KelasController::class, 'index']);
    Route::get('/master-data/kelas/create', [KelasController::class, 'create']);
    Route::post('/master-data/kelas', [KelasController::class, 'store']);
    Route::get('/master-data/kelas/{kelas}/show', [KelasController::class, 'show']);
    Route::get('/master-data/kelas/{kelas}/edit', [KelasController::class, 'edit']);
    Route::put('/master-data/kelas/{kelas}/update', [KelasController::class, 'update']);
    Route::delete('/master-data/kelas/{kelas}', [KelasController::class, 'destroy']);

    Route::get('/master-data/siswa', [SiswaController::class, 'index']);
    Route::get('/master-data/siswa/create', [SiswaController::class, 'create']);
    Route::post('/master-data/siswa', [SiswaController::class, 'store']);
    Route::get('/master-data/siswa/{siswa}/show', [SiswaController::class, 'show']);
    Route::get('/master-data/siswa/{siswa}/edit', [SiswaController::class, 'edit']);
    Route::put('/master-data/siswa/{siswa}/update', [SiswaController::class, 'update']);
    Route::delete('/master-data/siswa/{siswa}', [SiswaController::class, 'destroy']);

    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::get('/admin/users/create', [AdminController::class, 'create']);
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::get('/admin/users/{user}/show', [AdminController::class, 'show']);
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit']);
    Route::put('/admin/users/{user}/update', [AdminController::class, 'update']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy']);

    Route::get('/master-data/guru', [GuruController::class, 'index']);
    Route::get('/master-data/guru/create', [GuruController::class, 'create']);
    Route::post('/master-data/guru', [GuruController::class, 'store']);
    Route::get('/master-data/guru/{guru}/show', [GuruController::class, 'show']);
    Route::get('/master-data/guru/{guru}/edit', [GuruController::class, 'edit']);
    Route::put('/master-data/guru/{guru}/update', [GuruController::class, 'update']);
    Route::delete('/master-data/guru/{guru}', [GuruController::class, 'destroy']);
});

Route::get('/my-buku', function () {
    $buku = Buku::with('user', 'kontributor.user')
        ->where('user_id', auth()->id())
        ->get();

    return $buku;
});

Route::get('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
});

// Admin: Manajemen peminjaman buku

Route::get('/admin/peminjaman/create', [PeminjamanBukuController::class, 'create'])->name('admin.peminjaman.create');
Route::post('/admin/peminjaman', [PeminjamanBukuController::class, 'store'])->name('peminjaman.store');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('peminjaman')->group(function () {
            // User can only view their borrowings
            Route::get('/my-borrowings', [PeminjamanBukuController::class, 'myBorrowings']);
            Route::get('/{id}', [PeminjamanBukuController::class, 'show'])->name('peminjaman.show');
            Route::patch('/{id}/return', [PeminjamanBukuController::class, 'returnBook'])->name('peminjaman.return');
            Route::patch('/{id}/extend', [PeminjamanBukuController::class, 'extendBorrowing'])->name('peminjaman.extend');

            // Admin only routes
            Route::middleware('role:admin')->group(function () {
                Route::get('/', [PeminjamanBukuController::class, 'index'])->name('admin.peminjaman.index');

                Route::get('/overdue/books', [PeminjamanBukuController::class, 'overdueBooks'])->name('overdue');
                Route::get('/users/ajax', [PeminjamanBukuController::class, 'getUsersAjax'])->name('users.ajax');
                Route::get('/buku/ajax', [PeminjamanBukuController::class, 'getBukuAjax'])->name('buku.ajax');
            });
        });
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::prefix('denda')->group(function () {
        Route::get('/', [DendaController::class, 'index'])->name('admin.denda.index');
        Route::get('/create', [DendaController::class, 'create'])->name('admin.denda.create');
        Route::post('/', [DendaController::class, 'store'])->name('admin.denda.store');
        Route::get('/{denda}/show', [DendaController::class, 'show'])->name('admin.denda.show');
        Route::patch('/{denda}/confirm-payment', [DendaController::class, 'confirmPayment'])->name('admin.denda.confirm-payment');
        Route::delete('/{denda}', [DendaController::class, 'destroy'])->name('admin.denda.destroy');
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/buku', [LaporanController::class, 'bukuReport'])->name('admin.laporan.buku');
        Route::get('/siswa', [LaporanController::class, 'siswaReport'])->name('admin.laporan.siswa');
        Route::get('/download', [LaporanController::class, 'download'])->name('admin.laporan.download');
    });
});

// TEMPORARY IMPORT ROUTE
Route::get('/import-db-secret-12345', function () {
    $sqlFile = base_path('digilib_export.sql');
    if (!file_exists($sqlFile)) return "File not found at: " . $sqlFile;

    $sql = file_get_contents($sqlFile);
    
    try {
        $db = \Illuminate\Support\Facades\DB::connection();
        $db->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->statement("SET SESSION sql_mode=''");
        
        // Split SQL into individual statements
        $lines = explode("\n", $sql);
        $statement = '';
        $count = 0;
        $errors = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0 || strpos($line, '*/') === 0) {
                continue;
            }
            $statement .= $line . "\n";
            // Statement ends with semicolon
            if (substr($line, -1) === ';') {
                try {
                    $db->unprepared($statement);
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = substr($statement, 0, 60) . '... => ' . $e->getMessage();
                }
                $statement = '';
            }
        }
        
        $db->statement("SET FOREIGN_KEY_CHECKS=1");
        
        $msg = "Imported $count statements successfully.";
        if (!empty($errors)) {
            $msg .= "\n\nErrors (" . count($errors) . "):\n" . implode("\n", array_slice($errors, 0, 5));
        }
        return nl2br(htmlspecialchars($msg));
    } catch (\Exception $e) {
        return "Fatal Error: " . $e->getMessage();
    }
});
