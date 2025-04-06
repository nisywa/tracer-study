<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Atasan;
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
        // seed admin role
        $adminRole = Role::create([
            'name' => 'admin'
        ]);
        //  seed user role
        $alumniRole = Role::create([
            'name' => 'alumni'
        ]);
        $userAlumni = User::create(
            [
                'name' => 'Alumni',
                'email' => 'alumni@alumni.com',
                'password' => bcrypt('123123123')
            ]
        );

        $userAlumni->assignRole($alumniRole);
        // seed alumni table
        Alumni::create([
            'user_id' => $userAlumni->id,
            'nama' => 'Alumni',
            'no_hp' => '08123456789',
            'nip' => '123456789',
            'jabatan' => 'Staff',
            'satuan_kerja' => 'Divisi IT',
            'unit_kerja' => 'Pengembangan',
            'kepala_bps' => 'Kepala BPS',
            'email'=> 'alumni@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $atasanRole = Role::create([
            'name' => 'atasan'
        ]);
        $userAtasan = User::create(
            [
                'name' => 'Atasan',
                'email' => 'atasan@atasan.com',
                'password' => bcrypt('123123123')
            ]
        );
        Atasan::create([
            'user_id' => $userAtasan->id,
            'nama' => 'Atasan',
            'jabatan' => 'Manager',
            'satuan_kerja' => 'Divisi IT',
            'unit_kerja' => 'Pengembangan',
            'alamat_kantor' => 'Jl. Atasan No. 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userAtasan->assignRole($atasanRole);
        $userAlumni = User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123123123')
            ]
        );

        $userAlumni->assignRole($adminRole);
    }
}