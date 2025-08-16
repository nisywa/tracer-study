<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Survey Branch Rule Model
 * 
 * @property int $id
 * @property int $survey_id
 * @property int $source_question_id
 * @property int|null $answer_option_id
 * @property string|null $operator
 * @property mixed $value_json
 * @property int $target_block_id
 * @property int $priority
 */
class SurveyBranchRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'source_question_id',
        'answer_option_id',
        'operator',
        'value_json',
        'target_block_id',
        'priority'
    ];

    protected $casts = [
        'value_json' => 'array',
        'priority' => 'integer'
    ];

    /**
     * Available operators for value-based rules
     */
    public const OPERATORS = [
        'eq' => 'Equal to',
        'neq' => 'Not equal to',
        'gt' => 'Greater than',
        'gte' => 'Greater than or equal',
        'lt' => 'Less than',
        'lte' => 'Less than or equal',
        'in' => 'In array',
        'contains' => 'Contains'
    ];

    /**
     * Get the survey that owns the rule
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    /**
     * Get the source question that triggers this rule
     */
    public function sourceQuestion(): BelongsTo
    {
        return $this->belongsTo(TemplatePertanyaan::class, 'source_question_id');
    }

    /**
     * Get the answer option that triggers this rule (for multiple choice)
     */
    public function answerOption(): BelongsTo
    {
        return $this->belongsTo(TemplateJawaban::class, 'answer_option_id');
    }

    /**
     * Get the target block for this rule
     */
    public function targetBlock(): BelongsTo
    {
        return $this->belongsTo(SurveyBlock::class, 'target_block_id');
    }

    /**
     * Scope to filter by question
     */
    public function scopeForQuestion(Builder $query, int $questionId): Builder
    {
        return $query->where('source_question_id', $questionId);
    }

    /**
     * Scope to order by priority
     */
    public function scopeOrderedByPriority(Builder $query): Builder
    {
        return $query->orderBy('priority');
    }

    /**
     * Check if this is an option-based rule
     */
    public function isOptionBased(): bool
    {
        return !is_null($this->answer_option_id);
    }

    /**
     * Check if this is a value-based rule
     */
    public function isValueBased(): bool
    {
        return is_null($this->answer_option_id) && !is_null($this->operator);
    }

    /**
     * Get a human-readable description of the rule
     */
    public function getDescription(): string
    {
        if ($this->isOptionBased()) {
            return "If answer is '{$this->answerOption?->pilihan_jawaban}' → go to block {$this->targetBlock?->kode}";
        }
        
        if ($this->isValueBased()) {
            $operatorText = self::OPERATORS[$this->operator] ?? $this->operator;
            $value = is_array($this->value_json) ? implode(', ', $this->value_json) : $this->value_json;
            return "If value {$operatorText} '{$value}' → go to block {$this->targetBlock?->kode}";
        }
        
        return "Unknown rule type";
    }
}
