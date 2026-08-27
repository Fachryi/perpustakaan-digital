<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        $daftarKelas = Kelas::orderBy('nama')->get();
        if ($daftarKelas->isEmpty()) {
            $daftarKelas = collect(['VII a', 'VII b', 'VII c', 'VIII a', 'VIII b', 'VIII c', 'IX a', 'IX b', 'IX c'])->map(function ($name) {
                return (object) ['id' => $name, 'nama' => $name];
            });
        }
        return view('auth.register', compact('daftarKelas'));
    }

    public function register(Request $request)
    {
        $role = $request->role; // 'siswa' atau 'guru'

        $rules = [
            'role'     => 'required|in:siswa,guru',
            'nama'     => 'required|string|max:255',
            'nim_nip'  => 'required|string|max:50|unique:users,nim_nip',
            'password' => 'required|string|min:6|confirmed',
        ];

        $messages = [
            'role.required'      => 'Pilih jenis pendaftar terlebih dahulu.',
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'nim_nip.required'   => $role === 'guru' ? 'NIP wajib diisi.' : 'NIS wajib diisi.',
            'nim_nip.unique'     => $role === 'guru' ? 'NIP sudah terdaftar.' : 'NIS sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        // Siswa wajib pilih kelas
        if ($role === 'siswa') {
            $rules['kelas_id'] = 'required';
            $messages['kelas_id.required'] = 'Pilih kelas terlebih dahulu.';
        }

        $request->validate($rules, $messages);

        $kelasId = null;
        if ($role === 'siswa') {
            $kelasId = Kelas::resolveOrCreateIdFromName($request->kelas_id);
        }

        $user = User::create([
            'nama'     => $request->nama,
            'nim_nip'  => $request->nim_nip,
            'kelas_id' => $kelasId,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        Auth::login($user);

        $label = $role === 'guru' ? 'Guru' : 'Siswa';
        Alert::success('Berhasil Registrasi', "Selamat datang, {$user->nama}! Akun {$label} Anda berhasil dibuat.");

        return redirect('/welcome');
    }
}
