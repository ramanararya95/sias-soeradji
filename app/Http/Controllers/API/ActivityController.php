<?php

// app/Http/Controllers/API/ActivityController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function getTodayActivities(Request $request)
    {
        $user = $request->user();
        
        try {
            $activities = Activity::with('user')
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user' => [
                            'id' => $activity->user->id,
                            'nama_lengkap' => $activity->user->nama_lengkap,
                            'initials' => $activity->user->initials,
                            'avatar_url' => $activity->user->profile && $activity->user->profile->foto ? 
                                asset('storage/profiles/' . $activity->user->profile->foto) : null,
                        ],
                        'description' => $activity->description,
                        'time' => $activity->created_at->format('H:i')
                    ];
                });
                
            return response()->json($activities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'activities' => []
            ], 500);
        }
    }
}