<?php

namespace App\Exports;

use App\Models\Atasan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AtasanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Atasan::join('users', 'atasan.user_id', '=', 'users.id')
        ->get(['atasan.id', 'atasan.nama', 'atasan.jabatan', 'atasan.satuan_kerja', 'users.email', 'atasan.unit_kerja', 'atasan.alamat_kantor', 'atasan.no_hp', 'atasan.created_at', 'atasan.updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Jabatan',
            'Satuan Kerja',
            'Email',
            'Unit Kerja',
            'Alamat Kantor',
            'No HP',
            'Created At',
            'Updated At',
        ];
    }

}
