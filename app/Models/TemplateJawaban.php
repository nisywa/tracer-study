<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateJawaban extends Model
{
    protected $table = 'template_jawaban';
    protected $fillable = ['id_template_pertanyaan', 'jawaban', 'urutan'];
}
