<?php

if (!function_exists('createNotification')) {
    function createNotification($userId, $title, $message, $type = 'info')
    {
        return \App\Models\Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }
}

if (!function_exists('getUnreadNotificationsCount')) {
    function getUnreadNotificationsCount($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        return \App\Models\Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}