<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atasan extends Model
{
    protected $table = 'atasan';
    protected $fillable = ['user_id', 'nama', 'jabatan', 'satuan_kerja', 'unit_kerja', 'alamat_kantor','email','no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
