<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role supervisor jika belum ada
        $supervisorRole = Role::firstOrCreate([
            'name' => 'supervisor'
        ]);
        
        // Hapus user supervisor lama jika ada
        User::where('email', 'supervisor@example.com')->delete();
        
        // Buat user supervisor baru
        $userSupervisor = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('123123123'),
            'role' => 'supervisor', 
            'created_at' => now(),
            'updated_at' => now(),
            //'email_verified_at' => now(),
        ]);
        
        // Assign role supervisor
        $userSupervisor->assignRole($supervisorRole);
        
        echo "✅ User supervisor berhasil dibuat:\n";
        echo "Email: supervisor@example.com\n";
        echo "Password: 123123123\n";
        echo "Role: " . $userSupervisor->getRoleNames()->first() . "\n";
    }
}
