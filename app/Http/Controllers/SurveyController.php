<?php

namespace App\Http\Controllers;

use App\Models\Survey;
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
                $q->where('nama', 'like', $searchTerm)
                  ->orWhere('type_survei', 'like', $searchTerm);
            });
        }

        $survey = $query->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->appends($request->query());
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
                    'survey_id' => $survey->id,
                    'redirect_url' => route('admin.survey.index')
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
     * Create form builder content (blocks and questions) - Two-Pass Approach
     */
    private function createFormBuilderContent($survey, $sections)
    {
        Log::info('Creating form builder content with two-pass approach', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections)
        ]);

        // Reindex sections array to ensure sequential order (0, 1, 2, 3...)
        // This fixes the issue where DOM insertion creates non-sequential keys
        $sections = array_values($sections);
        
        Log::info('Sections reindexed for sequential processing', [
            'original_keys' => array_keys($sections),
            'reindexed_count' => count($sections)
        ]);

        // PASS 1: Create all blocks first
        $createdBlocks = [];
        foreach ($sections as $sectionIndex => $sectionData) {
            $kode = 'BLOCK_' . str_pad(($sectionIndex + 1), 2, '0', STR_PAD_LEFT);
            $blockNumber = $sectionIndex + 1;

            $blockData = [
                'survey_id' => $survey->id,
                'kode' => $kode,
                'nama' => $sectionData['section_name'],
                'deskripsi' => $sectionData['section_description'] ?? '',
                'urutan' => $blockNumber,
                'navigation_type' => $sectionData['navigation_type'] ?? 'next',
                'is_terminal' => false,
                'target_section_id' => null, // Will be updated in PASS 3
                'created_at' => now(),
                'updated_at' => now()
            ];

            try {
                $block = \App\Models\SurveyBlock::create($blockData);
                $createdBlocks[$blockNumber] = $block; // Key = block number for easy lookup

                Log::info('Block created in pass 1', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'block_name' => $block->nama
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to create survey block in pass 1', [
                    'error' => $e->getMessage(),
                    'data' => $blockData
                ]);
                throw new \Exception('Gagal membuat survey block: ' . $e->getMessage());
            }
        }

        // PASS 2: Create questions and answers with resolved navigation
        foreach ($sections as $sectionIndex => $sectionData) {
            $blockNumber = $sectionIndex + 1;
            $block = $createdBlocks[$blockNumber];

            if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                // Reindex questions array to ensure sequential order (0, 1, 2, 3...)
                $questions = array_values($sectionData['questions']);
                
                foreach ($questions as $questionIndex => $questionData) {
                    $question = \App\Models\TemplatePertanyaan::create([
                        'id_survey' => $survey->id,
                        'block_id' => $block->id,
                        'pertanyaan' => $questionData['question'],
                        'deskripsi_pertanyaan' => $questionData['description'] ?? '',
                        'tipe' => $questionData['type'],
                        'urutan' => $questionIndex + 1,
                        'is_required' => isset($questionData['required']) ? (bool)$questionData['required'] : false,
                        'visualisasi' => $questionData['visualization'] ?? 'bar',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Question created in pass 2', [
                        'question_id' => $question->id,
                        'block_id' => $block->id
                    ]);

                    // Create template answers for radio, checkbox, select types
                    if (in_array($questionData['type'], ['radio', 'checkbox', 'select']) &&
                        isset($questionData['options']) && is_array($questionData['options'])) {

                        foreach ($questionData['options'] as $optionIndex => $option) {
                            if (!empty(trim($option))) {
                                $optionNavigation = null;

                                // Check if navigation array exists and has value for this option
                                if (isset($questionData['option_navigation']) &&
                                    is_array($questionData['option_navigation']) &&
                                    isset($questionData['option_navigation'][$optionIndex])) {

                                    $navValue = trim($questionData['option_navigation'][$optionIndex]);

                                    Log::info('Processing option navigation', [
                                        'option' => $option,
                                        'option_index' => $optionIndex,
                                        'nav_value_raw' => $questionData['option_navigation'][$optionIndex],
                                        'nav_value_trimmed' => $navValue,
                                        'question_id' => $question->id
                                    ]);

                                    if ($navValue !== '') {
                                        $optionNavigation = $this->simpleNavigationResolve($navValue, $createdBlocks);

                                        Log::info('Option navigation resolved', [
                                            'option' => $option,
                                            'nav_input' => $navValue,
                                            'nav_output' => $optionNavigation
                                        ]);
                                    }
                                }

                                \App\Models\TemplateJawaban::create([
                                    'id_template_pertanyaan' => $question->id,
                                    'pilihan_jawaban' => $option,
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

        // PASS 3: Update target_section_id for blocks that have default navigation
        foreach ($sections as $sectionIndex => $sectionData) {
            $blockNumber = $sectionIndex + 1;
            $block = $createdBlocks[$blockNumber];

            // Determine target section based on navigation_type
            $targetSectionId = null;
            $navigationType = $sectionData['navigation_type'] ?? 'next';

            Log::info('Processing block navigation_type', [
                'block_number' => $blockNumber,
                'block_name' => $block->nama,
                'navigation_type' => $navigationType
            ]);

            if ($navigationType === 'next') {
                // Point to next block if exists
                $nextBlockNumber = $blockNumber + 1;
                if (isset($createdBlocks[$nextBlockNumber])) {
                    $targetSectionId = $createdBlocks[$nextBlockNumber]->id;
                }
            } elseif ($navigationType === 'end') {
                // No target for end type
                $targetSectionId = null;
            } elseif (strpos($navigationType, 'block_') === 0) {
                // Specific block target - extract block number carefully
                $targetBlockNumberStr = substr($navigationType, 6);
                $targetBlockNumber = (int) $targetBlockNumberStr;

                Log::info('Resolving block navigation type', [
                    'navigation_type' => $navigationType,
                    'target_block_str' => $targetBlockNumberStr,
                    'target_block_number' => $targetBlockNumber
                ]);

                // Validate block number is positive and exists
                if ($targetBlockNumber > 0 && isset($createdBlocks[$targetBlockNumber])) {
                    $targetSectionId = $createdBlocks[$targetBlockNumber]->id;

                    Log::info('Block navigation resolved', [
                        'source_block' => $blockNumber,
                        'target_block_number' => $targetBlockNumber,
                        'target_section_id' => $targetSectionId
                    ]);
                } else {
                    Log::error('Target block not found for navigation_type', [
                        'navigation_type' => $navigationType,
                        'target_block_str' => $targetBlockNumberStr,
                        'target_block_number' => $targetBlockNumber,
                        'available_blocks' => array_keys($createdBlocks),
                        'block_exists' => isset($createdBlocks[$targetBlockNumber]),
                        'block_positive' => $targetBlockNumber > 0
                    ]);
                }
            }

            // Update block with target_section_id
            if ($targetSectionId) {
                $block->update(['target_section_id' => $targetSectionId]);
                Log::info('Updated block target_section_id', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'target_section_id' => $targetSectionId
                ]);
            } else {
                Log::info('No target_section_id set for block', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'navigation_type' => $navigationType
                ]);
            }
        }

        Log::info('Form builder content created successfully', [
            'survey_id' => $survey->id,
            'total_blocks' => count($createdBlocks)
        ]);
    }

    /**
     * Simple navigation resolver - Clean approach using pre-created blocks
     */
    private function simpleNavigationResolve($navValue, $createdBlocks)
    {
        // Handle special values that don't need resolution
        if (in_array($navValue, ['next', 'end', ''])) {
            return $navValue;
        }

        // Handle block_X format (including block_1)
        if (strpos($navValue, 'block_') === 0) {
            // Extract block number more carefully
            $blockNumberStr = substr($navValue, 6); // Remove 'block_' prefix
            $blockNumber = (int) $blockNumberStr;

            Log::info('Attempting to resolve navigation', [
                'nav_value' => $navValue,
                'block_number_str' => $blockNumberStr,
                'block_number_int' => $blockNumber,
                'available_blocks' => array_keys($createdBlocks)
            ]);

            // Validate block number is positive and exists
            if ($blockNumber > 0 && isset($createdBlocks[$blockNumber])) {
                $targetBlockId = (string) $createdBlocks[$blockNumber]->id;

                Log::info('Navigation resolved successfully', [
                    'nav_value' => $navValue,
                    'block_number' => $blockNumber,
                    'target_block_id' => $targetBlockId,
                    'target_block_name' => $createdBlocks[$blockNumber]->nama
                ]);

                return $targetBlockId;
            } else {
                Log::error('Block number not found or invalid', [
                    'nav_value' => $navValue,
                    'block_number_str' => $blockNumberStr,
                    'block_number_int' => $blockNumber,
                    'available_blocks' => array_keys($createdBlocks),
                    'block_exists' => isset($createdBlocks[$blockNumber]),
                    'block_positive' => $blockNumber > 0
                ]);

                // Return the nav_value as fallback untuk debugging
                return $navValue;
            }
        }

        // Fallback: return original value for unknown formats
        Log::info('Navigation fallback - unknown format', [
            'nav_value' => $navValue
        ]);
        return $navValue;
    }

    /**
     * Reverse navigation transform - Convert block ID back to block_x format for editing
     */
    private function reverseNavigationTransform($navigationTarget, $surveyBlocks)
    {
        // Handle special values that don't need transformation
        if (in_array($navigationTarget, ['next', 'end', '', null])) {
            return $navigationTarget ?? '';
        }

        // If it's a numeric block ID, find the corresponding block and convert to block_x format
        if (is_numeric($navigationTarget)) {
            foreach ($surveyBlocks as $block) {
                if ($block->id == $navigationTarget) {
                    return 'block_' . $block->urutan;
                }
            }
        }

        // Return original value if no transformation needed
        return $navigationTarget;
    }

    /**
     * Update form builder content (blocks and questions) - Two-Pass Approach
     */
    private function updateFormBuilderContent($survey, $sections)
    {
        Log::info('Updating form builder content with two-pass approach', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections)
        ]);

        try {
            // Step 1: Completely delete existing survey data
            $this->deleteExistingSurveyData($survey->id);

            // Step 2: Create new content using the same two-pass method as create
            $this->createFormBuilderContent($survey, $sections);

            Log::info('Form builder content updated successfully', [
                'survey_id' => $survey->id
            ]);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("Y-m-d");
            $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("Y-m-d");

            // Load existing survey blocks with their questions
            $surveyBlocks = \App\Models\SurveyBlock::with([
                'questions.templateJawaban' => function ($query) {
                    $query->orderBy('urutan');
                }
            ])->where('survey_id', $id)->orderBy('urutan')->get();

            // Transform blocks data for the form builder
            $formBuilderData = [];
            if ($surveyBlocks->count() > 0) {
                foreach ($surveyBlocks as $index => $block) {
                    $formBuilderData[$index + 1] = [
                        'section_name' => $block->nama,
                        'section_description' => $block->deskripsi ?? '',
                        'navigation_type' => $block->navigation_type ?? 'next',
                        'questions' => []
                    ];

                    foreach ($block->questions as $qIndex => $question) {
                        $questionData = [
                            'question' => $question->pertanyaan,
                            'description' => $question->deskripsi_pertanyaan ?? '',
                            'type' => $question->tipe,
                            'required' => $question->is_required ? '1' : '0',
                            'visualization' => $question->visualisasi ?? 'bar'
                        ];

                        if (in_array($question->tipe, ['radio', 'checkbox', 'select'])) {
                            $questionData['options'] = [];
                            $questionData['option_navigation'] = [];
                            foreach ($question->templateJawaban as $answer) {
                                $questionData['options'][] = $answer->pilihan_jawaban;
                                // Transform block ID back to block_x format for editing
                                $navigationValue = $this->reverseNavigationTransform($answer->navigation_target, $surveyBlocks);
                                $questionData['option_navigation'][] = $navigationValue;
                            }
                        }

                        $formBuilderData[$index + 1]['questions'][$qIndex + 1] = $questionData;
                    }
                }
            }

            return view('admin.views.survey.edit', [
                'survey' => $survey,
                'formBuilderData' => $formBuilderData
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading survey edit form', ['error' => $e->getMessage()]);
            return redirect()->route('admin.survey.index')->with('error', 'Gagal memuat form edit survey');
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
                    'message' => 'Survey berhasil diupdate!',
                    'survey_id' => $survey->id
                ], 200);
            }

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating survey with form builder', [
                'error' => $e->getMessage(),
                'survey_id' => $id
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display survey details
     */
    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $survey_user = SurveyUser::getSurveyUser($id);

        $surveyBlocks = \App\Models\SurveyBlock::with([
            'questions.templateJawaban' => function ($query) {
                $query->orderBy('urutan');
            }
        ])->where('survey_id', $id)->orderBy('urutan')->get();

        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");

        return view('admin.views.survey.details', [
            'survey' => $survey,
            'survey_user' => $survey_user,
            'surveyBlocks' => $surveyBlocks
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(survey $survey)
    {
        $survey->delete();
        return redirect()->route('admin.survey.index')->with('success', 'Survey deleted successfully.');
    }
}
