<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleOnlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder only creates the roles without demo users.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating roles only...');
        
        $roles = ['admin', 'alumni', 'atasan', 'supervisor'];
        
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $this->command->info("✅ Role '{$roleName}' created/found");
        }
        
        $this->command->info('');
        $this->command->info('🎉 All roles have been created successfully!');
        $this->command->info('Available roles: admin, alumni, atasan, supervisor');
    }
}
