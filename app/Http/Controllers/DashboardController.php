<?php

namespace App\Http\Controllers;
use App\Models\Survey;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query=Survey::where('tanggal_mulai', '<=', now())
                     ->where('tanggal_selesai', '>=', now())
                     ->orderBy('created_at', 'desc');

        $surveyAktif = $query->paginate(10)->withQueryString();
        foreach ($surveyAktif as $survey) {
            $totalResponses = $survey->surveyUsers()->count();
            $completedResponses = $survey->surveyUsers()->where('status', true)->count();
            $completionRate = $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0;
            $survey->completionRate = $completionRate;
            $survey->completedResponses = $completedResponses;
            $survey->totalResponses = $totalResponses;
        }

                          
        return view('admin.views.dashboard',compact('surveyAktif'));
    }

}
