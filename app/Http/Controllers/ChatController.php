<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Menampilkan halaman chat utama
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua chat yang melibatkan user ini
        $chats = Chat::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
            
        return view('chat.index', compact('chats'));
    }
    
    /**
     * Mendapatkan detail chat dengan user tertentu
     */
    public function getChat($userId)
    {
        $currentUserId = Auth::id();
        
        // Find or create chat
        $chat = Chat::where(function($query) use ($currentUserId, $userId) {
            $query->where('user1_id', $currentUserId)->where('user2_id', $userId);
        })
        ->orWhere(function($query) use ($currentUserId, $userId) {
            $query->where('user1_id', $userId)->where('user2_id', $currentUserId);
        })
        ->first();
        
        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $currentUserId,
                'user2_id' => $userId
            ]);
        }
        
        // Get messages
        $messages = ChatMessage::where('chat_id', $chat->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark messages as read
        ChatMessage::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json([
            'chat' => $chat,
            'messages' => $messages
        ]);
    }
    
    /**
     * Mengirim pesan baru
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);
        
        $senderId = Auth::id();
        $receiverId = $request->receiver_id;
        
        // Find or create chat
        $chat = Chat::where(function($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $senderId)->where('user2_id', $receiverId);
        })
        ->orWhere(function($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $receiverId)->where('user2_id', $senderId);
        })
        ->first();
        
        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $senderId,
                'user2_id' => $receiverId
            ]);
        }
        
        // Create message
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $senderId,
            'message' => $request->message
        ]);
        
        // Update last_message_at
        $chat->update(['last_message_at' => now()]);
        
        // Load relationships
        $message->load('sender');
        
        // Broadcast event (optional, if you want real-time chat)
        // broadcast(new NewMessageSent($message))->toOthers();
        
        return response()->json([
            'success' => true, 
            'message' => $message
        ]);
    }
    
    /**
     * Mendapatkan daftar user yang online
     */
    public function getOnlineUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('last_activity', '>', now()->subMinutes(5))
            ->select('id', 'nama_lengkap', 'jabatan', 'last_activity')
            ->get()
            ->map(function ($user) {
                $user->last_activity_formatted = $user->last_activity->diffForHumans();
                $user->initials = collect(explode(' ', $user->nama_lengkap))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');
                return $user;
            });
            
        return response()->json($users);
    }
    
    /**
     * Mendapatkan jumlah pesan yang belum dibaca
     */
    public function getUnreadCount()
    {
        $userId = Auth::id();
        $count = ChatMessage::whereHas('chat', function($query) use ($userId) {
            $query->where('user1_id', $userId)->orWhere('user2_id', $userId);
        })
        ->where('sender_id', '!=', $userId)
        ->where('is_read', false)
        ->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Menandai pesan sebagai sudah dibaca
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:chat_messages,id'
        ]);
        
        $message = ChatMessage::findOrFail($request->message_id);
        
        // Pastikan user adalah penerima pesan
        $chat = $message->chat;
        if ($chat->user1_id != Auth::id() && $chat->user2_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Pastikan user bukan pengirim pesan
        if ($message->sender_id == Auth::id()) {
            return response()->json(['error' => 'Cannot mark own message as read'], 403);
        }
        
        $message->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Menghapus pesan
     */
    public function deleteMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:chat_messages,id'
        ]);
        
        $message = ChatMessage::findOrFail($request->message_id);
        
        // Pastikan user adalah pengirim pesan
        if ($message->sender_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Menghapus chat
     */
    public function deleteChat(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);
        
        $chat = Chat::findOrFail($request->chat_id);
        
        // Pastikan user terlibat dalam chat
        if ($chat->user1_id != Auth::id() && $chat->user2_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Hapus semua pesan dalam chat
        ChatMessage::where('chat_id', $chat->id)->delete();
        
        // Hapus chat
        $chat->delete();
        
        return response()->json(['success' => true]);
    }
}