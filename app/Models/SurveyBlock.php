<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Survey Block Model
 * 
 * @property int $id
 * @property int $survey_id
 * @property string $kode
 * @property string $nama
 * @property string|null $deskripsi
 * @property int $urutan
 * @property bool $is_terminal
 */
class SurveyBlock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'survey_id',
        'kode',
        'nama',
        'deskripsi',
        'urutan',
        'is_terminal'
    ];

    protected $casts = [
        'is_terminal' => 'boolean',
        'urutan' => 'integer'
    ];

    /**
     * Get the survey that owns the block
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    /**
     * Get questions assigned to this block
     */
    public function questions(): HasMany
    {
        return $this->hasMany(TemplatePertanyaan::class, 'block_id')
                   ->orderBy('urutan');
    }

    /**
     * Get branch rules that target this block
     */
    public function incomingRules(): HasMany
    {
        return $this->hasMany(SurveyBranchRule::class, 'target_block_id');
    }

    /**
     * Scope to filter by survey
     */
    public function scopeOfSurvey(Builder $query, int $surveyId): Builder
    {
        return $query->where('survey_id', $surveyId);
    }

    /**
     * Scope to order by urutan
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan');
    }

    /**
     * Get the next block based on urutan
     */
    public function getNextBlock(): ?self
    {
        return self::where('survey_id', $this->survey_id)
                   ->where('urutan', '>', $this->urutan)
                   ->ordered()
                   ->first();
    }

    /**
     * Check if this block has questions
     */
    public function hasQuestions(): bool
    {
        return $this->questions()->exists();
    }
}
