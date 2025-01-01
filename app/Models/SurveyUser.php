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
}
