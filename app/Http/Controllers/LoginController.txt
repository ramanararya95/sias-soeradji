<?php

use App\Models\Profile;

if (Auth::attempt($credentials)) {
    $user = Auth::user();

    // Update status online
    $user->profile()->updateOrCreate(
        ['user_id' => $user->id],
        ['status_online' => true, 'last_seen' => now()]
    );

    return redirect()->intended('dashboard');
}
else {
    return back()->withErrors([
        'username' => 'The provided credentials do not match our records.',
    ])->onlyInput('username');
}

if (auth()->check()) {
    auth()->user()->profile()->update(['status_online' => false, 'last_seen' => now()]);
}

Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();

return redirect('/login');

