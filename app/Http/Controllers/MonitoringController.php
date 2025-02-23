<?php

namespace App\Http\Controllers;
use App\Models\survey;
// use App\Models\SurveyUser;
// use App\Models\TemplatePertanyaan;
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
}
