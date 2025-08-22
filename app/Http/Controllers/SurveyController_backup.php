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
use Illuminate\Support\Facades\Auth;
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
            'sections' => 'nullable|array',
            'sections.*.section_name' => 'required_with:sections|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.navigation_type' => 'nullable|string',
            'sections.*.questions' => 'required_with:sections|array|min:1',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,file,date',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.options' => 'nullable|array',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.questions.*.option_navigation' => 'nullable|array',
            'sections.*.questions.*.option_navigation.*' => 'string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create the survey
            $survey = Survey::create([
                'nama' => $validatedData['nama'],
                'tanggal_mulai' => $validatedData['tanggal_mulai'],
                'tanggal_selesai' => $validatedData['tanggal_selesai'],
                'type_survei' => $validatedData['type_survei'],
                'deskripsi' => $validatedData['deskripsi'],
                'created_by' => Auth::id(),
            ]);

            // If sections are provided, create form builder content
            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                $this->createFormBuilderContent($survey, $validatedData['sections']);
            }

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey berhasil dibuat' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'),
                    'survey_id' => $survey->id
                ], 200);
            }

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil dibuat' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating survey with form builder', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan survey: ' . $e->getMessage(),
                    'errors' => ['database' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan survey: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Create form builder content (blocks and questions)
     */
    private function createFormBuilderContent($survey, $sections)
    {
        Log::info('Creating form builder content', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections),
            'sections_data' => $sections
        ]);

        foreach ($sections as $sectionIndex => $sectionData) {
            // Create survey block with proper kode generation
            $kode = 'BLOCK_' . str_pad(($sectionIndex + 1), 2, '0', STR_PAD_LEFT);

            $blockData = [
                'survey_id' => $survey->id,
                'kode' => $kode,
                'nama' => $sectionData['section_name'],
                'deskripsi' => $sectionData['section_description'] ?? '',
                'urutan' => $sectionIndex + 1,
                'navigation_type' => $sectionData['navigation_type'] ?? 'next',
                'is_terminal' => false,
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::info('Attempting to create survey block', [
                'block_data' => $blockData,
                'section_index' => $sectionIndex
            ]);

            try {
                // Clear any cached model attributes
                \App\Models\SurveyBlock::clearBootedModels();

                $block = \App\Models\SurveyBlock::create($blockData);
                Log::info('Survey block created successfully via Eloquent', ['block_id' => $block->id]);

            } catch (\Exception $e) {
                Log::error('Failed to create survey block via Eloquent', [
                    'error' => $e->getMessage(),
                    'data' => $blockData,
                    'trace' => $e->getTraceAsString()
                ]);

                // Try with direct DB insertion as fallback
                try {
                    $blockId = DB::table('survey_blocks')->insertGetId($blockData);
                    $block = \App\Models\SurveyBlock::find($blockId);
                    Log::info('Survey block created successfully via DB::table', ['block_id' => $block->id]);
                } catch (\Exception $dbException) {
                    Log::error('Failed to create survey block via DB::table', [
                        'error' => $dbException->getMessage(),
                        'data' => $blockData
                    ]);
                    throw new \Exception('Gagal membuat survey block: ' . $dbException->getMessage());
                }
            }

            // Create questions for this block
            if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                foreach ($sectionData['questions'] as $questionIndex => $questionData) {
                    $question = \App\Models\TemplatePertanyaan::create([
                        'id_survey' => $survey->id,
                        'block_id' => $block->id,
                        'pertanyaan' => $questionData['question'],
                        'deskripsi_pertanyaan' => $questionData['description'] ?? '',
                        'tipe' => $questionData['type'],
                        'is_required' => $questionData['required'] ?? false,
                        'visualisasi' => $questionData['visualization'] ?? '',
                        'urutan' => $questionIndex + 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Create template answers for radio, checkbox, select types
                    if (in_array($questionData['type'], ['radio', 'checkbox', 'select']) &&
                        isset($questionData['options']) && is_array($questionData['options'])) {

                        foreach ($questionData['options'] as $optionIndex => $optionText) {
                            if (!empty(trim($optionText))) {
                                // Get navigation for this option if available
                                $optionNavigation = null;

                                // Check if navigation array exists and has value for this option
                                if (isset($questionData['option_navigation']) &&
                                    is_array($questionData['option_navigation'])) {

                                    // Handle both indexed and non-indexed arrays
                                    if (isset($questionData['option_navigation'][$optionIndex])) {
                                        $navValue = trim($questionData['option_navigation'][$optionIndex]);
                                        // Set navigation value, allowing 'next', 'end', 'block_X', etc.
                                        // Only skip if completely empty string
                                        if ($navValue !== '') {
                                            $optionNavigation = $this->resolveNavigationTarget($navValue, $survey->id, $sections);
                                        }
                                    }
                                }

                                \App\Models\TemplateJawaban::create([
                                    'id_template_pertanyaan' => $question->id,
                                    'pilihan_jawaban' => trim($optionText),
                                    'urutan' => $optionIndex + 1,
                                    'navigation_target' => $optionNavigation,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Resolve navigation target from string format to actual block ID
     */
    private function resolveNavigationTarget($navValue, $surveyId, $sections)
    {
        // Handle special values that don't need resolution
        if (in_array($navValue, ['next', 'end', ''])) {
            return $navValue;
        }

        // Handle block_X format
        if (strpos($navValue, 'block_') === 0) {
            $blockNumber = (int) substr($navValue, 6);
            $kode = 'BLOCK_' . str_pad($blockNumber, 2, '0', STR_PAD_LEFT);
            
            Log::info('Resolving navigation target', [
                'nav_value' => $navValue,
                'block_number' => $blockNumber,
                'block_code' => $kode,
                'survey_id' => $surveyId
            ]);

            // Find existing block by urutan (order) OR by kode
            $existingBlock = \App\Models\SurveyBlock::where('survey_id', $surveyId)
                ->where(function($query) use ($blockNumber, $kode) {
                    $query->where('urutan', $blockNumber)
                          ->orWhere('kode', $kode);
                })
                ->first();

            if ($existingBlock) {
                Log::info('Found existing block', [
                    'block_id' => $existingBlock->id,
                    'block_name' => $existingBlock->nama,
                    'block_code' => $existingBlock->kode,
                    'block_order' => $existingBlock->urutan
                ]);
                return (string) $existingBlock->id;
            }

            // Block doesn't exist yet, create placeholder if we have section data
            if (isset($sections[$blockNumber - 1])) {
                $sectionData = $sections[$blockNumber - 1];
                
                try {
                    // Double check that block doesn't exist before creating
                    $doubleCheckBlock = \App\Models\SurveyBlock::where('survey_id', $surveyId)
                        ->where(function($query) use ($blockNumber, $kode) {
                            $query->where('urutan', $blockNumber)
                                  ->orWhere('kode', $kode);
                        })
                        ->first();
                    
                    if ($doubleCheckBlock) {
                        Log::info('Block found in double-check', [
                            'block_id' => $doubleCheckBlock->id
                        ]);
                        return (string) $doubleCheckBlock->id;
                    }
                    
                    $newBlock = \App\Models\SurveyBlock::create([
                        'survey_id' => $surveyId,
                        'kode' => $kode,
                        'nama' => $sectionData['section_name'],
                        'deskripsi' => $sectionData['section_description'] ?? '',
                        'urutan' => $blockNumber,
                        'navigation_type' => $sectionData['navigation_type'] ?? 'next',
                        'is_terminal' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Created new block for navigation', [
                        'block_id' => $newBlock->id,
                        'block_name' => $newBlock->nama,
                        'block_order' => $newBlock->urutan
                    ]);

                    return (string) $newBlock->id;

                } catch (\Exception $e) {
                    Log::error('Failed to create block for navigation', [
                        'error' => $e->getMessage(),
                        'block_number' => $blockNumber,
                        'survey_id' => $surveyId
                    ]);
                    
                    // Fallback: return original value if block creation fails
                    return $navValue;
                }
            } else {
                Log::warning('No section data found for block number', [
                    'block_number' => $blockNumber,
                    'available_sections' => array_keys($sections)
                ]);
                
                try {
                    // Double check that block doesn't exist before creating
                    $doubleCheckBlock = \App\Models\SurveyBlock::where('survey_id', $surveyId)
                        ->where(function($query) use ($blockNumber, $kode) {
                            $query->where('urutan', $blockNumber)
                                  ->orWhere('kode', $kode);
                        })
                        ->first();
                    
                    if ($doubleCheckBlock) {
                        Log::info('Block found in double-check (fallback)', [
                            'block_id' => $doubleCheckBlock->id
                        ]);
                        return (string) $doubleCheckBlock->id;
                    }
                
                    // Create a basic placeholder block
                    $newBlock = \App\Models\SurveyBlock::create([
                        'survey_id' => $surveyId,
                        'kode' => $kode,
                        'nama' => 'Blok ' . $blockNumber,
                        'deskripsi' => 'Blok placeholder untuk navigasi',
                        'urutan' => $blockNumber,
                        'navigation_type' => 'next',
                        'is_terminal' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Created placeholder block for navigation', [
                        'block_id' => $newBlock->id,
                        'block_order' => $newBlock->urutan
                    ]);

                    return (string) $newBlock->id;

                } catch (\Exception $e) {
                    Log::error('Failed to create placeholder block', [
                        'error' => $e->getMessage(),
                        'block_number' => $blockNumber,
                        'survey_id' => $surveyId
                    ]);
                    
                    return $navValue;
                }
            }
        }

        // Return original value for unknown formats
        Log::info('Unknown navigation target format', ['nav_value' => $navValue]);
        return $navValue;
    }

    /**
     * Update form builder content (blocks and questions)
     */
    private function updateFormBuilderContent($survey, $sections)
    {
        Log::info('Updating form builder content', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections),
            'sections_data' => $sections
        ]);

        try {
            // Step 1: Completely delete existing survey data
            $this->deleteExistingSurveyData($survey->id);
            
            // Step 2: Clear any cached models to avoid stale data
            \App\Models\SurveyBlock::flushEventListeners();
            \App\Models\TemplatePertanyaan::flushEventListeners();
            \App\Models\TemplateJawaban::flushEventListeners();

            // Step 3: Create new content using the same method as create
            $this->createFormBuilderContent($survey, $sections);

        } catch (\Exception $e) {
            Log::error('Error in updateFormBuilderContent', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'survey_id' => $survey->id
            ]);
            throw $e;
        }
    }

    /**
     * Delete all existing survey data (blocks, questions, answers)
     */
    private function deleteExistingSurveyData($surveyId)
    {
        Log::info('Deleting existing survey data', ['survey_id' => $surveyId]);

        try {
            // Disable foreign key checks for clean deletion
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Delete in correct order: answers -> questions -> blocks
            $deletedAnswers = DB::table('template_jawaban')
                ->whereIn('id_template_pertanyaan', function ($query) use ($surveyId) {
                    $query->select('id')
                          ->from('template_pertanyaan')
                          ->where('id_survey', $surveyId);
                })->delete();

            $deletedQuestions = DB::table('template_pertanyaan')
                ->where('id_survey', $surveyId)
                ->delete();

            $deletedBlocks = DB::table('survey_blocks')
                ->where('survey_id', $surveyId)
                ->delete();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Log::info('Successfully deleted existing survey data', [
                'survey_id' => $surveyId,
                'deleted_answers' => $deletedAnswers,
                'deleted_questions' => $deletedQuestions,
                'deleted_blocks' => $deletedBlocks
            ]);

            // Force refresh any cached models
            DB::statement('FLUSH TABLES');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::error('Failed to delete existing survey data', [
                'survey_id' => $surveyId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(survey $survey)
    {
        //
    }
                ]);

                try {
                    // Clear any cached model attributes
                    \App\Models\SurveyBlock::clearBootedModels();

                    $block = \App\Models\SurveyBlock::create($blockData);
                    Log::info('Survey block created successfully via Eloquent', ['block_id' => $block->id]);

                } catch (\Exception $e) {
                    Log::error('Failed to create survey block via Eloquent', [
                        'error' => $e->getMessage(),
                        'data' => $blockData
                    ]);

                    // Try with direct DB insertion as fallback
                    try {
                        $blockId = DB::table('survey_blocks')->insertGetId($blockData);
                        $block = \App\Models\SurveyBlock::find($blockId);
                        Log::info('Survey block created successfully via DB::table', ['block_id' => $block->id]);
                    } catch (\Exception $dbException) {
                        Log::error('Failed to create survey block via DB::table', [
                            'error' => $dbException->getMessage(),
                            'data' => $blockData
                        ]);
                        throw new \Exception('Gagal mengupdate survey block: ' . $dbException->getMessage());
                    }
                }

                // Create questions for this block
                if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                    foreach ($sectionData['questions'] as $questionIndex => $questionData) {
                        $question = \App\Models\TemplatePertanyaan::create([
                            'id_survey' => $survey->id,
                            'block_id' => $block->id,
                            'pertanyaan' => $questionData['question'],
                            'deskripsi_pertanyaan' => $questionData['description'] ?? '',
                            'tipe' => $questionData['type'],
                            'is_required' => $questionData['required'] ?? false,
                            'visualisasi' => $questionData['visualization'] ?? '',
                            'urutan' => $questionIndex + 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        Log::info('Created question', ['question_id' => $question->id, 'block_id' => $block->id]);

                        // Create template answers for radio, checkbox, select types
                        if (in_array($questionData['type'], ['radio', 'checkbox', 'select']) &&
                            isset($questionData['options']) && is_array($questionData['options'])) {

                            foreach ($questionData['options'] as $optionIndex => $optionText) {
                                if (!empty(trim($optionText))) {
                                    // Get navigation for this option if available
                                    $optionNavigation = null;

                                    // Check if navigation array exists and has value for this option
                                    if (isset($questionData['option_navigation']) &&
                                        is_array($questionData['option_navigation'])) {

                                        // Handle both indexed and non-indexed arrays
                                        if (isset($questionData['option_navigation'][$optionIndex])) {
                                            $navValue = trim($questionData['option_navigation'][$optionIndex]);
                                            // Set navigation value, allowing 'next', 'end', 'block_X', etc.
                                            // Only skip if completely empty string
                                            if ($navValue !== '') {
                                                $optionNavigation = $this->resolveNavigationTarget($navValue, $survey->id, $sections);
                                            }
                                        }
                                    }

                                    $templateJawaban = \App\Models\TemplateJawaban::create([
                                        'id_template_pertanyaan' => $question->id,
                                        'pilihan_jawaban' => trim($optionText),
                                        'urutan' => $optionIndex + 1,
                                        'navigation_target' => $optionNavigation,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);

                                    Log::info('Created template jawaban', [
                                        'jawaban_id' => $templateJawaban->id,
                                        'question_id' => $question->id,
                                        'option' => trim($optionText),
                                        'navigation' => $optionNavigation
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

        }
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
        try {
            $survey = Survey::findOrFail($id);
            $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("Y-m-d");
            $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("Y-m-d");
            if (Carbon::parse($survey->tanggal_selesai) >= now()) {
                $survey->status = "Aktif";
            } else {
                $survey->status = "Selesai";
            }

            // Load existing survey blocks with their questions (optimized)
            $surveyBlocks = \App\Models\SurveyBlock::with([
                'questions' => function ($query) {
                    $query->orderBy('urutan')->limit(50); // Limit questions per block
                },
                'questions.templateJawaban' => function ($query) {
                    $query->orderBy('urutan')->limit(20); // Limit options per question
                }
            ])
                ->where('survey_id', $id)
                ->orderBy('urutan')
                ->limit(10) // Limit blocks
                ->get();

            // Transform blocks data for the form builder (simplified)
            $formBuilderData = [];

            if ($surveyBlocks->count() > 0) {
                foreach ($surveyBlocks as $index => $block) {
                    $sectionId = $index + 1;

                    $formBuilderData[$sectionId] = [
                        'section_name' => $block->nama,
                        'section_description' => $block->deskripsi ?? '',
                        'navigation_type' => $block->navigation_type ?? 'next',
                        'target_section' => $block->target_section_id,
                        'questions' => []
                    ];

                    foreach ($block->questions as $qIndex => $question) {
                        $questionId = $qIndex + 1;

                        $questionData = [
                            'question' => $question->pertanyaan,
                            'description' => $question->deskripsi_pertanyaan ?? '',
                            'type' => $question->tipe,
                            'required' => $question->is_required ? '1' : '0',
                            'visualization' => $question->visualisasi ?? 'bar'
                        ];

                        // Add options for choice-based questions (simplified)
                        if (in_array($question->tipe, ['radio', 'checkbox', 'select']) && $question->templateJawaban->count() > 0) {
                            $questionData['options'] = [];
                            $questionData['option_navigation'] = [];

                            foreach ($question->templateJawaban as $answer) {
                                $questionData['options'][] = $answer->pilihan_jawaban;
                                $questionData['option_navigation'][] = $answer->navigation_target ?? 'next';
                            }
                        }

                        $formBuilderData[$sectionId]['questions'][$questionId] = $questionData;
                    }
                }
            } else {
                // If no blocks found, provide empty structure for new form
                Log::info('No survey blocks found for survey ID: ' . $id . ', providing empty form structure');
            }

            return view('admin.views.survey.edit', [
                'survey' => $survey,
                'formBuilderData' => $formBuilderData
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading survey edit form', [
                'survey_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.survey.index')
                ->with('error', 'Gagal memuat form edit survey: ' . $e->getMessage());
        }
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
            'deskripsi' => 'nullable|string|max:255',
            'sections' => 'nullable|array',
            'sections.*.section_name' => 'required_with:sections|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.navigation_type' => 'nullable|string',
            'sections.*.questions' => 'required_with:sections|array|min:1',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,file,date',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.options' => 'nullable|array',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.questions.*.option_navigation' => 'nullable|array',
            'sections.*.questions.*.option_navigation.*' => 'string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $survey = Survey::findOrFail($id);

            // Update basic survey info
            $survey->update([
                'nama' => $validatedData['nama'],
                'tanggal_mulai' => $validatedData['tanggal_mulai'],
                'tanggal_selesai' => $validatedData['tanggal_selesai'],
                'type_survei' => $validatedData['type_survei'],
                'deskripsi' => $validatedData['deskripsi'],
            ]);

            // If sections are provided, update form builder content
            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                $this->updateFormBuilderContent($survey, $validatedData['sections']);
            }

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey berhasil diupdate' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'),
                    'survey_id' => $survey->id
                ], 200);
            }

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil diupdate' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating survey with form builder', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'survey_id' => $id,
                'request_data' => $request->all()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage(),
                    'errors' => ['database' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage()])->withInput();
        }
    }

    public function form_builder($id)
    {
        $survey = Survey::findOrFail($id);

        // Get existing questions with their template answers
        $questions = TemplatePertanyaan::with(['templateJawaban' => function ($query) {
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
                    'tipe' => $question->tipe,
                    'block_id' => $question->id_blok ?? '',
                    'required' => $question->is_required == 1,
                    'visualisasi' => $question->visualisasi ?? '',
                    'options' => $question->templateJawaban->map(function ($jawaban) {
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

        // Ambil blok beserta pertanyaan dan jawaban
        $surveyBlocks = \App\Models\SurveyBlock::with([
            'questions' => function ($query) {
                $query->orderBy('urutan');
            },
            'questions.templateJawaban' => function ($query) {
                $query->orderBy('urutan');
            }
        ])
        ->where('survey_id', $id)
        ->orderBy('urutan')
        ->get();

        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }

        return view('admin.views.survey.details', [
            'survey' => $survey,
            'survey_user' => $survey_user,
            'surveyBlocks' => $surveyBlocks
        ]);
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
                        'tipe' => $questionData['tipe'] ?? 'text',
                        'urutan' => $index + 1,
                        'visualisasi' => $questionData['visualisasi'] ?? null,
                        'is_required' => isset($questionData['required']) && $questionData['required'] ? 1 : 0,
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
                        'tipe' => $questionData['type'],
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

        // 3. Get all blocks of the original survey
        $originalBlocks = \App\Models\SurveyBlock::where('survey_id', $originalSurvey->id)->orderBy('urutan')->get();
        $blockIdMap = [];

        foreach ($originalBlocks as $originalBlock) {
            $newBlock = $originalBlock->replicate();
            $newBlock->survey_id = $newSurvey->id;
            $newBlock->created_at = now();
            $newBlock->updated_at = now();
            $newBlock->save();
            $blockIdMap[$originalBlock->id] = $newBlock->id;

            // 4. Get all questions for this block
            $originalQuestions = \App\Models\TemplatePertanyaan::where('block_id', $originalBlock->id)->orderBy('urutan')->get();
            foreach ($originalQuestions as $originalQuestion) {
                $newQuestion = $originalQuestion->replicate();
                $newQuestion->id_survey = $newSurvey->id;
                $newQuestion->block_id = $newBlock->id;
                $newQuestion->created_at = now();
                $newQuestion->updated_at = now();
                $newQuestion->save();

                // 5. Get all template answers for this question
                $originalAnswers = \App\Models\TemplateJawaban::where('id_template_pertanyaan', $originalQuestion->id)->orderBy('urutan')->get();
                foreach ($originalAnswers as $originalAnswer) {
                    $newAnswer = $originalAnswer->replicate();
                    $newAnswer->id_template_pertanyaan = $newQuestion->id;
                    $newAnswer->created_at = now();
                    $newAnswer->updated_at = now();
                    $newAnswer->save();
                }
            }
        }

        // 6. Get all survey users of the original survey
        $originalSurveyUsers = SurveyUser::where('survey_id', $originalSurvey->id)->whereNull('deleted_at')->get();
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
