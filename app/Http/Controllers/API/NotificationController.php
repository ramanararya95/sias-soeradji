<?php

// app/Http/Controllers/API/NotificationController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getUnread(Request $request)
    {
        $user = $request->user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')  // Perbaikan: gunakan whereNull instead of unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
    
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$notification) {
            return response()->json(['success' => false], 404);
        }
        
        $notification->read_at = now();
        $notification->save();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')  // Perbaikan: gunakan whereNull instead of unread()
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
}