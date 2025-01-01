<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePertanyaan extends Model
{
    protected $table = 'template_pertanyaan';
    protected $fillable = ['id_survey', 'pertanyaan', 'tipe', 'urutan'];
}
