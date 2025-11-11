<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackgroundController extends Controller
{
    public function __construct()
    {
        // Ganti dari 'role:admin' menjadi 'is_admin'
        $this->middleware('auth'); 
    }

    public function index()
    {
        $user = Auth::user();
        return view('admin.pengaturan.background', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'background' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ilustrasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Proses upload background
        if ($request->hasFile('background')) {
            $file = $request->file('background');
            $ext = $file->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '.' . $ext;
            
            // Hapus background lama jika ada
            if ($user->background) {
                Storage::disk('public')->delete('background/' . $user->background);
            }
            
            // Simpan background baru
            $file->storeAs('background', $filename, 'public');
            $user->background = $filename;
        }

        // Proses upload ilustrasi
        if ($request->hasFile('ilustrasi')) {
            $file = $request->file('ilustrasi');
            $ext = $file->getClientOriginalExtension();
            $filename = 'ilustrasi_' . $user->id . '.' . $ext;
            
            // Hapus ilustrasi lama jika ada
            if ($user->login_ilustrasi) {
                Storage::disk('public')->delete('ilustrasi/' . $user->login_ilustrasi);
            }
            
            // Simpan ilustrasi baru
            $file->storeAs('ilustrasi', $filename, 'public');
            $user->login_ilustrasi = $filename;
        }

        $user->save();

        return redirect()->route('admin.pengaturan.background')
            ->with('success', 'Pengaturan tampilan login berhasil diperbarui!');
    }
}