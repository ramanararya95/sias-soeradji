<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
        public function boot()
    {
        // Share user variable to all views
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        // Tambahkan kode ini
        Blade::if('role', function (string|array $roles) {
            if (!auth()->check()) {
                return false;
            }
            
            $userRole = auth()->user()->role;
            $rolesToCheck = is_array($roles) ? $roles : [$roles];
            
            return in_array($userRole, $rolesToCheck);
        });

        // TAMBAHKAN DIRECTIVE BARU INI
        Blade::if('hasprofile', function ($field) {
            if (!auth()->check()) {
                return false;
            }
            
            // Pastikan relasi profile sudah dimuat untuk menghindari query N+1
            $user = auth()->user();
            if (!$user->relationLoaded('profile')) {
                $user->load('profile');
            }
            
            return $user->profile && !empty($user->profile->$field);
        });
    }

}