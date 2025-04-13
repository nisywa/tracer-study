<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUserJawaban extends Model
{
    protected $table = 'survey_user_jawaban';
    protected $fillable = ['survey_user_id', 'jawaban', 'template_pertanyaan_id'];

    public function surveyUser()
    {
        return $this->belongsTo(SurveyUser::class);
    }
    // Add the missing relationship
    public function template_pertanyaan()
    {
        return $this->belongsTo(TemplatePertanyaan::class, 'template_pertanyaan_id');
    }

    // Add relationship for template_jawaban as well
    public function template_jawaban()
    {
        return $this->belongsTo(TemplateJawaban::class, 'template_jawaban_id');
    }
}
