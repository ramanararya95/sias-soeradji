<?php

namespace App\Providers;

use App\Listeners\ClearCacheOnModelChange;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LogUserLogin::class,
        ],
        Logout::class => [
            LogUserLogout::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\NotificationEvent' => [
        'App\Listeners\SendNotification',
        ],
        
        // Model events
        'eloquent.created: *' => [
            ClearCacheOnModelChange::class,
        ],
        'eloquent.updated: *' => [
            ClearCacheOnModelChange::class,
        ],
        'eloquent.deleted: *' => [
            ClearCacheOnModelChange::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}