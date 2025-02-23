<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateJawaban extends Model
{
    protected $table = 'template_jawaban';
    protected $fillable = ['id_template_pertanyaan', 'pilihan_jawaban', 'urutan'];

    public function template_pertanyaan()
    {
        return $this->belongsTo(TemplatePertanyaan::class, 'id_template_pertanyaan');
    }
}
