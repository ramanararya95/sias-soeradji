<?php

// app/Http/Controllers/API/ChatController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\User;

class ChatController extends Controller
{
    public function getChatMessages(Request $request, $userId)
    {
        $currentUser = $request->user();
        
        $messages = ChatMessage::where(function ($query) use ($currentUser, $userId) {
                $query->where('sender_id', $currentUser->id)
                    ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($currentUser, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $currentUser->id);
            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json([
            'messages' => $messages
        ]);
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $currentUser = $request->user();
        $receiverId = $request->receiver_id;
        $messageText = $request->message;
        
        // Create message
        $message = ChatMessage::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiverId,
            'message' => $messageText,
            'read_at' => null
        ]);
        
        // You could also implement real-time notification here using WebSocket or Pusher
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}