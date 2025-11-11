<?php

// app/Http/Controllers/API/UserController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getOnlineUsers(Request $request)
    {
        $currentUser = $request->user();
        
        try {
            // Get users who were active in the last 5 minutes
            $fiveMinutesAgo = Carbon::now()->subMinutes(5);
            
            $onlineUsers = User::whereHas('activities', function ($query) use ($fiveMinutesAgo) {
                    $query->where('created_at', '>=', $fiveMinutesAgo);
                })
                ->with('profile')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'nama_lengkap' => $user->nama_lengkap,
                        'role' => $user->role,
                        'initials' => $user->initials,
                        'avatar_url' => $user->profile && $user->profile->foto ? 
                            asset('storage/profiles/' . $user->profile->foto) : null,
                        'last_activity_formatted' => 'Sekarang'
                    ];
                });
                
            // Add current user if not in the list
            if (!$onlineUsers->contains('id', $currentUser->id)) {
                $onlineUsers->prepend([
                    'id' => $currentUser->id,
                    'nama_lengkap' => $currentUser->nama_lengkap,
                    'role' => $currentUser->role,
                    'initials' => $currentUser->initials,
                    'avatar_url' => $currentUser->profile && $currentUser->profile->foto ? 
                        asset('storage/profiles/' . $currentUser->profile->foto) : null,
                    'last_activity_formatted' => 'Sekarang'
                ]);
            }
            
            return response()->json($onlineUsers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'users' => []
            ], 500);
        }
    }
}