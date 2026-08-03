<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GuruController extends Controller
{
    public function index()
    {
        $daftarguru = User::with('kelas')->where('role', 'guru')->paginate(10);
        return view('admin.master-data.guru.index', compact('daftarguru'));
    }

    public function create()
    {
        $daftarKelas = Kelas::all();
        return view('admin.master-data.guru.create', compact('daftarKelas'));
    }

    public function store(Request $request)
    {

        User::create(['nama' => $request->nama, 'nim_nip' => $request->nim_nip, 'password' => bcrypt($request->password), 'kelas_id' => $request->kelas_id, 'role' => 'guru']);
        Alert::success("Berhasil tambah guru");
        return redirect('/master-data/guru');
    }

    public function show(User $guru)
    {
        return view('admin.master-data.guru.show', compact('guru'));
    }

    public function edit(User $guru)
    {
        $daftarKelas = Kelas::all();
        return view('admin.master-data.guru.edit', compact('guru', 'daftarKelas'));
    }

    public function update(Request $request, User $guru)
    {
        $guru->update(['nama' => $request->nama, 'nim_nip' => $request->nim_nip, 'kelas_id' => $request->kelas_id]);
        if ($request->password) {
            $guru->update(["password" => bcrypt($request->password)]);
        }
        Alert::success("Berhasil update");
        return redirect('/master-data/guru');
    }

    public function destroy(User $guru)
    {
        $guru->delete();
        Alert::success("Berhasil hapus");
        return redirect('/master-data/guru');
    }
}
