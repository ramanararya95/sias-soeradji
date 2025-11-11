<?php

use App\Http\Controllers\WatermarkController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// API Watermark Routes
Route::prefix('watermark')->name('api.watermark.')->middleware('auth:api')->group(function () {
    // Image Watermark
    Route::post('/image/process', [WatermarkController::class, 'processImage'])->name('image.process');
    Route::post('/image/save-preview', [WatermarkController::class, 'saveImagePreview'])->name('image.save-preview');
    
    // Text Watermark
    Route::post('/text/process', [WatermarkController::class, 'processText'])->name('text.process');
});

// Include API v2 routes
require __DIR__.'/api_v2.php';

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    
    // Notifications
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    
    // Online Users
    Route::get('/users/online', [UserController::class, 'getOnlineUsers']);
    
    // Activities
    Route::get('/activities/today', [ActivityController::class, 'getTodayActivities']);
    
    // Chat
    Route::get('/chat/{userId}', [ChatController::class, 'getChatMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
});


// Dashboard API Routes
Route::middleware('auth')->group(function () {
    // Dashboard Stats
    Route::get('/dashboard/stats', function (Request $request) {
        // Replace with actual data from your database
        return response()->json([
            'total_arsip_aktif' => \App\Models\ArsipAktif::count(),
            'total_arsip_inaktif' => \App\Models\ArsipInaktif::count(),
            'total_surat_tugas' => \App\Models\SuratTugas::count(),
            'total_berita_pemindahan' => \App\Models\BeritaAcaraPemindahan::count(),
            'total_berita_pemusnahan' => \App\Models\BeritaAcaraPemusnahan::count(),
            'total_berita_alihmedia' => \App\Models\BeritaAcaraAlihmedia::count(),
        ]);
    });
    
    // Dashboard Activities
    Route::get('/dashboard/activities', function (Request $request) {
        // Replace with actual data from your database
        $activities = \App\Models\Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => [
                        'name' => $activity->user->nama_lengkap,
                        'avatar' => $activity->user->profile && $activity->user->profile->foto 
                            ? asset('storage/profiles/' . $activity->user->profile->foto) 
                            : null
                    ],
                    'description' => $activity->description,
                    'time' => $activity->created_at->diffForHumans()
                ];
            });
            
        return response()->json($activities);
    });
    
    // Notifications
    Route::get('/notifications', function (Request $request) {
        // Replace with actual data from your database
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time' => $notification->created_at->diffForHumans()
                ];
            });
            
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->count()
        ]);
    });
    
    // Online Users
    Route::get('/users/online', function (Request $request) {
        // Replace with actual data from your database
        $onlineUsers = \App\Models\User::where('last_activity', '>', now()->subMinutes(5))
            ->where('id', '!=', auth()->id())
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->nama_lengkap,
                    'avatar' => $user->profile && $user->profile->foto 
                        ? asset('storage/profiles/' . $user->profile->foto) 
                        : null,
                    'role' => $user->role,
                    'last_activity' => $user->last_activity->diffForHumans()
                ];
            });
            
        return response()->json($onlineUsers);
    });

// Get chat messages with a user
    Route::get('/chat/messages/{userId}', function (Request $request, $userId) {
        // Replace with actual data from your database
        $messages = \App\Models\ChatMessage::where(function($query) use ($userId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', auth()->id());
            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sent' => $message->sender_id === auth()->id(),
                    'sender' => $message->sender->nama_lengkap,
                    'avatar' => $message->sender->profile && $message->sender->profile->foto 
                        ? asset('storage/profiles/' . $message->sender->profile->foto) 
                        : null,
                    'time' => $message->created_at->format('H:i')
                ];
            });
            
        return response()->json($messages);
    });
    
    // Send a message
    Route::post('/chat/send', function (Request $request) {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000'
        ]);
        
        // Create the message
        $message = \App\Models\ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content
        ]);
        
        // You might want to broadcast this event using WebSocket or Pusher
        // Broadcast(new NewMessage($message))->toOthers();
        
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'sent' => true,
                'sender' => auth()->user()->nama_lengkap,
                'avatar' => auth()->user()->profile && auth()->user()->profile->foto 
                    ? asset('storage/profiles/' . auth()->user()->profile->foto) 
                    : null,
                'time' => $message->created_at->format('H:i')
            ]
        ]);
    });
    
    // Get unread messages count
    Route::get('/chat/unread', function (Request $request) {
        $count = \App\Models\ChatMessage::where('receiver_id', auth()->id())
            ->where('read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    });
    
    // Mark messages as read
    Route::post('/chat/read/{userId}', function (Request $request, $userId) {
        \App\Models\ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);
            
        return response()->json(['success' => true]);
    });


});