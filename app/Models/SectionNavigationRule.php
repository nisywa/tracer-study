<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionNavigationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'source_section_id',
        'navigation_action',
        'target_section_id',
        'conditions'
    ];

    protected $casts = [
        'conditions' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function sourceSection()
    {
        return $this->belongsTo(SurveyBlock::class, 'source_section_id');
    }

    public function targetSection()
    {
        return $this->belongsTo(SurveyBlock::class, 'target_section_id');
    }
}
