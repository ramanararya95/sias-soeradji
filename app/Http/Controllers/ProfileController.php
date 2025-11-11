<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tampilkan halaman profile
    public function index()
    {
        $user = Auth::user();
        $settings = Setting::firstOrCreate(
            ['user_id' => $user->id],
            ['theme' => 'light', 'language' => 'id']
        );

        return view('profile.index', compact('user', 'settings'));
    }

    // Update foto profil
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = $file->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '.' . $ext;

            if ($user->foto) {
                Storage::disk('public')->delete('profiles/' . $user->foto);
            }

            $file->storeAs('profiles', $filename, 'public');
            $user->foto = $filename;
            $user->save();
        }

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    // ✅ Update tema & bahasa
    public function updateSettings(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:id,en',
        ]);

        $user = Auth::user();

        // Ambil atau buat setting untuk user
        $settings = Setting::firstOrCreate(
            ['user_id' => $user->id],
            ['theme' => 'light', 'language' => 'id']
        );

        // Update nilai
        $settings->update([
            'theme' => $request->theme,
            'language' => $request->language,
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak cocok']);
        }

        // Update password baru
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui!');
    }


}
