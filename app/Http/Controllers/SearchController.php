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
       
        $bukus = Buku::where('judul', 'like', '%'.$request->term.'%');

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
