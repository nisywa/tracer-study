<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUser extends Model
{
    protected $table = 'survey_user';
    protected $fillable = ['survey_id', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jawaban()
    {
        return $this->hasMany(SurveyUserJawaban::class);
    }

    static function getSurveyUser($id_survey)
    {
        $survey=Survey::where('id',$id_survey)->first();
        if ($survey){
            $query=self::select('a.*')
            ->join('survey','survey.id','=','survey_user.survey_id')
            ->join('users','users.id','=','survey_user.user_id');

            if ($survey->type_survei=='alumni'){
              $query->join('alumni as a' , 'users.id','=','a.user_id');
            } elseif ($survey->type_survei=='atasan'){
              $query->join('atasan as a','users.id','=','a.user_id');  
            }
            $query->where ('survey_user.survey_id',$id_survey);
            return $query->paginate(10, ['*'], 'user_page');
           

        }
        return false;
    }

    static function getUser($id_survey)
    {
        $survey=Survey::where('id',$id_survey)->first();
        if ($survey){
            $query=self::select('a.*,survey_user.*')
            ->join('survey','survey.id','=','survey_user.survey_id')
            ->join('users','users.id','=','survey_user.user_id');

            if ($survey->type_survei=='alumni'){
              $query->join('alumni as a' , 'users.id','=','a.user_id');
            } elseif ($survey->type_survei=='atasan'){
              $query->join('atasan as a','users.id','=','a.user_id');  
            }
            $query->where ('survey_user.survey_id',$id_survey);
            return $query->get();
           

        }
        return false;
    }
}
