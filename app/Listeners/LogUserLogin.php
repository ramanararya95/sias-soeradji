<?php

namespace App\Listeners;

use App\Models\Activity;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    public function handle(Login $event)
    {
        Activity::create([
            'user_id' => $event->user->id,
            'description' => 'masuk ke sistem',
        ]);
    }
}