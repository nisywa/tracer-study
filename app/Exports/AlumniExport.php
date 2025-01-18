<?php
// filepath: /Users/miftahulhidayati/php-docker-dev/web-src/tracer-study/app/Exports/AlumniExport.php
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
            ->get(['alumni.id', 'alumni.nim', 'alumni.nama', 'alumni.alamat', 'alumni.jenis_kelamin', 'alumni.no_hp', 'alumni.prodi', 'alumni.tahun_lulus', 'users.email as user_email', 'alumni.created_at', 'alumni.updated_at']);
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
            'Jenis Kelamin',
            'Nomor HP',
            'Prodi',
            'Tahun Lulus',
            'User.Email',
            'Created At',
            'Updated At',
        ];
    }
}
