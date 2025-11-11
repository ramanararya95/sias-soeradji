<?php

namespace App\Listeners;

use App\Models\CacheKey;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearCacheOnModelChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Clear relevant cache based on model type
        if (method_exists($event, 'getModel')) {
            $model = $event->getModel();
            
            // Clear dashboard stats cache for all users
            if (in_array(class_basename($model), [
                'ArsipAktif', 'ArsipInaktif', 'SuratTugas', 
                'BeritaPemindahan', 'BeritaPemusnahan', 'BeritaAlihmedia'
            ])) {
                // This is a simplified approach, in production you might want to be more selective
                Cache::flush();
            }
        }
    }
}