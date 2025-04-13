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
        'kepala_bps',
        'nip_kepala_bps',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
