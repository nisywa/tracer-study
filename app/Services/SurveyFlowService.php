<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\SurveyBranchRule;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Menghitung pertanyaan berikutnya berdasarkan jawaban,
 * menerapkan branching rules ala Google Forms.
 */
class SurveyFlowService
{
    /**
     * @param  Survey $survey
     * @param  TemplatePertanyaan $currentQuestion
     * @param  array $givenAnswer  // contoh: ['answer_option_id' => 123] atau ['value' => 'A']
     * @return TemplatePertanyaan|null  // null = selesai/terminal
     */
    public function nextQuestion(Survey $survey, TemplatePertanyaan $currentQuestion, array $givenAnswer): ?TemplatePertanyaan
    {
        try {
            // 1) Cek rule spesifik answer_option
            $rule = $this->matchOptionRule($survey, $currentQuestion, $givenAnswer);
            if ($rule) {
                return $this->firstQuestionOfBlock($rule->target_block_id);
            }

            // 2) Cek rule berbasis operator/value
            $rule = $this->matchValueRule($survey, $currentQuestion, $givenAnswer);
            if ($rule) {
                return $this->firstQuestionOfBlock($rule->target_block_id);
            }

            // 3) Default: pertanyaan berikutnya dalam blok yang sama
            $nextInBlock = $this->nextQuestionInSameBlock($currentQuestion);
            if ($nextInBlock) {
                return $nextInBlock;
            }

            // 4) Jika habis, lanjut ke blok berikutnya (berdasarkan urutan)
            $nextBlock = $this->nextBlock($survey, $currentQuestion->block_id);
            if (!$nextBlock || $nextBlock->is_terminal) {
                return null; // selesai
            }

            return $this->firstQuestionOfBlock($nextBlock->id);
        } catch (\Exception $e) {
            Log::error('Error in SurveyFlowService::nextQuestion: ' . $e->getMessage(), [
                'survey_id' => $survey->id,
                'current_question_id' => $currentQuestion->id,
                'given_answer' => $givenAnswer
            ]);
            
            // Fallback to default behavior
            return $this->nextQuestionInSameBlock($currentQuestion);
        }
    }

    /**
     * Match rule based on selected answer option
     */
    protected function matchOptionRule(Survey $survey, TemplatePertanyaan $q, array $answer): ?SurveyBranchRule
    {
        $answerOptionId = Arr::get($answer, 'answer_option_id');
        if (!$answerOptionId) return null;

        return SurveyBranchRule::query()
            ->where('survey_id', $survey->id)
            ->where('source_question_id', $q->id)
            ->where('answer_option_id', $answerOptionId)
            ->orderBy('priority')
            ->first();
    }

    /**
     * Match rule based on value and operator
     */
    protected function matchValueRule(Survey $survey, TemplatePertanyaan $q, array $answer): ?SurveyBranchRule
    {
        $value = Arr::get($answer, 'value');
        if ($value === null) return null;

        $rules = SurveyBranchRule::query()
            ->where('survey_id', $survey->id)
            ->where('source_question_id', $q->id)
            ->whereNull('answer_option_id')
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {
            if ($this->evaluateOperator($rule->operator, $value, $rule->value_json)) {
                return $rule;
            }
        }
        return null;
    }

    /**
     * Evaluate operator condition
     */
    protected function evaluateOperator(?string $op, $value, $valueJson): bool
    {
        $target = $valueJson ? (is_array($valueJson) ? $valueJson : json_decode($valueJson, true)) : null;
        
        switch ($op) {
            case 'eq':  
                return (string)$value === (string)$target;
            case 'neq': 
                return (string)$value !== (string)$target;
            case 'gt':  
                return floatval($value) > floatval($target);
            case 'gte': 
                return floatval($value) >= floatval($target);
            case 'lt':  
                return floatval($value) < floatval($target);
            case 'lte': 
                return floatval($value) <= floatval($target);
            case 'in':  
                return is_array($target) && in_array($value, $target);
            case 'contains':
                if (is_array($value)) {
                    return in_array($target, $value);
                }
                if (is_array($target)) {
                    return is_array($value) ? !empty(array_intersect($value, $target)) : in_array($value, $target);
                }
                return is_string($value) && is_string($target) && str_contains($value, $target);
            default:    
                return false;
        }
    }

    /**
     * Get first question of a specific block
     */
    protected function firstQuestionOfBlock(int $blockId): ?TemplatePertanyaan
    {
        return TemplatePertanyaan::query()
            ->where('block_id', $blockId)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get next question in the same block
     */
    protected function nextQuestionInSameBlock(TemplatePertanyaan $q): ?TemplatePertanyaan
    {
        if (!$q->block_id) {
            // Fallback: if no block_id, use old logic based on survey and urutan
            return TemplatePertanyaan::query()
                ->where('id_survey', $q->id_survey)
                ->where('urutan', '>', $q->urutan)
                ->orderBy('urutan')
                ->first();
        }

        return TemplatePertanyaan::query()
            ->where('id_survey', $q->id_survey)
            ->where('block_id', $q->block_id)
            ->where('urutan', '>', $q->urutan)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get next block based on urutan
     */
    protected function nextBlock(Survey $survey, ?int $currentBlockId): ?SurveyBlock
    {
        if (!$currentBlockId) return null;
        
        $current = SurveyBlock::query()->find($currentBlockId);
        if (!$current) return null;

        return SurveyBlock::query()
            ->where('survey_id', $survey->id)
            ->where('urutan', '>', $current->urutan)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get all blocks for a survey ordered by urutan
     */
    public function getSurveyBlocks(int $surveyId): \Illuminate\Database\Eloquent\Collection
    {
        return SurveyBlock::query()
            ->where('survey_id', $surveyId)
            ->orderBy('urutan')
            ->get();
    }

    /**
     * Check if there are any cycles in the branching rules (optional validation)
     */
    public function detectCycles(int $surveyId): array
    {
        $blocks = $this->getSurveyBlocks($surveyId);
        $visited = [];
        $recursionStack = [];
        $cycles = [];

        foreach ($blocks as $block) {
            if (!isset($visited[$block->id])) {
                $this->dfsDetectCycle($block, $visited, $recursionStack, $cycles, $surveyId);
            }
        }

        return $cycles;
    }

    /**
     * DFS helper for cycle detection
     */
    private function dfsDetectCycle(SurveyBlock $block, array &$visited, array &$recursionStack, array &$cycles, int $surveyId): void
    {
        $visited[$block->id] = true;
        $recursionStack[$block->id] = true;

        // Get all rules that target other blocks from questions in this block
        $rules = SurveyBranchRule::query()
            ->whereIn('source_question_id', function ($query) use ($block) {
                $query->select('id')
                      ->from('template_pertanyaan')
                      ->where('block_id', $block->id);
            })
            ->get();

        foreach ($rules as $rule) {
            $targetBlockId = $rule->target_block_id;
            
            if (!isset($visited[$targetBlockId])) {
                $targetBlock = SurveyBlock::find($targetBlockId);
                if ($targetBlock) {
                    $this->dfsDetectCycle($targetBlock, $visited, $recursionStack, $cycles, $surveyId);
                }
            } elseif (isset($recursionStack[$targetBlockId]) && $recursionStack[$targetBlockId]) {
                $cycles[] = "Cycle detected: Block {$block->kode} -> Block " . 
                          (SurveyBlock::find($targetBlockId)->kode ?? $targetBlockId);
            }
        }

        $recursionStack[$block->id] = false;
    }
}
