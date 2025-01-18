<?php

namespace App\Imports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\ToModel;

class AlumniImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => bcrypt('default_password'), // You might want to generate a random password or handle this differently
        ]);

        $user->save();

        $alumni = new Alumni([
            'nim' => $row['nim'],
            'nama' => $row['nama'],
            'alamat' => $row['alamat'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'no_hp' => $row['no_hp'],
            'prodi' => $row['prodi'],
            'tahun_lulus' => $row['tahun_lulus'],
            'user_id' => $user->id, // Assuming Alumni has a user_id field
        ]);

        $alumni->save();

        return $alumni;
    }
}
