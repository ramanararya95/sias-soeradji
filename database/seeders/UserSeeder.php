<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah user admin sudah ada
        $admin = User::where('username', 'admin')->first();
        
        if (!$admin) {
            User::create([
                'username' => 'admin',
                'email' => 'admin@sias.com',
                'password' => Hash::make('password'),
                'nama_lengkap' => 'Administrator SIAS',
                'jabatan' => 'Administrator',
                'role' => 'admin',
                'kode_petugas' => 'ADM001',
                'status' => 'aktif',
            ]);
        }
    }
}