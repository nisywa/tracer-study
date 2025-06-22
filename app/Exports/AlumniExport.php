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
        ->get(['alumni.id', 'alumni.nama', 'alumni.nip', 'users.email', 'alumni.jabatan','alumni.satuan_kerja','alumni.unit_kerja', 'alumni.no_hp', 'alumni.nip_kepala_bps', 'alumni.tanggal_lahir','alumni.tahun_lulus','alumni.created_at', 'alumni.updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NIP',
            'Email',
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'No HP',
            'Kepala BPS',
            'NIP Kepala BPS',
            'Tanggal Lahir',
            'Tahun Lulus',
            'Created At',
            'Updated At',
        ];
    }
}