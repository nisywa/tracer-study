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
            'email'=> 'alumni@gmail.com',
            'tanggal_lahir' => '1990-01-01',
            'tahun_lulus' => '2020',
            'nip_kepala_bps' => '123456789',
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
            'email' => 'atasan@gmail.com',
            'no_hp' => '08123456789',
            'nip' => '123456789',
            // 'alamat_kantor' => 'Jl. Atasan No. 1',
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
        // Tambahkan supervisor role dan user
        // $supervisorRole = Role::firstOrCreate([
        //     'name' => 'supervisor'
        // ]);
        
        // $userSupervisor = User::firstOrCreate(
        //     ['email' => 'supervisor@example.com'],
        //     [
        //         'name' => 'Supervisor',
        //         'email' => 'supervisor@example.com',
        //         'password' => bcrypt('123123123')
        //     ]
        // );
        
        // $userSupervisor->assignRole($supervisorRole);
        
    }
}