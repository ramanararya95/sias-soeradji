<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate token
        $token = Str::random(60);
        $expiry = now()->addHour();

        // Update token
        $user->update([
            'reset_token' => $token,
            'reset_expires' => $expiry,
        ]);

        // Kirim email
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user));
            return back()->with('success', 'Link reset telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }

    public function showResetForm($token)
    {
        // Validasi token
        $user = User::where('reset_token', $token)
                    ->where('reset_expires', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'captcha' => 'required|string',
        ]);

        // Validasi CAPTCHA
        if (strtoupper($request->captcha) !== Session::get('captcha')) {
            return back()->withErrors(['captcha' => 'Kode CAPTCHA salah!'])->withInput();
        }

        // Validasi token
        $user = User::where('reset_token', $request->token)
                    ->where('reset_expires', '>', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_expires' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}