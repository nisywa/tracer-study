<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'alamat',
        'jenis_kelamin',
        'no_hp',
        'prodi',
        'tahun_lulus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
