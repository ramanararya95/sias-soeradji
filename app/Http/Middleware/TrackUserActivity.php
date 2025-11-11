<?php

// app/Http/Middleware/TrackUserActivity.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivity;
use Carbon\Carbon;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only track for authenticated users
        if ($request->user()) {
            // Update or create user activity record
            UserActivity::updateOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'ip_address' => $request->ip(),
                ],
                [
                    'user_agent' => $request->userAgent(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
        
        return $response;
    }
}