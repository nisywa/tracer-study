<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\TemplatePertanyaan;
use Illuminate\Support\Facades\Cache;

/**
 * Service for calculating survey progress
 */
class SurveyProgressService
{
    /**
     * Get progress information for a question in a survey
     */
    public function getProgress(Survey $survey, TemplatePertanyaan $question): array
    {
        $orderedQuestions = $this->getOrderedQuestions($survey);
        
        $totalQuestions = $orderedQuestions->count();
        $currentIndex = $orderedQuestions->pluck('id')->search($question->id);
        
        // Convert to 1-based index
        $currentPosition = $currentIndex !== false ? $currentIndex + 1 : 1;
        
        $percent = $totalQuestions > 0 ? round(($currentPosition / $totalQuestions) * 100) : 0;
        
        return [
            'current' => $currentPosition,
            'total' => $totalQuestions,
            'percent' => $percent,
            'block_name' => $question->block ? $question->block->nama : 'Tidak ada blok'
        ];
    }

    /**
     * Get all questions ordered by block and question order (cached)
     */
    private function getOrderedQuestions(Survey $survey)
    {
        $cacheKey = "survey_ordered_questions_{$survey->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($survey) {
            return TemplatePertanyaan::where('id_survey', $survey->id)
                ->leftJoin('survey_blocks', 'survey_blocks.id', '=', 'template_pertanyaan.block_id')
                ->orderBy('survey_blocks.urutan')
                ->orderBy('template_pertanyaan.urutan')
                ->select('template_pertanyaan.*')
                ->get();
        });
    }

    /**
     * Clear cached questions for a survey
     */
    public function clearCache(int $surveyId): void
    {
        Cache::forget("survey_ordered_questions_{$surveyId}");
    }
}
