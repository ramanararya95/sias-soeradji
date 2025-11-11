<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\RememberToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        // Generate CAPTCHA jika belum ada
        if (!Session::has('captcha')) {
            $this->generateCaptcha();
        }

        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|string',
        ]);

        // Validasi CAPTCHA
        if (strtoupper($request->captcha) !== Session::get('captcha')) {
            return back()
                ->withErrors(['captcha' => 'Kode CAPTCHA salah!'])
                ->withInput();
        }

        // Regenerasi CAPTCHA setelah percobaan login
        $this->generateCaptcha();

        // Coba login dengan username atau email
        $user = null;
        
        // Coba dengan username jika kolom ada
        if (\Schema::hasColumn('users', 'username')) {
            $user = User::where('username', $request->username)->first();
        }
        
        // Jika tidak ditemukan, coba dengan email
        if (!$user) {
            $user = User::where('email', $request->username)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'Username atau password salah!'])
                ->withInput();
        }

        // Cek status jika kolom ada
        if (\Schema::hasColumn('users', 'status') && $user->status !== 'aktif') {
            return back()
                ->withErrors(['login' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'])
                ->withInput();
        }

        // Login user
        Auth::login($user, $request->has('remember'));

        // Update last activity jika kolom ada
        if (\Schema::hasColumn('users', 'last_activity')) {
            $user->update(['last_activity' => now()]);
        }

        // Regenerate session ID untuk keamanan
        $request->session()->regenerate();

        // Handle remember me
        if ($request->has('remember')) {
            $this->createRememberToken($user);
        }

        // Redirect ke dashboard
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        // Hapus remember token jika ada
        if ($request->cookie('remember_token')) {
            $this->clearRememberToken($request->cookie('remember_token'));
        }

        Auth::logout();
        
        // Invalidate dan regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    private function generateCaptcha($length = 6)
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        Session::put('captcha', $captcha);
        return $captcha;
    }

    private function createRememberToken(User $user)
    {
        // Hapus token lama
        $user->rememberTokens()->delete();

        // Buat token baru
        $token = Str::random(32);
        $selector = Str::random(16);
        $hashedToken = hash('sha256', $token);
        $expiresAt = now()->addDays(30);

        // Simpan ke database
        $user->rememberTokens()->create([
            'selector' => $selector,
            'token' => $hashedToken,
            'expires_at' => $expiresAt,
        ]);

        // Set cookie
        $cookieValue = $selector . ':' . $token;
        Cookie::queue('remember_token', $cookieValue, $expiresAt->diffInMinutes());
    }

    private function clearRememberToken($cookieValue)
    {
        $parts = explode(':', $cookieValue);
        if (count($parts) === 2) {
            $selector = $parts[0];
            RememberToken::where('selector', $selector)->delete();
        }
        Cookie::queue(Cookie::forget('remember_token'));
    }
}