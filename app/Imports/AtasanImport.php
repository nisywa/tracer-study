<?php

namespace App\Imports;

use App\Models\Atasan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AtasanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try{
        // Create the user
        $user = User::updateOrCreate(
            ['email' => $row['email']], // Check for duplicate email
            [
                'name' => $row['nama'],
                'password' => bcrypt('default_password'), // Handle password securely
            ]
        );

        // Create the atasan record
        $atasan = Atasan::updateOrCreate(['email'=> $row['email']],[
            'user_id' => $user->id,
            'nama' => $row['nama'],
            'jabatan' => $row['jabatan'],
            'satuan_kerja' => $row['satuan_kerja'],
            'unit_kerja' => $row['unit_kerja'],
            'alamat_kantor' => $row['alamat_kantor'],
            'email' => $row['email'],
            'no_hp' => $row['no_hp'],
        ]);
        return $atasan;
    } catch (QueryException $e) {
        // Log or handle the error
        Log::error("message: {$e->getMessage()}");
        throw $e;
    }
  }
}
