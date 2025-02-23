<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\TemplatePertanyaan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SurveyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyUser $surveyUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyUser $surveyUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyUser $surveyUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyUser $surveyUser)
    {
        //
    }

    public function survey(){
        return view ('user.views.survey');
    }

    public function surveyUserPertanyaan(){
        $surveyUser= SurveyUser::where ('user_id',1)->first();
        $survey=Survey::where('id',$surveyUser->survey_id)->first();
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-y");
        $surveyUserPertanyaan=TemplatePertanyaan::with(['template_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $surveyUser->survey_id)
            ->orderBy('urutan')
            ->get()
            ->groupBy('blok')
            ->map(function ($questions) {
                return $questions->values(); // Reset array keys for each group
            });
        
        return view('user.views.survey',[
            'survey'=>$survey,
            'surveyPertanyaan'=>$surveyUserPertanyaan
        ]);  
    }

    public function saveSurvey(Request $request){
        echo "<pre>";
        print_r($request);
        echo "</pre>";
    }
}
