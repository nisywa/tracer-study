<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SurveyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::whereHas('surveyUsers', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();

        $alumni = Auth::user();

        return view('user.views.index', [
            'surveys' => $surveys,
            'alumni' => $alumni
        ]);
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

    public function survey()
    {
        return view('user.views.survey');
    }

    public function surveyUserPertanyaan()
    {
        $surveyUser = SurveyUser::where('user_id', Auth::id())->where('status', 0)->first();
        if (!$surveyUser) {
            return redirect()->back()->with('error', 'Survey tidak ditemukan');
        }
        $survey = Survey::where('id', $surveyUser->survey_id)->first();
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-y");
        $surveyUserPertanyaan = TemplatePertanyaan::with(['template_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $surveyUser->survey_id)
            ->orderBy('urutan')
            ->get()
            ->groupBy('blok')
            ->map(function ($questions) {
                return $questions->values(); // Reset array keys for each group
            });

        return view('user.views.survey', [
            'survey' => $survey,
            'surveyPertanyaan' => $surveyUserPertanyaan
        ]);
    }

    public function saveSurvey(Request $request)
    {
        try {
            // Get current user's survey assignment
            $surveyUser = SurveyUser::where('user_id', Auth::id())->first();
            if (!$surveyUser) {
                return redirect()->back()->with('error', 'Survey tidak ditemukan');
            }

            // Process each question response
            foreach ($request->except('_token') as $questionId => $answer) {
                // Check if the answer is a file upload
                if ($request->hasFile($questionId)) {
                    $file = $request->file($questionId);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('survey_uploads', $fileName, 'public');
                    $answer = $path;
                }
                // Handle checkbox arrays
                elseif (is_array($answer)) {
                    $answer = implode(',', $answer);
                }

                // Create or update response
                SurveyUserJawaban::updateOrCreate(
                    [
                        'survey_user_id' => $surveyUser->id,
                        'template_pertanyaan_id' => $questionId
                    ],
                    [
                        'jawaban' => $answer
                    ]
                );
            }

            // update status survey user
            $surveyUser->status = 1;
            $surveyUser->save();


            return redirect()->back()->with('success', 'Jawaban survey berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
