<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSetting;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Create profile if not exists
            if (!$user->profile) {
                UserProfile::create([
                    'user_id' => $user->id,
                    'bio' => 'Pengguna SIAS RSUP Soeradji',
                ]);
            }
            
            // Create settings if not exists
            if (!$user->settings) {
                UserSetting::create([
                    'user_id' => $user->id,
                    'theme' => 'light',
                    'email_notifications' => true,
                    'chat_notifications' => true,
                    'language' => 'id',
                ]);
            }
        }
    }
}