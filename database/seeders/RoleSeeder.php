<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Membuat role
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        // Bisa juga membuat permission jika diperlukan
        // $permissionCreatePost = Permission::create(['name' => 'create post']);
        // $permissionEditPost = Permission::create(['name' => 'edit post']);

        // Assign permission ke role
        // $roleAdmin->givePermissionTo(['create post', 'edit post']);
    }
}