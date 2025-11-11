<?php

namespace App\Listeners;

use App\Models\Activity;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        Activity::create([
            'user_id' => $event->user->id,
            'description' => 'keluar dari sistem',
        ]);
    }
}