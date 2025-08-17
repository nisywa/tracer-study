<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SectionNavigationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FormBuilderController extends Controller
{
    /**
     * Show form builder untuk create survey
     */
    public function create()
    {
        return view('admin.views.survey.form-builder');
    }

    /**
     * Show form builder untuk edit survey
     */
    public function edit($surveyId)
    {
        $survey = Survey::with([
            'surveyBlocks' => function($query) {
                $query->orderBy('urutan');
            },
            'surveyBlocks.questions' => function($query) {
                $query->orderBy('urutan');
            },
            'surveyBlocks.questions.templateJawaban' => function($query) {
                $query->orderBy('urutan');
            }
        ])->findOrFail($surveyId);

        return view('admin.views.survey.form-builder', compact('survey'));
    }

    /**
     * Save form builder data dengan optimasi dan error handling
     */
    public function save(Request $request)
    {
        // Log incoming request for debugging
        Log::info('FormBuilder save request', [
            'user_id' => auth()->id(),
            'action' => $request->input('action'),
            'survey_id' => $request->input('survey_id'),
            'has_sections' => !empty($request->input('sections')),
            'sections_count' => count($request->input('sections', [])),
            'request_size' => strlen(json_encode($request->all()))
        ]);

        // Check if this is questions-only save mode
        $isQuestionsOnly = $request->input('action') === 'save_questions_only';
        
        if ($isQuestionsOnly) {
            return $this->saveQuestionsOnly($request);
        }

        // Original full survey save logic
        // Validasi input
        $validator = $this->validateFormBuilderData($request);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // Additional business logic validation
        $additionalValidation = $this->validateBusinessLogic($request);
        if (!$additionalValidation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $additionalValidation['message'],
                'errors' => $additionalValidation['errors']
            ], 422);
        }

        DB::beginTransaction();
        try {
            $surveyId = $request->input('survey_id');
            
            // Tentukan mode: create atau update
            if ($surveyId) {
                // Check permission untuk update
                $survey = Survey::findOrFail($surveyId);
                if (!$this->canEditSurvey($survey)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk mengedit survey ini'
                    ], 403);
                }
                
                $survey = $this->updateSurvey($request, $surveyId);
                $action = 'updated';
            } else {
                $survey = $this->createSurvey($request);
                $action = 'created';
            }

            // Process sections/blocks with optimized queries
            $this->processSurveyBlocks($request, $survey);

            // Process section navigation rules
            $this->processSectionNavigation($request, $survey);

            // Update survey timestamp
            $survey->touch();

            DB::commit();
            
            // Log activity
            Log::info("Survey {$action}", [
                'survey_id' => $survey->id,
                'user_id' => auth()->id(),
                'sections_count' => count($request->input('sections', []))
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $surveyId ? 'Survey berhasil diperbarui' : 'Survey berhasil dibuat',
                'data' => [
                    'survey_id' => $survey->id,
                    'redirect_url' => route('admin.survey.index'),
                    'edit_url' => route('admin.survey.form_builder', $survey->id)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving form builder', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan survey. Silakan coba lagi.',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Save questions only - untuk mode editing questions tanpa mengubah survey metadata
     */
    private function saveQuestionsOnly(Request $request)
    {
        // Validasi khusus untuk questions only mode
        $validator = $this->validateQuestionsOnlyData($request);
        if ($validator->fails()) {
            Log::warning('Questions validation failed', [
                'errors' => $validator->errors()->toArray(),
                'user_id' => auth()->id(),
                'survey_id' => $request->input('survey_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data pertanyaan tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $surveyId = $request->input('survey_id');
        if (!$surveyId) {
            return response()->json([
                'success' => false,
                'message' => 'Survey ID diperlukan untuk menyimpan pertanyaan'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Check survey exists and permission
            $survey = Survey::findOrFail($surveyId);
            if (!$this->canEditSurvey($survey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit survey ini'
                ], 403);
            }

            Log::info('Starting questions-only save', [
                'survey_id' => $surveyId,
                'user_id' => auth()->id(),
                'sections_count' => count($request->input('sections', []))
            ]);

            // Process only the questions/blocks - don't update survey metadata
            $this->processSurveyBlocks($request, $survey);
            $this->processSectionNavigation($request, $survey);

            // Update survey timestamp to reflect changes
            $survey->touch();

            DB::commit();
            
            Log::info('Questions saved successfully', [
                'survey_id' => $surveyId,
                'user_id' => auth()->id(),
                'sections_processed' => count($request->input('sections', []))
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil disimpan',
                'data' => [
                    'survey_id' => $survey->id,
                    'sections_saved' => count($request->input('sections', [])),
                    'updated_at' => $survey->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving questions only', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'survey_id' => $surveyId,
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pertanyaan. Silakan coba lagi.',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Validasi khusus untuk mode questions only
     */
    private function validateQuestionsOnlyData(Request $request)
    {
        $rules = [
            'survey_id' => 'required|integer|exists:survey,id', // Fixed table name: survey (not surveys)
            'sections' => 'required|array|min:1|max:20',
            'sections.*.section_name' => 'required|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.questions' => 'required|array|min:1|max:50',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,file,date',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.options' => 'nullable|array|max:20',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.navigation' => 'nullable|array',
            'sections.*.navigation.type' => 'nullable|in:next,jump,end',
            'sections.*.navigation.target_section' => 'nullable|integer|min:1',
        ];

        $messages = [
            'survey_id.required' => 'Survey ID diperlukan',
            'survey_id.exists' => 'Survey tidak ditemukan',
            'sections.required' => 'Minimal harus ada 1 section',
            'sections.max' => 'Maksimal 20 sections dalam satu survey',
            'sections.*.section_name.required' => 'Nama section wajib diisi',
            'sections.*.questions.required' => 'Setiap section harus memiliki minimal 1 pertanyaan',
            'sections.*.questions.*.question.required' => 'Teks pertanyaan wajib diisi',
            'sections.*.questions.*.type.required' => 'Tipe pertanyaan wajib dipilih',
            'sections.*.questions.*.type.in' => 'Tipe pertanyaan tidak valid',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Validasi business logic tambahan
     */
    private function validateBusinessLogic(Request $request)
    {
        $sections = $request->input('sections', []);
        $errors = [];

        foreach ($sections as $sectionIndex => $section) {
            // Check navigation logic
            if (isset($section['navigation']['type']) && $section['navigation']['type'] === 'jump') {
                $targetSection = $section['navigation']['target_section'] ?? null;
                
                if ($targetSection && ($targetSection < 1 || $targetSection > count($sections))) {
                    $errors["sections.{$sectionIndex}.navigation.target_section"] = [
                        'Target section tidak valid. Harus antara 1-' . count($sections)
                    ];
                }
                
                if ($targetSection && $targetSection == ($sectionIndex + 1)) {
                    $errors["sections.{$sectionIndex}.navigation.target_section"] = [
                        'Section tidak boleh mengarah ke dirinya sendiri'
                    ];
                }
            }

            // Validate question options for select types
            foreach ($section['questions'] ?? [] as $questionIndex => $question) {
                if (in_array($question['type'] ?? '', ['radio', 'checkbox', 'select'])) {
                    $options = $question['options'] ?? [];
                    if (empty($options) || count($options) < 2) {
                        $errors["sections.{$sectionIndex}.questions.{$questionIndex}.options"] = [
                            'Pertanyaan dengan tipe ' . $question['type'] . ' harus memiliki minimal 2 opsi'
                        ];
                    }
                }
            }
        }

        return [
            'valid' => empty($errors),
            'message' => empty($errors) ? '' : 'Ada kesalahan dalam konfigurasi survey',
            'errors' => $errors
        ];
    }

    /**
     * Check permission untuk edit survey
     */
    private function canEditSurvey(Survey $survey)
    {
        $user = auth()->user();
        
        // Admin bisa edit semua
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Supervisor hanya bisa edit survey yang dia buat
        if ($user->hasRole('supervisor')) {
            return $survey->created_by === $user->id;
        }
        
        return false;
    }

    /**
     * Validasi data form builder dengan custom messages
     */
    private function validateFormBuilderData(Request $request)
    {
        $rules = [
            'survey_name' => 'required|string|max:255',
            'survey_description' => 'nullable|string|max:1000',
            'sections' => 'required|array|min:1|max:20',
            'sections.*.section_name' => 'required|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.questions' => 'required|array|min:1|max:50',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,file,date',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.options' => 'nullable|array|max:20',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.navigation' => 'nullable|array',
            'sections.*.navigation.type' => 'nullable|in:next,jump,end',
            'sections.*.navigation.target_section' => 'nullable|integer|min:1',
        ];

        $messages = [
            'survey_name.required' => 'Nama survey wajib diisi',
            'survey_name.max' => 'Nama survey maksimal 255 karakter',
            'survey_description.max' => 'Deskripsi survey maksimal 1000 karakter',
            'sections.required' => 'Minimal harus ada 1 section',
            'sections.max' => 'Maksimal 20 sections dalam satu survey',
            'sections.*.section_name.required' => 'Nama section wajib diisi',
            'sections.*.section_name.max' => 'Nama section maksimal 255 karakter',
            'sections.*.questions.required' => 'Setiap section harus memiliki minimal 1 pertanyaan',
            'sections.*.questions.max' => 'Maksimal 50 pertanyaan per section',
            'sections.*.questions.*.question.required' => 'Teks pertanyaan wajib diisi',
            'sections.*.questions.*.question.max' => 'Teks pertanyaan maksimal 500 karakter',
            'sections.*.questions.*.type.required' => 'Tipe pertanyaan wajib dipilih',
            'sections.*.questions.*.type.in' => 'Tipe pertanyaan tidak valid',
            'sections.*.questions.*.options.max' => 'Maksimal 20 opsi per pertanyaan',
            'sections.*.questions.*.options.*.max' => 'Teks opsi maksimal 255 karakter',
            'sections.*.navigation.type.in' => 'Tipe navigasi tidak valid',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Create new survey dengan audit trail
     */
    private function createSurvey(Request $request)
    {
        return Survey::create([
            'nama' => $request->input('survey_name'),
            'deskripsi' => $request->input('survey_description'),
            'status' => 'draft',
            'type_survei' => 'form_builder', // Mark as form builder survey
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Update existing survey dengan audit trail
     */
    private function updateSurvey(Request $request, $surveyId)
    {
        $survey = Survey::findOrFail($surveyId);
        
        $oldData = [
            'nama' => $survey->nama,
            'deskripsi' => $survey->deskripsi
        ];
        
        $survey->update([
            'nama' => $request->input('survey_name'),
            'deskripsi' => $request->input('survey_description'),
            'updated_at' => now()
        ]);
        
        // Log changes if significant
        if ($oldData['nama'] !== $survey->nama) {
            Log::info('Survey name changed', [
                'survey_id' => $survey->id,
                'old_name' => $oldData['nama'],
                'new_name' => $survey->nama,
                'user_id' => auth()->id()
            ]);
        }
        
        return $survey;
    }

    /**
     * Process survey blocks/sections dengan batch operations dan foreign key handling
     */
    private function processSurveyBlocks(Request $request, Survey $survey)
    {
        $sections = $request->input('sections', []);
        
        // Delete existing blocks if updating (with cascade)
        if ($request->input('survey_id')) {
            $this->deleteExistingBlocksOptimized($survey->id);
        }

        // STEP 1: Create all blocks first WITHOUT target_section_id to avoid foreign key constraint
        $insertedBlocks = [];
        foreach ($sections as $sectionIndex => $sectionData) {
            // Create unique kode using letters (A, B, C, etc.) to match migration design
            $kodeOptions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
            $uniqueKode = $kodeOptions[$sectionIndex] ?? 'S' . ($sectionIndex + 1);
            
            Log::info("Creating survey block", [
                'survey_id' => $survey->id,
                'section_index' => $sectionIndex,
                'unique_kode' => $uniqueKode,
                'section_name' => $sectionData['section_name'] ?? "Section " . ($sectionIndex + 1)
            ]);
            
            // Create block without target_section_id first
            $block = SurveyBlock::create([
                'survey_id' => $survey->id,
                'kode' => $uniqueKode,
                'nama' => $sectionData['section_name'] ?? "Section " . ($sectionIndex + 1),
                'deskripsi' => $sectionData['section_description'] ?? '',
                'urutan' => $sectionIndex + 1,
                'is_terminal' => ($sectionData['navigation']['type'] ?? 'next') === 'end',
                'navigation_type' => $sectionData['navigation']['type'] ?? 'next',
                'target_section_id' => null, // Set to null initially to avoid foreign key constraint
                'metadata' => json_encode([
                    'form_builder_version' => '1.0',
                    'created_at' => now()->toISOString(),
                    'section_index' => $sectionIndex,
                    'original_code_format' => "Section " . ($sectionIndex + 1),
                    'original_target_section' => $sectionData['navigation']['target_section'] ?? null // Store original target for later update
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $insertedBlocks[$sectionIndex] = $block;
            
            Log::info("Block created with ID", [
                'block_id' => $block->id,
                'section_index' => $sectionIndex,
                'kode' => $uniqueKode
            ]);
        }

        // STEP 2: Update target_section_id now that all blocks exist
        foreach ($sections as $sectionIndex => $sectionData) {
            $block = $insertedBlocks[$sectionIndex];
            $navigation = $sectionData['navigation'] ?? [];
            
            if (!empty($navigation['type']) && $navigation['type'] === 'jump' && !empty($navigation['target_section'])) {
                $targetSectionNumber = $navigation['target_section'];
                $targetIndex = $targetSectionNumber - 1; // Convert to 0-based index
                
                if (isset($insertedBlocks[$targetIndex])) {
                    $targetBlock = $insertedBlocks[$targetIndex];
                    
                    Log::info("Updating target_section_id", [
                        'source_block_id' => $block->id,
                        'target_block_id' => $targetBlock->id,
                        'target_section_number' => $targetSectionNumber
                    ]);
                    
                    // Update the target_section_id
                    $block->update([
                        'target_section_id' => $targetBlock->id
                    ]);
                } else {
                    Log::warning("Target section not found", [
                        'source_block_id' => $block->id,
                        'target_section_number' => $targetSectionNumber,
                        'available_sections' => count($insertedBlocks)
                    ]);
                }
            }
        }

        // STEP 3: Process questions for each block
        foreach ($insertedBlocks as $index => $block) {
            $this->processQuestionsForBlock($block, $sections[$index]['questions'] ?? []);
        }

        Log::info("All survey blocks processed successfully", [
            'survey_id' => $survey->id,
            'total_blocks' => count($insertedBlocks)
        ]);
    }

    /**
     * Delete existing survey blocks dengan optimized cascade
     */
    private function deleteExistingBlocksOptimized($surveyId)
    {
        try {
            // Get block IDs first
            $blockIds = SurveyBlock::where('survey_id', $surveyId)->pluck('id')->toArray();
            
            if (empty($blockIds)) {
                Log::info("No existing blocks found for survey {$surveyId}");
                return;
            }

            Log::info("Deleting existing blocks", [
                'survey_id' => $surveyId,
                'block_count' => count($blockIds)
            ]);

            // Get question IDs
            $questionIds = TemplatePertanyaan::whereIn('block_id', $blockIds)->pluck('id')->toArray();
            
            // Delete in proper order to maintain referential integrity
            if (!empty($questionIds)) {
                $deletedAnswers = TemplateJawaban::whereIn('id_template_pertanyaan', $questionIds)->delete();
                Log::info("Deleted {$deletedAnswers} answer options");
            }
            
            $deletedQuestions = TemplatePertanyaan::whereIn('block_id', $blockIds)->delete();
            $deletedNavRules = SectionNavigationRule::where('survey_id', $surveyId)->delete();
            $deletedBlocks = SurveyBlock::whereIn('id', $blockIds)->delete();

            Log::info("Cleanup completed", [
                'survey_id' => $surveyId,
                'deleted_blocks' => $deletedBlocks,
                'deleted_questions' => $deletedQuestions,
                'deleted_nav_rules' => $deletedNavRules
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting existing blocks', [
                'survey_id' => $surveyId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process questions untuk specific block
     */
    private function processQuestionsForBlock(SurveyBlock $block, array $questions)
    {
        foreach ($questions as $questionIndex => $questionData) {
            $question = $this->createQuestionOptimized($block, $questionData, $questionIndex + 1);
            $this->processQuestionOptionsOptimized($question, $questionData);
        }
    }

    /**
     * Create question dengan optimized data
     */
    private function createQuestionOptimized(SurveyBlock $block, array $questionData, int $urutan)
    {
        return TemplatePertanyaan::create([
            'id_survey' => $block->survey_id,
            'block_id' => $block->id,
            'pertanyaan' => trim($questionData['question']),
            'deskripsi_pertanyaan' => trim($questionData['description'] ?? ''),
            'tipe' => $questionData['type'],
            'urutan' => $urutan,
            'visualisasi' => $questionData['visualization'] ?? '',
            'is_required' => $questionData['required'] ?? false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Process question options dengan batch insert
     */
    private function processQuestionOptionsOptimized(TemplatePertanyaan $question, array $questionData)
    {
        $options = $questionData['options'] ?? [];
        
        if (!in_array($question->tipe, ['radio', 'checkbox', 'select']) || empty($options)) {
            return;
        }

        $optionData = [];
        foreach ($options as $optionIndex => $optionText) {
            if (!empty(trim($optionText))) {
                $optionData[] = [
                    'id_template_pertanyaan' => $question->id,
                    'pilihan_jawaban' => trim($optionText),
                    'urutan' => $optionIndex + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        if (!empty($optionData)) {
            TemplateJawaban::insert($optionData);
        }
    }

    /**
     * Process section navigation rules dengan optimized logic dan proper block reference
     */
    private function processSectionNavigation(Request $request, Survey $survey)
    {
        $sections = $request->input('sections', []);
        $blocks = SurveyBlock::where('survey_id', $survey->id)->orderBy('urutan')->get();

        // Clear existing navigation rules
        SectionNavigationRule::where('survey_id', $survey->id)->delete();

        $navigationRules = [];

        foreach ($sections as $sectionIndex => $sectionData) {
            $navigation = $sectionData['navigation'] ?? [];
            
            if (!empty($navigation['type']) && $navigation['type'] !== 'next') {
                $sourceBlock = $blocks[$sectionIndex] ?? null;
                
                if ($sourceBlock) {
                    $targetSectionId = null;
                    
                    if ($navigation['type'] === 'jump' && !empty($navigation['target_section'])) {
                        $targetSectionNumber = $navigation['target_section'];
                        $targetIndex = $targetSectionNumber - 1; // Convert to 0-based index
                        $targetBlock = $blocks[$targetIndex] ?? null;
                        
                        if ($targetBlock) {
                            $targetSectionId = $targetBlock->id;
                            
                            Log::info("Creating navigation rule", [
                                'source_block_id' => $sourceBlock->id,
                                'target_block_id' => $targetBlock->id,
                                'target_section_number' => $targetSectionNumber
                            ]);
                        } else {
                            Log::warning("Target block not found for navigation rule", [
                                'source_block_id' => $sourceBlock->id,
                                'target_section_number' => $targetSectionNumber,
                                'available_blocks' => $blocks->count()
                            ]);
                        }
                    }

                    $navigationRules[] = [
                        'survey_id' => $survey->id,
                        'source_section_id' => $sourceBlock->id,
                        'navigation_action' => $navigation['type'] === 'jump' ? 'jump_to_section' : 'end_survey',
                        'target_section_id' => $targetSectionId,
                        'conditions' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        // Batch insert navigation rules
        if (!empty($navigationRules)) {
            SectionNavigationRule::insert($navigationRules);
            Log::info("Navigation rules created", [
                'survey_id' => $survey->id,
                'rules_count' => count($navigationRules)
            ]);
        }
    }

    /**
     * Get survey data untuk form builder editing dengan caching
     */
    public function getData($surveyId)
    {
        try {
            // Check permission
            $survey = Survey::findOrFail($surveyId);
            if (!$this->canEditSurvey($survey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengakses data survey ini'
                ], 403);
            }

            // Use eager loading untuk optimasi query
            $survey = Survey::with([
                'surveyBlocks' => function($query) {
                    $query->orderBy('urutan');
                },
                'surveyBlocks.questions' => function($query) {
                    $query->orderBy('urutan');
                },
                'surveyBlocks.questions.templateJawaban' => function($query) {
                    $query->orderBy('urutan');
                },
                'surveyBlocks.navigationRules'
            ])->findOrFail($surveyId);

            // Transform data untuk form builder dengan validation
            $formData = [
                'survey' => [
                    'id' => $survey->id,
                    'name' => $survey->nama,
                    'description' => $survey->deskripsi,
                    'status' => $survey->status,
                    'created_at' => $survey->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $survey->updated_at->format('Y-m-d H:i:s')
                ],
                'sections' => [],
                'metadata' => [
                    'total_sections' => $survey->surveyBlocks->count(),
                    'total_questions' => $survey->surveyBlocks->sum(function($block) {
                        return $block->questions->count();
                    }),
                    'has_navigation_rules' => $survey->surveyBlocks->flatMap->navigationRules->isNotEmpty()
                ]
            ];

            foreach ($survey->surveyBlocks as $blockIndex => $block) {
                $sectionData = [
                    'id' => $block->id,
                    'name' => $block->nama,
                    'description' => $block->deskripsi,
                    'questions' => [],
                    'navigation' => [
                        'type' => $block->navigation_type,
                        'target_section_id' => $block->target_section_id,
                        'is_terminal' => $block->is_terminal
                    ],
                    'metadata' => $block->metadata ? json_decode($block->metadata, true) : null
                ];

                foreach ($block->questions as $question) {
                    $questionData = [
                        'id' => $question->id,
                        'question' => $question->pertanyaan,
                        'description' => $question->deskripsi_pertanyaan,
                        'type' => $question->tipe,
                        'required' => $question->is_required,
                        'visualization' => $question->visualisasi,
                        'options' => $question->templateJawaban->pluck('pilihan_jawaban')->toArray(),
                        'order' => $question->urutan
                    ];
                    
                    $sectionData['questions'][] = $questionData;
                }

                // Add navigation rules info
                $navigationRule = $block->navigationRules->first();
                if ($navigationRule) {
                    $sectionData['navigation']['rule_id'] = $navigationRule->id;
                    $sectionData['navigation']['action'] = $navigationRule->navigation_action;
                    $sectionData['navigation']['conditions'] = $navigationRule->conditions;
                }

                $formData['sections'][] = $sectionData;
            }

            // Cache response untuk performance (optional)
            $cacheKey = "form_builder_data_{$surveyId}";
            cache()->put($cacheKey, $formData, 300); // Cache 5 menit

            return response()->json([
                'success' => true,
                'data' => $formData,
                'message' => 'Data survey berhasil dimuat'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Survey tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting form builder data', [
                'survey_id' => $surveyId,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data survey',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Clear cache untuk survey data
     */
    private function clearSurveyCache($surveyId)
    {
        cache()->forget("form_builder_data_{$surveyId}");
    }

    /**
     * Duplicate survey - create copy from existing survey
     */
    public function duplicate(Request $request, $surveyId)
    {
        DB::beginTransaction();
        try {
            $originalSurvey = Survey::with([
                'surveyBlocks.questions.templateJawaban',
                'surveyBlocks.navigationRules'
            ])->findOrFail($surveyId);

            // Check permission
            if (!$this->canEditSurvey($originalSurvey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menduplikasi survey ini'
                ], 403);
            }

            // Create new survey
            $newSurvey = Survey::create([
                'nama' => $originalSurvey->nama . ' (Copy)',
                'deskripsi' => $originalSurvey->deskripsi,
                'status' => 'draft',
                'type_survei' => $originalSurvey->type_survei,
                'created_by' => auth()->id(),
            ]);

            // Duplicate blocks and questions
            foreach ($originalSurvey->surveyBlocks as $originalBlock) {
                $newBlock = SurveyBlock::create([
                    'survey_id' => $newSurvey->id,
                    'kode' => $originalBlock->kode,
                    'nama' => $originalBlock->nama,
                    'deskripsi' => $originalBlock->deskripsi,
                    'urutan' => $originalBlock->urutan,
                    'is_terminal' => $originalBlock->is_terminal,
                    'navigation_type' => $originalBlock->navigation_type,
                    'target_section_id' => $originalBlock->target_section_id,
                    'metadata' => $originalBlock->metadata,
                ]);

                // Duplicate questions
                foreach ($originalBlock->questions as $originalQuestion) {
                    $newQuestion = TemplatePertanyaan::create([
                        'id_survey' => $newSurvey->id,
                        'block_id' => $newBlock->id,
                        'pertanyaan' => $originalQuestion->pertanyaan,
                        'deskripsi_pertanyaan' => $originalQuestion->deskripsi_pertanyaan,
                        'tipe' => $originalQuestion->tipe,
                        'urutan' => $originalQuestion->urutan,
                        'visualisasi' => $originalQuestion->visualisasi,
                        'is_required' => $originalQuestion->is_required,
                    ]);

                    // Duplicate answer options
                    foreach ($originalQuestion->templateJawaban as $originalAnswer) {
                        TemplateJawaban::create([
                            'id_template_pertanyaan' => $newQuestion->id,
                            'pilihan_jawaban' => $originalAnswer->pilihan_jawaban,
                            'urutan' => $originalAnswer->urutan,
                        ]);
                    }
                }

                // Duplicate navigation rules
                foreach ($originalBlock->navigationRules as $originalRule) {
                    SectionNavigationRule::create([
                        'survey_id' => $newSurvey->id,
                        'source_section_id' => $newBlock->id,
                        'navigation_action' => $originalRule->navigation_action,
                        'target_section_id' => $originalRule->target_section_id,
                        'conditions' => $originalRule->conditions,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey berhasil diduplikasi',
                'data' => [
                    'new_survey_id' => $newSurvey->id,
                    'edit_url' => route('admin.survey.form_builder', $newSurvey->id)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error duplicating survey', [
                'original_survey_id' => $surveyId,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menduplikasi survey'
            ], 500);
        }
    }

    /**
     * Preview survey structure
     */
    public function preview($surveyId)
    {
        try {
            $survey = Survey::with([
                'surveyBlocks' => function($query) {
                    $query->orderBy('urutan');
                },
                'surveyBlocks.questions' => function($query) {
                    $query->orderBy('urutan');
                },
                'surveyBlocks.questions.templateJawaban' => function($query) {
                    $query->orderBy('urutan');
                }
            ])->findOrFail($surveyId);

            return response()->json([
                'success' => true,
                'data' => [
                    'survey' => $survey,
                    'stats' => [
                        'total_sections' => $survey->surveyBlocks->count(),
                        'total_questions' => $survey->surveyBlocks->sum(function($block) {
                            return $block->questions->count();
                        }),
                        'estimated_time' => $this->calculateEstimatedTime($survey)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading survey preview'
            ], 500);
        }
    }

    /**
     * Calculate estimated completion time
     */
    private function calculateEstimatedTime(Survey $survey)
    {
        $totalQuestions = 0;
        $complexQuestions = 0;

        foreach ($survey->surveyBlocks as $block) {
            foreach ($block->questions as $question) {
                $totalQuestions++;
                
                // Complex questions take more time
                if (in_array($question->tipe, ['textarea', 'file']) || 
                    ($question->templateJawaban && $question->templateJawaban->count() > 5)) {
                    $complexQuestions++;
                }
            }
        }

        // Base time: 30 seconds per question, +30 seconds for complex questions
        $estimatedSeconds = ($totalQuestions * 30) + ($complexQuestions * 30);
        
        return [
            'minutes' => ceil($estimatedSeconds / 60),
            'seconds' => $estimatedSeconds % 60,
            'formatted' => ceil($estimatedSeconds / 60) . ' menit'
        ];
    }
}
