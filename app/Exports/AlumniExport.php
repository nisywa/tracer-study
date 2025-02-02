<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlumniExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Alumni::join('users', 'alumni.user_id', '=', 'users.id')
        ->get(['alumni.id', 'alumni.nim', 'alumni.nama', 'alumni.alamat', 'users.email', 'alumni.jenis_kelamin', 'alumni.no_hp', 'alumni.prodi', 'alumni.tahun_lulus', 'alumni.created_at', 'alumni.updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'NIM',
            'Nama',
            'Alamat',
            'Email',
            'Jenis Kelamin',
            'Nomor HP',
            'Prodi',
            'Tahun Lulus',
            'Created At',
            'Updated At',
        ];
    }
}