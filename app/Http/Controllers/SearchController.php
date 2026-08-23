<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Jenis;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //$start = microtime(true);
        $jenis = Jenis::all();
        $kelass = Kelas::all();
        $bukus = Buku::query();

        if ($request->term) {
            $term = $request->term;
            $bukus->where(function ($q) use ($term) {
                $q->where('judul', 'like', '%' . $term . '%')
                  ->orWhere('kode_buku', 'like', '%' . $term . '%')
                  ->orWhere('pengarang', 'like', '%' . $term . '%')
                  ->orWhere('penerbit', 'like', '%' . $term . '%');
            });
        }

        if ($request->jenis) {
            $bukus->whereIn('jenis_id', $request->jenis);
        }

        if ($request->kelas) {
            $bukus->whereIn('kelas_id', $request->kelas);
        }

        $bukus = $bukus->paginate(25);
       // $end = microtime(true);
       // $requestTime = round(($end - $start) * 1000, 2);
        return view('search', compact('bukus', 'jenis', 'kelass'));
    }
}
