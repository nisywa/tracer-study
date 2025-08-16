<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SurveyBlock;
use App\Models\SurveyBranchRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchRuleController extends Controller
{
    /**
     * Display branch rules for a question
     */
    public function index(Request $request, $surveyId, $questionId)
    {
        $survey = Survey::findOrFail($surveyId);
        $question = TemplatePertanyaan::with(['template_jawaban', 'block'])
                                     ->where('id_survey', $surveyId)
                                     ->findOrFail($questionId);
        
        $rules = SurveyBranchRule::with(['answerOption', 'targetBlock'])
                                ->where('survey_id', $surveyId)
                                ->where('source_question_id', $questionId)
                                ->orderBy('priority')
                                ->get();

        $availableBlocks = SurveyBlock::where('survey_id', $surveyId)
                                     ->orderBy('urutan')
                                     ->get();

        return view('admin.views.survey.questions.branch-rules.index', compact(
            'survey', 'question', 'rules', 'availableBlocks'
        ));
    }

    /**
     * Store a new branch rule
     */
    public function store(Request $request, $surveyId, $questionId)
    {
        $question = TemplatePertanyaan::where('id_survey', $surveyId)
                                     ->findOrFail($questionId);

        $validatedData = $request->validate([
            'rule_type' => 'required|in:option,value',
            'answer_option_id' => 'nullable|exists:template_jawaban,id',
            'operator' => 'nullable|in:eq,neq,gt,gte,lt,lte,in,contains',
            'value' => 'nullable',
            'target_block_id' => 'required|exists:survey_blocks,id',
            'priority' => 'required|integer|min:1'
        ]);

        // Validation logic based on rule type
        if ($validatedData['rule_type'] === 'option') {
            $request->validate([
                'answer_option_id' => 'required|exists:template_jawaban,id'
            ]);
            
            // Verify the answer option belongs to this question
            $answerOption = TemplateJawaban::where('id', $validatedData['answer_option_id'])
                                          ->where('id_template_pertanyaan', $questionId)
                                          ->first();
            if (!$answerOption) {
                return redirect()->back()
                                ->with('error', 'Pilihan jawaban tidak valid untuk pertanyaan ini.');
            }
        } else {
            $request->validate([
                'operator' => 'required|in:eq,neq,gt,gte,lt,lte,in,contains',
                'value' => 'required'
            ]);
        }

        // Verify target block belongs to same survey
        $targetBlock = SurveyBlock::where('id', $validatedData['target_block_id'])
                                 ->where('survey_id', $surveyId)
                                 ->first();
        if (!$targetBlock) {
            return redirect()->back()
                            ->with('error', 'Blok target tidak valid.');
        }

        try {
            DB::beginTransaction();

            // Check if rule already exists (prevent duplicates)
            $existingRule = SurveyBranchRule::where('survey_id', $surveyId)
                                           ->where('source_question_id', $questionId);
            
            if ($validatedData['rule_type'] === 'option') {
                $existingRule->where('answer_option_id', $validatedData['answer_option_id']);
            } else {
                $existingRule->whereNull('answer_option_id')
                            ->where('operator', $validatedData['operator'])
                            ->where('value_json', json_encode($validatedData['value']));
            }

            if ($existingRule->exists()) {
                return redirect()->back()
                                ->with('error', 'Aturan dengan kondisi yang sama sudah ada.');
            }

            // Adjust priorities if needed
            $this->adjustPriorities($surveyId, $questionId, $validatedData['priority']);

            // Create the rule
            $ruleData = [
                'survey_id' => $surveyId,
                'source_question_id' => $questionId,
                'target_block_id' => $validatedData['target_block_id'],
                'priority' => $validatedData['priority']
            ];

            if ($validatedData['rule_type'] === 'option') {
                $ruleData['answer_option_id'] = $validatedData['answer_option_id'];
            } else {
                $ruleData['operator'] = $validatedData['operator'];
                
                // Handle different value types
                if (in_array($validatedData['operator'], ['in'])) {
                    // For 'in' operator, expect comma-separated values
                    $ruleData['value_json'] = explode(',', $validatedData['value']);
                } else {
                    $ruleData['value_json'] = $validatedData['value'];
                }
            }

            SurveyBranchRule::create($ruleData);

            DB::commit();

            return redirect()->back()
                            ->with('success', 'Aturan percabangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menyimpan aturan.');
        }
    }

    /**
     * Update an existing branch rule
     */
    public function update(Request $request, $surveyId, $questionId, $id)
    {
        $rule = SurveyBranchRule::where('survey_id', $surveyId)
                               ->where('source_question_id', $questionId)
                               ->findOrFail($id);

        $validatedData = $request->validate([
            'target_block_id' => 'required|exists:survey_blocks,id',
            'priority' => 'required|integer|min:1'
        ]);

        // Verify target block belongs to same survey
        $targetBlock = SurveyBlock::where('id', $validatedData['target_block_id'])
                                 ->where('survey_id', $surveyId)
                                 ->first();
        if (!$targetBlock) {
            return redirect()->back()
                            ->with('error', 'Blok target tidak valid.');
        }

        try {
            DB::beginTransaction();

            // Adjust priorities if changed
            if ($rule->priority != $validatedData['priority']) {
                $this->adjustPriorities($surveyId, $questionId, $validatedData['priority'], $rule->id);
            }

            $rule->update($validatedData);

            DB::commit();

            return redirect()->back()
                            ->with('success', 'Aturan percabangan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat memperbarui aturan.');
        }
    }

    /**
     * Remove a branch rule
     */
    public function destroy($surveyId, $questionId, $id)
    {
        try {
            $rule = SurveyBranchRule::where('survey_id', $surveyId)
                                   ->where('source_question_id', $questionId)
                                   ->findOrFail($id);

            $priority = $rule->priority;
            $rule->delete();

            // Adjust remaining priorities
            SurveyBranchRule::where('survey_id', $surveyId)
                           ->where('source_question_id', $questionId)
                           ->where('priority', '>', $priority)
                           ->decrement('priority');

            return redirect()->back()
                            ->with('success', 'Aturan percabangan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menghapus aturan.');
        }
    }

    /**
     * Get rule details as JSON
     */
    public function show($surveyId, $questionId, $id)
    {
        $rule = SurveyBranchRule::with(['answerOption', 'targetBlock'])
                               ->where('survey_id', $surveyId)
                               ->where('source_question_id', $questionId)
                               ->findOrFail($id);

        return response()->json($rule);
    }

    /**
     * Helper method to adjust rule priorities
     */
    private function adjustPriorities($surveyId, $questionId, $newPriority, $excludeId = null)
    {
        $query = SurveyBranchRule::where('survey_id', $surveyId)
                                ->where('source_question_id', $questionId)
                                ->where('priority', '>=', $newPriority);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->increment('priority');
    }
}
