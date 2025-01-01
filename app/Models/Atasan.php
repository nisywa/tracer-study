<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atasan extends Model
{
    protected $fillable = ['user_id', 'nama', 'perusahaan', 'jabatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
