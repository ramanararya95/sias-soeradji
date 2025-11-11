<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Models\Notification;

class SendNotification
{
    public function handle(NotificationEvent $event)
    {
        Notification::create([
            'user_id' => $event->userId,
            'title' => $event->title,
            'message' => $event->message,
            'type' => $event->type ?? 'info',
        ]);
    }
}