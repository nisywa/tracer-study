<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePertanyaan extends Model
{
    protected $table = 'template_pertanyaan';
    protected $fillable = ['id_survey', 'pertanyaan', 'tipe', 'urutan', 'blok', 'deskripsi_pertanyaan'];

    static function getTemplatePertanyaan($id_survey){
        $query=self::select('template_pertanyaan.*')
        ->join('survey','survey.id','=','template_pertanyaan.id_survey')
        ->join('template_jawaban','template_pertanyaan.id','=','template_jawaban.id_template_pertanyaan')
        ->selectRaw('GROUP_CONCAT(pilihan_jawaban) as template_jawaban')
        ->groupBy('template_pertanyaan.id');
        return $query->get();
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'id_survey');
    }
    public function template_jawaban()
    {
        return $this->hasMany(TemplateJawaban::class, 'id_template_pertanyaan');
    }
}


