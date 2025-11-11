<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ambil semua notifikasi user dengan pagination
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function show($id)
    {
        $user = auth()->user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Tandai sebagai dibaca jika belum dibaca
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }
        
        return view('notifications.show', compact('notification'));
    }
    
    public function markAsRead($id)
    {
        $user = auth()->user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->read_at = now();
        $notification->save();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        $user = auth()->user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    public function destroy($id)
    {
        $user = auth()->user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->delete();
        
        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus');
    }
}