<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        $daftaradmin = User::with('prodi')->where('role', 'admin')->paginate(10);
        return view('admin.users.index', compact('daftaradmin'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {

        User::create(['nama' => $request->nama, 'nim_nip' => $request->nim_nip, 'password' => bcrypt($request->password), 'role' => 'admin']);

        Alert::success("Berhasil tambah admin");
        return redirect('/admin/users');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update(['nama' => $request->nama, 'nim_nip' => $request->nim_nip]);

        if ($request->password) {
            $user->update(["password" => bcrypt($request->password)]);
        }
        Alert::success("Berhasil update");
        return redirect('/admin/users');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Alert::success("Berhasil hapus");
        return redirect('/admin/users');
    }
}
