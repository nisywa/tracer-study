<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';
    protected $fillable = ['nama', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi'];
}
