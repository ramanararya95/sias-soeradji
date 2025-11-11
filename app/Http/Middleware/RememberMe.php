<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RememberToken;

class RememberMe
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() && $request->cookie('remember_token')) {
            $token = $request->cookie('remember_token');
            $parts = explode(':', $token);
            
            if (count($parts) === 2) {
                $selector = $parts[0];
                $tokenValue = $parts[1];
                
                $rememberToken = RememberToken::where('selector', $selector)
                    ->with('user')
                    ->first();
                
                if ($rememberToken && 
                    !$rememberToken->isExpired() && 
                    hash('sha256', $tokenValue) === $rememberToken->token) {
                    
                    Auth::login($rememberToken->user);
                }
            }
        }
        
        return $next($request);
    }
}