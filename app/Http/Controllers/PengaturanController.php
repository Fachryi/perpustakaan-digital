<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::all()->keyBy('key');
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'batas_hari_pinjam'       => 'required|integer|min:1|max:365',
            'denda_per_hari'          => 'required|integer|min:0',
            'denda_kehilangan_default'=> 'required|integer|min:0',
        ], [
            'batas_hari_pinjam.required'        => 'Batas hari pinjam wajib diisi.',
            'batas_hari_pinjam.integer'         => 'Batas hari pinjam harus berupa angka.',
            'batas_hari_pinjam.min'             => 'Batas hari pinjam minimal 1 hari.',
            'denda_per_hari.required'           => 'Denda per hari wajib diisi.',
            'denda_per_hari.integer'            => 'Denda per hari harus berupa angka.',
            'denda_kehilangan_default.required' => 'Denda kehilangan wajib diisi.',
        ]);

        Pengaturan::setValue('batas_hari_pinjam', $request->batas_hari_pinjam);
        Pengaturan::setValue('denda_per_hari', $request->denda_per_hari);
        Pengaturan::setValue('denda_kehilangan_default', $request->denda_kehilangan_default);

        Alert::success('Berhasil', 'Pengaturan sistem berhasil disimpan!');

        return redirect()->route('admin.pengaturan');
    }
}
