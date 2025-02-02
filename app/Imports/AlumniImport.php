<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumniImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Create the user
            $user = User::firstOrCreate(
                ['email' => $row['email']], // Check for duplicate email
                [
                    'name' => $row['nama'],
                    'password' => bcrypt('default_password'), // Handle password securely
                ]
            );

            // Create the alumni record
            $alumni = Alumni::firstOrCreate(['nim'=> $row['nim']],[
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'nim' => $row['nim'],
                'no_hp' => $row['no_hp'],
                'alamat' => $row['alamat'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'prodi' => $row['prodi'],
                'tahun_lulus' => $row['tahun_lulus'],
            ]);
            return $alumni;
        } catch (QueryException $e) {
            // Log or handle the error
            Log::error("message: {$e->getMessage()}");
            throw $e;
        }
    }


}
