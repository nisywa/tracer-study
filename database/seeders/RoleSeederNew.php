<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Atasan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeederNew extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating roles and demo users...');
        
        // Create all roles first
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $alumniRole = Role::firstOrCreate(['name' => 'alumni']);
        $atasanRole = Role::firstOrCreate(['name' => 'atasan']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        
        $this->command->info('✅ Roles created: admin, alumni, atasan, supervisor');

        // Create Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123123123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Create Alumni User
        $alumniUser = User::firstOrCreate(
            ['email' => 'alumni@alumni.com'],
            [
                'name' => 'Alumni User',
                'email' => 'alumni@alumni.com',
                'password' => Hash::make('123123123'),
                'role' => 'alumni',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $alumniUser->assignRole($alumniRole);

        // Create Alumni profile
        Alumni::firstOrCreate(
            ['user_id' => $alumniUser->id],
            [
                'user_id' => $alumniUser->id,
                'nama' => 'Alumni Demo',
                'no_hp' => '08123456789',
                'nip' => '123456789',
                'jabatan' => 'Staff',
                'satuan_kerja' => 'Divisi IT',
                'unit_kerja' => 'Pengembangan',
                'email' => 'alumni@gmail.com',
                'tanggal_lahir' => '1990-01-01',
                'nip_kepala_bps' => '123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Atasan User
        $atasanUser = User::firstOrCreate(
            ['email' => 'atasan@atasan.com'],
            [
                'name' => 'Atasan User',
                'email' => 'atasan@atasan.com',
                'password' => Hash::make('123123123'),
                'role' => 'atasan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $atasanUser->assignRole($atasanRole);

        // Create Atasan profile
        Atasan::firstOrCreate(
            ['user_id' => $atasanUser->id],
            [
                'user_id' => $atasanUser->id,
                'nama' => 'Atasan Demo',
                'jabatan' => 'Manager',
                'satuan_kerja' => 'Divisi IT',
                'unit_kerja' => 'Pengembangan',
                'email' => 'atasan@gmail.com',
                'no_hp' => '08123456789',
                'nip' => '987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Supervisor User
        $supervisorUser = User::firstOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor User',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('123123123'),
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $supervisorUser->assignRole($supervisorRole);

        $this->command->info('✅ Demo users created with credentials:');
        $this->command->info('👑 Admin: admin@admin.com / 123123123');
        $this->command->info('🎓 Alumni: alumni@alumni.com / 123123123');
        $this->command->info('👔 Atasan: atasan@atasan.com / 123123123');
        $this->command->info('📊 Supervisor: supervisor@example.com / 123123123');
        $this->command->info('');
        $this->command->info('🎉 All roles and demo users have been created successfully!');
    }
}
