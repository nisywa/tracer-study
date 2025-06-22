<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'email',
        'jabatan',
        'satuan_kerja',
        'unit_kerja',
        'no_hp',
        'nip_kepala_bps',
        'tanggal_lahir',
        'tahun_lulus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCleanNameAttribute()
    {
        $nameParts = explode(',', $this->nama, 2);
        return trim($nameParts[0]);
    }
}
