<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class JenisController extends Controller
{
    public function index()
    {
        $daftarjenis = Jenis::query()->paginate(10);
        return view('admin.master-data.jenis.index', compact('daftarjenis'));
    }

    public function create()
    {
        return view('admin.master-data.jenis.create');
    }

    public function store(Request $request)
    {
        Jenis::create(['nama' => $request->nama]);
        Alert::success("Berhasil tambah jenis");
        return redirect('/master-data/jenis');
    }

    public function show(Jenis $jenis)
    {
        return view('admin.master-data.jenis.show', compact('jenis'));
    }

    public function edit(Jenis $jenis)
    {
        return view('admin.master-data.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $jenis->update(['nama' => $request->nama]);
        Alert::success("Berhasil update");
        return redirect('/master-data/jenis');
    }

    public function destroy(Jenis $jenis)
    {
        $jenis->delete();
        Alert::success("Berhasil hapus");
        return redirect('/master-data/jenis');
    }
}
