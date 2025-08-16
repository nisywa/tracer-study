<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePertanyaan extends Model
{
    protected $table = 'template_pertanyaan';
    protected $fillable = ['id_survey', 'pertanyaan', 'tipe', 'urutan', 'blok', 'deskripsi_pertanyaan', 'visualisasi', 'block_id'];

    static function getTemplatePertanyaan($id_survey){
        $query=self::select('template_pertanyaan.*')
        ->join('survey','survey.id','=','template_pertanyaan.id_survey')
        ->join('template_jawaban','template_pertanyaan.id','=','template_jawaban.id_template_pertanyaan')
        ->selectRaw('GROUP_CONCAT(pilihan_jawaban) as template_jawaban')
        ->where('template_pertanyaan.id_survey',$id_survey)
        ->groupBy('template_pertanyaan.id');
        return $query->paginate(10, ['*'], 'question_page');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'id_survey');
    }
    public function template_jawaban()
    {
        return $this->hasMany(TemplateJawaban::class, 'id_template_pertanyaan');
    }

    public function survey_user_jawaban()
    {
        return $this->hasMany(SurveyUserJawaban::class, 'id_template_pertanyaan');
    }

    /**
     * Get the block this question belongs to
     */
    public function block()
    {
        return $this->belongsTo(SurveyBlock::class, 'block_id');
    }

    /**
     * Get branch rules that originate from this question
     */
    public function branchRules()
    {
        return $this->hasMany(SurveyBranchRule::class, 'source_question_id')->orderBy('priority');
    }

    /**
     * Scope to filter by block
     */
    public function scopeInBlock($query, $blockId)
    {
        return $query->where('block_id', $blockId);
    }

    /**
     * Get the next question in the same block
     */
    public function getNextInBlock()
    {
        return self::where('id_survey', $this->id_survey)
                   ->where('block_id', $this->block_id)
                   ->where('urutan', '>', $this->urutan)
                   ->orderBy('urutan')
                   ->first();
    }
}


