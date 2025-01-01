<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $fillable = ['user_id', 'nama', 'nim', 'jurusan', 'angkatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
