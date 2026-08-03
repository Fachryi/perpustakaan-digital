<?php

namespace App\Http\Controllers;

use App\Imports\BukuImport;
use App\Models\Buku;
use App\Models\FileBuku;
use App\Models\Jenis;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $daftarBuku = Buku::with('user', 'fileBuku')->orderBy('updated_at', 'desc');
        if ($request->search) {
            $daftarBuku->where('judul', 'LIKE', "%{$request->search}%");
        }

        if ($request->jenis_id) {
            $daftarBuku->where('jenis_id', $request->jenis_id);
        }
        // if (auth()->user()->role !== "admin") {
        //     $daftarBuku->where('user_id', auth()->id());
        // }

        if ($request->status) {
            $daftarBuku->where('status', $request->status);
        }

        $daftarBuku = $daftarBuku->paginate(25);
        $daftarJenis = Jenis::all();
        $daftarKelas = Kelas::all();

        return view('buku.index', compact('daftarBuku', 'daftarJenis', 'daftarKelas'));
    }

    public function show(Buku $buku)
    {
        $buku = $buku->load('fileBuku');
        $daftarJenis = Jenis::all();
        $daftarKelas = Kelas::all();

        return view('buku.show', compact('buku', 'daftarJenis', 'daftarKelas'));
    }

    public function create()
    {
        $users = User::where(function ($query) {
            $query->where('role', 'guru')->orWhere('role', 'siswa');
        })->get();
        $daftarGuru = User::where('role', 'guru')->get();

        $daftarJenis = Jenis::all();
        $daftarKelas = Kelas::all();

        return view('buku.create', compact('daftarGuru', 'users', 'daftarJenis', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul' => ['required', 'max:255'],
            'sinopsis' => ['required'],
            'jumlah' => ['required'],
            'pengarang' => ['required'],
            'penerbit' => ['required'],
            'tahun_terbit' => ['required'],
            'file' => ['file', 'mimes:pdf'],
            'foto' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'abstrak' => ['nullable', 'string'],
        ];

        if (auth()->user()->role == 'admin') {
            $rules['judul'][] = 'unique:buku,judul';
            $rules['jenis_koleksi'] = ['required'];
            $status = 'tersedia';
        }

        $request->validate($rules);

        $filePath = $request->hasFile('file') ? $request->file('file')->store('file', 'public') : null;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_buku', 'public');
        }

        $buku = Buku::create([
            'judul' => $request->judul,
            'sinopsis' => $request->sinopsis,
            'jumlah' => $request->jumlah,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'jenis_id' => $request->jenis_koleksi ?? 1,
            'kelas_id' => $request->kelas ?? 1,
            'status' => $status ?? 'tersedia',
            'foto' => $fotoPath,
            'abstrak' => $request->abstrak,
        ]);

        if ($filePath) {
            FileBuku::create([
                'buku_id' => $buku->id,
                'file_name' => $filePath,
                'file_size' => $request->file('file')->getSize(),
                'file_type' => 'pdf',
            ]);
        }

        Alert::success('Berhasil tambah buku');

        return redirect('/dashboard/buku');
    }

    public function edit(Buku $buku)
    {
        $users = User::where(function ($query) {
            $query->where('role', 'guru')->orWhere('role', 'siswa');
        })->get();

        $daftarGuru = User::where('role', 'guru')->get();
        $daftarJenis = Jenis::all();
        $daftarKelas = Kelas::all();

        return view('buku.edit', compact('buku', 'users', 'daftarGuru', 'daftarJenis', 'daftarKelas'));
    }

    public function update(Request $request, Buku $buku)
    {
        $rules = [
            'judul' => ['required', 'max:255'],
            'sinopsis' => ['required'],
            'jumlah' => ['required'],
            'pengarang' => ['required'],
            'penerbit' => ['required'],
            'tahun_terbit' => ['required'],
            'jenis_koleksi' => ['required'],
            'kelas' => ['required'],
            'file' => ['file', 'mimes:pdf'],
        ];

        if (auth()->user()->role == 'admin') {
            $rules['judul'][] = 'unique:buku,judul,'.$buku->id;
        }

        $request->validate($rules);

        $buku->update([
            'judul' => $request->judul,
            'sinopsis' => $request->sinopsis,
            'jumlah' => $request->jumlah,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'jenis_id' => $request->jenis_koleksi,
            'kelas_id' => $request->kelas,
            'abstrak' => $request->abstrak,
        ]);

        if ($request->hasFile('file')) {
            $fileBuku = FileBuku::where('buku_id', $buku->id)->first();
            if ($fileBuku && ! empty($fileBuku->file_name)) {
                if (Storage::disk('public')->exists($fileBuku->file_name)) {
                    Storage::disk('public')->delete($fileBuku->file_name);
                }
                $fileBuku->delete();
            }

            $filePath = $request->file('file')->store('file', 'public');

            FileBuku::create([
                'buku_id' => $buku->id,
                'file_name' => $filePath,
                'file_size' => $request->file('file')->getSize(),
                'file_type' => 'pdf',
            ]);
        }

        if ($request->hasFile('foto')) {
            if ($buku->foto && Storage::disk('public')->exists($buku->foto)) {
                Storage::disk('public')->delete($buku->foto);
            }
            $buku->update(['foto' => $request->file('foto')->store('foto_buku', 'public')]);
        }

        Alert::success('Berhasil update buku');

        return redirect('/dashboard/buku');
    }

    public function terima(Buku $buku)
    {
        $buku->update(['status' => 'tersedia']);
        Alert::success('Berhasil menerima buku '.$buku->judul);

        return back();
    }

    public function import(Request $request)
    {
        Excel::import(new BukuImport, 'data.xlsx');

        return redirect('/welcome')->with('success', 'All good!');
    }

    public function destroy(Buku $buku)
    {
        $fileBuku = FileBuku::where('buku_id', $buku->id)->first();
        if ($fileBuku && ! empty($fileBuku->file_name)) {
            if (Storage::disk('public')->exists($fileBuku->file_name)) {
                Storage::disk('public')->delete($fileBuku->file_name);

                $fileBuku->delete();
            }
        }

        $buku->delete();
        Alert::success('Berhasil hapus buku');

        return back();
    }
}
