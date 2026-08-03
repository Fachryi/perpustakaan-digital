<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelasController extends Controller
{
    public function index()
    {
        $daftarKelas = Kelas::query()->paginate(10);
        return view('admin.master-data.kelas.index', compact('daftarKelas'));
    }

    public function create()
    {
        return view('admin.master-data.kelas.create');
    }

    public function store(Request $request)
    {
        if ($request->gambar) {
            $gambar  =  $request->file('gambar')->store('gambar_kelas');
        }
        Kelas::create(['nama' => $request->nama, "gambar" => $gambar ?? null]);
        Alert::success("Berhasil tambah kelas");
        return redirect('/master-data/kelas');
    }

    public function show(Kelas $kelas)
    {
        return view('admin.master-data.kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.master-data.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        if ($request->gambar) {
            $gambar  =  $request->file('gambar')->store('gambar_kelas');
            $kelas->update(["gambar" => $gambar]);
        }
        $kelas->update(['nama' => $request->nama]);
        Alert::success("Berhasil update");
        return redirect('/master-data/kelas');
    }

    public function destroy(Kelas $kelas)
    {
        @unlink("storage/" . $kelas->gambar);
        $kelas->delete();
        Alert::success("Berhasil hapus");
        return redirect('/master-data/kelas');
    }
}
