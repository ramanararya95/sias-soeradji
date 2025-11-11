<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Update last activity every 5 minutes
            $user = Auth::user();
            if (!$user->last_activity || $user->last_activity->diffInMinutes(now()) >= 5) {
                $user->last_activity = now();
                $user->save();
                
                // Clear online users cache
                \App\Models\CacheKey::clearGeneralCache();
            }
        }
        
        return $next($request);
    }
}