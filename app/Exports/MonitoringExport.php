<?php

namespace App\Exports;

use App\Models\Survey;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use App\Models\SurveyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonitoringExport implements FromCollection, WithHeadings
{protected $surveyId;
    protected $questions;
    protected $users;
    protected $answers; 
    /**
     * @return \Illuminate\Support\Collection
     */

    
    public function __construct($surveyId)
    {
        $this->surveyId = $surveyId;

    }
    
    public function prepareData(){
        $survey=Survey::findOrFail($this->surveyId);
        $this->questions=TemplatePertanyaan::where('id_survey', $this->surveyId)->get();
        $this->users = SurveyUser::getUser($this->surveyId);
        $this->answers= [];
        $answers=SurveyUserJawaban::whereIn('survey_user_id', $this->users->pluck(id))->with(
            'surveyUser','template_pertanyaan'
        )->get();
        foreach ($answers as $answer) {
            $this->answers[$answer->survey_user_id][$answer->template_pertanyaan_id] = $answer->jawaban;
        }
    }

    public function headings(): array
    {
        $headings= [
            'ID',
            'Nama',
            'Email',
            'Status', 
            'Tanggal Mengisi',
        ];
        foreach ($this->questions as $question) {
            $prefix = $question->blok ? "[$question->blok] " : "";
            $headings[] =$prefix.$question->pertanyaan;
            
        }
        return $headings;
    }
     public function collection(){
        $data=new Collection();
        foreach($this->users as $user){
            $row = [
                $user->id,
                $user->nama,
                $user->email,
                $user->status? 'Sudah Mengisi' : 'Belum Mengisi',
                $user->tanggal_mengisi,
            ];
            foreach ($this->questions as $question) {
                $row[] = isset($this->answers[$user->id][$question->id]) ? $this->answers[$user->id][$question->id] : '';
            }
            
            $data->push($row);
        }
        return $data;
       


    }

    /**
     * @return array
     */
    
}