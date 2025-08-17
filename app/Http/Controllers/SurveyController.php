<?php

namespace App\Http\Controllers;

use App\Models\survey;
use App\Models\SurveyUser;
use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlumniImport;
use App\Imports\AtasanImport;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query=Survey::query();
        
        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('type_survei', 'like','%'. $searchTerm . '%');
            });
        }

        $survey = $query->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->withQueryString();
        ///

        foreach ($survey as $srvy) {
            $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-Y");
            $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-Y");

            if (Carbon::parse($srvy->tanggal_selesai) >= now()) {
                $srvy->status = "Aktif";
            } else {
                $srvy->status = "Selesai";
            }
        }

        //search not fix
        // $query = Survey::query();
        
        // if ($request->has('search')) {
        //     $searchTerm = '%' . $request->search . '%';
        //     $query->where(function($q) use ($searchTerm) {
        //         $q->where('nama', 'ilike', $searchTerm)
        //           ->orWhere('type_survei', 'ilike', $searchTerm);
        //     });
        // }

        // $surveys = $query->orderBy('created_at', 'desc')
        //                 ->paginate(10)
        //                 ->withQueryString();
        
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
            'deskripsi' => 'nullable|string|max:255',
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
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("Y-m-d");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("Y-m-d");
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
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
            'deskripsi' => 'nullable|string|max:255',
        ]);
        $survey = Survey::findOrFail($id);
        $survey->update($validatedData);

        return redirect()->route('admin.survey.index')->with('success', 'Survey updated successfully');
    }

    public function form_builder($id)
    {
        $survey = Survey::findOrFail($id);

        // Get existing questions with their template answers
        $questions = TemplatePertanyaan::with(['template_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $id)
            ->orderBy('urutan')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'pertanyaan' => $question->pertanyaan,
                    'deskripsi_pertanyaan' => $question->deskripsi_pertanyaan,
                    'tipe' => $question->tipe_jawaban,
                    'block_id' => $question->id_blok ?? '',
                    'required' => $question->wajib_diisi == 1,
                    'visualisasi' => $question->visualisasi ?? '',
                    'options' => $question->template_jawaban->map(function ($jawaban) {
                        return ['text' => $jawaban->pilihan_jawaban];
                    })->toArray()
                ];
            });

        // If no questions exist, provide a sample question for better UX
        if ($questions->isEmpty()) {
            $questions = collect([
                [
                    'id' => 'sample-1',
                    'pertanyaan' => 'Nama Lengkap',
                    'deskripsi_pertanyaan' => 'Silakan masukkan nama lengkap Anda',
                    'tipe' => 'text',
                    'block_id' => '',
                    'required' => true,
                    'visualisasi' => '',
                    'options' => []
                ],
                [
                    'id' => 'sample-2',
                    'pertanyaan' => 'Apakah Anda sudah bekerja?',
                    'deskripsi_pertanyaan' => 'Pilih status pekerjaan Anda saat ini',
                    'tipe' => 'radio',
                    'block_id' => '',
                    'required' => true,
                    'visualisasi' => 'pie',
                    'options' => [
                        ['text' => 'Sudah bekerja'],
                        ['text' => 'Belum bekerja'],
                        ['text' => 'Sedang mencari kerja']
                    ]
                ]
            ]);
        }

        // Sample blocks for demonstration (you can create Block model later)
        $blocks = collect([
            [
                'id' => 1,
                'survey_id' => $id,
                'kode' => 'A',
                'nama' => 'Basic Information',
                'deskripsi' => 'Basic demographic and contact information',
                'urutan' => 1,
                'is_terminal' => false
            ],
            [
                'id' => 2,
                'survey_id' => $id,
                'kode' => 'B',
                'nama' => 'Employment Status',
                'deskripsi' => 'Current employment status and job information',
                'urutan' => 2,
                'is_terminal' => false
            ]
        ]);

        // Sample branch rules (you can create BranchRule model later)
        $branchRules = collect([]);

        return view('admin.views.survey.form-builder', [
            'survey' => $survey,
            'questions' => $questions,
            'blocks' => $blocks,
            'branchRules' => $branchRules
        ]);
    }

    public function details($id)
    {
        
        $survey = Survey::findOrFail($id);
        $survey_user = SurveyUser::getSurveyUser($id);
        $template_pertanyaan = TemplatePertanyaan::getTemplatePertanyaan($id);
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }
        return view('admin.views.survey.details', ['survey' => $survey, 'survey_user' => $survey_user, 'template_pertanyaan' => $template_pertanyaan]);
    }

    public function create_question(Request $request)
    {
        try {
            DB::beginTransaction();

            $survey_id = $request->input('survey_id');
            Log::info('Creating questions for survey: ' . $survey_id);
            Log::info('Request data:', $request->all());

            // Handle both old format and new form-builder format
            $questions = $request->input('questions', []);
            
            // If it's from form builder (new format)
            if ($request->has('blocks') || $request->has('branchRules')) {
                Log::info('Processing form builder format');
                
                // Delete existing questions and their options
                $existingQuestions = TemplatePertanyaan::where('id_survey', $survey_id)->get();
                foreach ($existingQuestions as $question) {
                    TemplateJawaban::where('id_template_pertanyaan', $question->id)->delete();
                }
                TemplatePertanyaan::where('id_survey', $survey_id)->delete();

                // Process questions from form builder
                foreach ($questions as $index => $questionData) {
                    $question = TemplatePertanyaan::create([
                        'id_survey' => $survey_id,
                        'pertanyaan' => $questionData['pertanyaan'] ?? '',
                        'deskripsi_pertanyaan' => $questionData['deskripsi_pertanyaan'] ?? null,
                        'id_blok' => $questionData['block_id'] ?? null,
                        'tipe_jawaban' => $questionData['tipe'] ?? 'text',
                        'urutan' => $index + 1,
                        'visualisasi' => $questionData['visualisasi'] ?? null,
                        'wajib_diisi' => isset($questionData['required']) && $questionData['required'] ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Add options for choice-based questions
                    if (
                        in_array($questionData['tipe'] ?? 'text', ['radio', 'checkbox', 'select'])
                        && !empty($questionData['options'])
                    ) {
                        foreach ($questionData['options'] as $optionIndex => $option) {
                            TemplateJawaban::create([
                                'id_template_pertanyaan' => $question->id,
                                'pilihan_jawaban' => $option['text'] ?? '',
                                'urutan' => $optionIndex + 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }

                // TODO: Handle blocks and branch rules when models are ready
                // $blocks = $request->input('blocks', []);
                // $branchRules = $request->input('branchRules', []);
                
            } else {
                // Handle old format (existing functionality)
                Log::info('Processing legacy format');
                
                // Delete existing questions and their options
                $existingQuestions = TemplatePertanyaan::where('id_survey', $survey_id)->get();
                foreach ($existingQuestions as $question) {
                    TemplateJawaban::where('id_template_pertanyaan', $question->id)->delete();
                }
                TemplatePertanyaan::where('id_survey', $survey_id)->delete();

                // Create new questions (legacy format)
                foreach ($questions as $questionData) {
                    $question = TemplatePertanyaan::create([
                        'id_survey' => $survey_id,
                        'pertanyaan' => $questionData['question'],
                        'deskripsi_pertanyaan' => $questionData['description'] ?? null,
                        'blok' => $questionData['blok'] ?? null,
                        'tipe_jawaban' => $questionData['type'],
                        'urutan' => $questionData['order'],
                        'visualisasi' => $questionData['visualisasi'],
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
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Template pertanyaan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating questions: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
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
        var_dump($survey);
        
        $survey->delete();

        return redirect()->route('admin.survey.index')->with('success', 'Survey deleted successfully.');
    }

    public function import(Request $request, Excel $excel)
    {
        try{
            DB::beginTransaction();
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
                'survey_id' => 'required',
            ]);
            $survey_id = $request->input('survey_id');
            $survey = Survey::findOrFail($survey_id);
            if ($survey->type_survei=='atasan'){
                Excel::import(new AtasanImport($survey_id), ($request->file('file')));
            } else {
                Excel::import(new AlumniImport($survey_id), $request->file('file'));
            }
            DB::commit();
            return redirect()->route('admin.survey.index')->with('success', 'User imported successfully.');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            // Rollback the transaction if needed
            DB::rollback();
            return redirect()->route('admin.survey.index')->with('error', 'Failed to import user survey.');
        }
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
