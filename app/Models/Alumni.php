<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';
    protected $fillable = ['user_id', 'nama', 'nim','no_hp', 'alamat', 'jenis_kelamin', 'prodi', 'tahun_lulus'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
