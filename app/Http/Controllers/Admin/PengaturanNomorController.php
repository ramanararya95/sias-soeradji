<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengaturanNomor;

class PengaturanNomorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    public function index()
    {
        $pengaturan = PengaturanNomor::first();
        if (!$pengaturan) {
            $pengaturan = PengaturanNomor::create(['panjang_nomor_urut' => 4]);
        }
        
        return view('admin.pengaturan.nomor_arsip', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'panjang' => 'required|integer|min:2|max:10'
        ]);

        $pengaturan = PengaturanNomor::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanNomor();
        }
        
        $pengaturan->panjang_nomor_urut = $request->panjang;
        $pengaturan->save();

        return redirect()->route('admin.pengaturan.nomor')
            ->with('success', 'Pengaturan nomor arsip berhasil disimpan.');
    }
}