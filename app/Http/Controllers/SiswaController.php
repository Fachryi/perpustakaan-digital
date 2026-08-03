<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SiswaController extends Controller
{
    public function index()
    {
        $daftarsiswa = User::with('kelas')->where('role', 'siswa')->paginate(10);
        return view('admin.master-data.siswa.index', compact('daftarsiswa'));
    }

    public function create()
    {
        $daftarKelas = ['VII a', 'VII b', 'VII c', 'VIII a', 'VIII b', 'VIII c', 'IX a', 'IX b', 'IX c'];
        return view('admin.master-data.siswa.create', compact('daftarKelas'));
    }

    public function store(Request $request)
    {
        User::create([
            'nama' => $request->nama,
            'nim_nip' => $request->nis,
            'password' => bcrypt($request->password),
            'kelas_id' => Kelas::resolveOrCreateIdFromName($request->kelas_id),
            'role' => 'siswa',
        ]);
        Alert::success('Berhasil tambah siswa');
        return redirect('/master-data/siswa');
    }

    public function show(User $siswa)
    {
        return view('admin.master-data.siswa.show', compact('siswa'));
    }

    public function edit(User $siswa)
    {
        $daftarKelas = ['VII a', 'VII b', 'VII c', 'VIII a', 'VIII b', 'VIII c', 'IX a', 'IX b', 'IX c'];
        return view('admin.master-data.siswa.edit', compact('siswa', 'daftarKelas'));
    }

    public function update(Request $request, User $siswa)
    {
        $siswa->update([
            'nama' => $request->nama,
            'nim_nip' => $request->nim_nip,
            'kelas_id' => Kelas::resolveOrCreateIdFromName($request->kelas_id),
        ]);
        if ($request->password) {
            $siswa->update(['password' => bcrypt($request->password)]);
        }
        Alert::success('Berhasil update');
        return redirect('/master-data/siswa');
    }

    public function destroy(User $siswa)
    {
        $siswa->delete();
        Alert::success('Berhasil hapus');
        return redirect('/master-data/siswa');
    }
}
