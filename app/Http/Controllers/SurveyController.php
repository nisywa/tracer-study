<?php

namespace App\Http\Controllers;

use App\Models\survey;
use App\Models\SurveyUser;
use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class SurveyController extends Controller
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
            if ($srvy->tanggal_selesai >= now()) {
                $srvy->status = "Aktif";
            } else {
                $srvy->status = "Selesai";
            }
        }
        return view('admin.views.survey.index', compact('survey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $survey = Survey::paginate(10);
        return view('admin.views.survey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'type_survei' => 'required|string',
            'deskripsi' => 'required|string|max:255',
        ]);

        Survey::create($validatedData);
        return redirect()->route('admin.survey.index')->with('success', 'Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("m/d/yy");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("m/d/yy");
        if ($survey->tanggal_selesai >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }

        return view('admin.views.survey.edit', ['survey' => $survey]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'type_survei' => 'required|string',
            'deskripsi' => 'required|string|max:255',
        ]);
        $survey = Survey::findOrFail($id);
        $survey->update($validatedData);

        return redirect()->route('admin.survey.index')->with('success', 'Survey updated successfully');
    }

    public function add_question($id)
    {
        $survey = Survey::findOrFail($id);

        // Get template questions with their options ordered by urutan
        $template_questions = TemplatePertanyaan::with(['template_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $id)
            ->orderBy('urutan')
            ->get();

        return view('admin.views.survey.add_question', [
            'survey' => $survey,
            'template_questions' => $template_questions
        ]);
    }

    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $survey_user = SurveyUser::getSurveyUser($id);
        $template_pertanyaan = TemplatePertanyaan::getTemplatePertanyaan($id);
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-y");
        if ($survey->tanggal_selesai >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }
        return view('admin.views.survey.details', ['survey' => $survey, 'survey_user' => $survey_user, 'template_pertanyaan' => $template_pertanyaan]);
    }
    public function create_question2(Request $request, TemplatePertanyaan $template_pertanyaan)
    {
        dd($request->all());
        exit();
        $template = $template_pertanyaan->create([
            'id_survey' => request('id_survey'),
            'pertanyaan' => request('pertanyaan'),
            'tipe' => request('tipe')
        ]);

        return redirect()->back()->with('success', 'Question added successfully');
    }
    public function create_question(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

            $survey_id = $request->input('survey_id');

            // Delete existing questions and their options
            $existingQuestions = TemplatePertanyaan::where('id_survey', $survey_id)->get();
            foreach ($existingQuestions as $question) {
                TemplateJawaban::where('id_template_pertanyaan', $question->id)->delete();
            }
            TemplatePertanyaan::where('id_survey', $survey_id)->delete();

            // Create new questions
            $questions = $request->input('questions', []);

            foreach ($questions as $questionData) {
                $question = TemplatePertanyaan::create([
                    'id_survey' => $survey_id,
                    'pertanyaan' => $questionData['question'],
                    'deskripsi_pertanyaan' => $questionData['description'] ?? null,
                    'blok' => $questionData['blok'] ?? null,
                    'tipe' => $questionData['type'],
                    'urutan' => $questionData['order'],
                ]);

                if (
                    in_array($questionData['type'], ['radio', 'checkbox', 'select'])
                    && !empty($questionData['options'])
                ) {
                    foreach ($questionData['options'] as $option) {
                        TemplateJawaban::create([
                            'id_template_pertanyaan' => $question->id,
                            'pilihan_jawaban' => $option['text'],
                            'urutan' => $option['order'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Template pertanyaan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(survey $survey)
    {
        //
    }

    public function duplicate($id)
    {

    try {
        DB::beginTransaction();
        
        // 1. Find the original survey
        $originalSurvey = Survey::findOrFail($id);

        // 2. Clone the survey
        $newSurvey = $originalSurvey->replicate();
        $newSurvey->nama = $originalSurvey->nama . ' (Copy)';
        $newSurvey->created_at = now();
        $newSurvey->updated_at = now();
        $newSurvey->save();
        
        // 3. Get all template questions of the original survey
        $originalTemplateQuestions = TemplatePertanyaan::where('id_survey', $originalSurvey->id)
            ->orderBy('urutan')
            ->get();
        
        // 4. Clone each template question and its options
        foreach ($originalTemplateQuestions as $originalQuestion) {
            $newQuestion = $originalQuestion->replicate();
            $newQuestion->id_survey = $newSurvey->id;
            $newQuestion->created_at = now();
            $newQuestion->updated_at = now();
            $newQuestion->save();
            
            // 5. Get all template answers for this question
            $originalAnswers = TemplateJawaban::where('id_template_pertanyaan', $originalQuestion->id)
                ->orderBy('urutan')
                ->get();
            
            // 6. Clone each template answer
            foreach ($originalAnswers as $originalAnswer) {
                $newAnswer = $originalAnswer->replicate();
                $newAnswer->id_template_pertanyaan = $newQuestion->id;
                $newAnswer->created_at = now();
                $newAnswer->updated_at = now();
                $newAnswer->save();
            }
        }
        
        // 7. Get all survey users of the original survey
        $originalSurveyUsers = SurveyUser::where('survey_id', $originalSurvey->id)->whereNull('deleted_at')->get();
        
        // 8. Clone each survey user
        foreach ($originalSurveyUsers as $originalSurveyUser) {
            $newSurveyUser = $originalSurveyUser->replicate();
            $newSurveyUser->survey_id = $newSurvey->id;
            $newSurveyUser->status = '0'; // Reset status for the new survey
            $newSurveyUser->tanggal_mengisi = null;
            $newSurveyUser->created_at = now();
            $newSurveyUser->updated_at = now();
            $newSurveyUser->save();
            
            // Note: We don't copy user answers because the new survey hasn't been filled out yet
        }
        
        DB::commit();
        
        return redirect()->route('admin.survey.index')
            ->with('success', 'Survey has been successfully copied');
            
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        DB::rollback();
        return redirect()->back()
            ->with('error', 'Failed to copy survey: ' . $e->getMessage());
    }

    }
}
