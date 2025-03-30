<?php

namespace App\Http\Controllers;
use App\Models\survey;
// use App\Models\SurveyUser;
// use App\Models\TemplatePertanyaan;
use App\Models\TemplatePertanyaan;
use App\Models\SurveyUserJawaban;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $survey = Survey::paginate(10);
        foreach ($survey as $srvy) {
           $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-y");
           $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-y");
           if($srvy->tanggal_selesai >= now()){
            $srvy->status="Aktif";
           }else{
            $srvy->status="Selesai";
           }
        }

        return view('admin.views.monitoring.index',compact('survey'));
    }

    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $template_questions = TemplatePertanyaan::with(['survey_user_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->orderBy('urutan')
            ->get();

        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-y");
        if ($survey->tanggal_selesai >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }
        
        return view('admin.views.monitoring.details', [
            'survey' => $survey,
            'template_questions' => $template_questions
        ]);
        
    }
}
