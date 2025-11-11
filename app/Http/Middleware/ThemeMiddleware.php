<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $theme = $user->settings->theme ?? 'light';
            
            // Apply theme class to body
            if ($theme === 'dark') {
                echo '<script>document.documentElement.classList.add("dark");</script>';
            } else {
                echo '<script>document.documentElement.classList.remove("dark");</script>';
            }
        }

        return $next($request);
    }
}