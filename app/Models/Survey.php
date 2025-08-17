<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'type_survei',
        'deskripsi',
        'created_by'
    ];

    public function questions()
    {
        return $this->hasMany(TemplatePertanyaan::class, 'id_survey');
    }

    public function surveyUsers()
    {
        return $this->hasMany(SurveyUser::class, 'survey_id');
    }

    /**
     * Get blocks for this survey
     */
    public function blocks()
    {
        return $this->hasMany(SurveyBlock::class, 'survey_id')->orderBy('urutan');
    }

    /**
     * Get branch rules for this survey
     */
    public function branchRules()
    {
        return $this->hasMany(SurveyBranchRule::class, 'survey_id');
    }

    /**
     * Get survey blocks for this survey
     */
    public function surveyBlocks()
    {
        return $this->hasMany(SurveyBlock::class, 'survey_id')->orderBy('urutan');
    }
}
