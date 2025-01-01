<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUserJawaban extends Model
{
    protected $fillable = ['survey_user_id', 'jawaban', 'template_pertanyaan_id'];

    public function surveyUser()
    {
        return $this->belongsTo(SurveyUser::class);
    }
}
