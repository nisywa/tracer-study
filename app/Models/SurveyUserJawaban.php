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
}
