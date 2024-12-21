<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'name' => 'admin'
        ]);
        $userOwner = User::create(['name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123123123')
        ]);
        $userOwner->assignRole($adminRole);
    }
}