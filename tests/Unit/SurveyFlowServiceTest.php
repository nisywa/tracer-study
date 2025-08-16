<?php

namespace Tests\Unit;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\SurveyBranchRule;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Services\SurveyFlowService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyFlowServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SurveyFlowService $service;
    protected Survey $survey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SurveyFlowService();
        
        // Create a test survey
        $this->survey = Survey::create([
            'nama' => 'Test Survey',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(30),
            'type_survei' => 'alumni',
            'deskripsi' => 'Test survey for flow service'
        ]);
    }

    public function test_next_question_returns_null_when_no_next_question()
    {
        // Create a block
        $block = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => true
        ]);

        // Create a single question
        $question = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $block->id,
            'pertanyaan' => 'Test Question',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        $nextQuestion = $this->service->nextQuestion($this->survey, $question, []);
        
        $this->assertNull($nextQuestion);
    }

    public function test_next_question_returns_next_in_same_block()
    {
        // Create a block
        $block = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => false
        ]);

        // Create two questions
        $question1 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $block->id,
            'pertanyaan' => 'Question 1',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        $question2 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $block->id,
            'pertanyaan' => 'Question 2',
            'tipe' => 'text',
            'urutan' => 2
        ]);

        $nextQuestion = $this->service->nextQuestion($this->survey, $question1, []);
        
        $this->assertNotNull($nextQuestion);
        $this->assertEquals($question2->id, $nextQuestion->id);
    }

    public function test_branch_rule_with_option_redirects_to_target_block()
    {
        // Create blocks
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1
        ]);

        $blockB = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'B',
            'nama' => 'Block B',
            'urutan' => 2
        ]);

        // Create question with options
        $question = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Choose option',
            'tipe' => 'radio',
            'urutan' => 1
        ]);

        $option = TemplateJawaban::create([
            'id_template_pertanyaan' => $question->id,
            'pilihan_jawaban' => 'Option A',
            'urutan' => 1
        ]);

        // Create target question in block B
        $targetQuestion = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockB->id,
            'pertanyaan' => 'Target Question',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        // Create branch rule
        SurveyBranchRule::create([
            'survey_id' => $this->survey->id,
            'source_question_id' => $question->id,
            'answer_option_id' => $option->id,
            'target_block_id' => $blockB->id,
            'priority' => 1
        ]);

        $nextQuestion = $this->service->nextQuestion($this->survey, $question, [
            'answer_option_id' => $option->id
        ]);

        $this->assertNotNull($nextQuestion);
        $this->assertEquals($targetQuestion->id, $nextQuestion->id);
    }

    public function test_branch_rule_with_value_operator()
    {
        // Create blocks
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1
        ]);

        $blockB = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'B',
            'nama' => 'Block B',
            'urutan' => 2
        ]);

        // Create question
        $question = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Enter number',
            'tipe' => 'number',
            'urutan' => 1
        ]);

        // Create target question in block B
        $targetQuestion = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockB->id,
            'pertanyaan' => 'Target Question',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        // Create branch rule: if value > 50, go to block B
        SurveyBranchRule::create([
            'survey_id' => $this->survey->id,
            'source_question_id' => $question->id,
            'operator' => 'gt',
            'value_json' => 50,
            'target_block_id' => $blockB->id,
            'priority' => 1
        ]);

        $nextQuestion = $this->service->nextQuestion($this->survey, $question, [
            'value' => 75
        ]);

        $this->assertNotNull($nextQuestion);
        $this->assertEquals($targetQuestion->id, $nextQuestion->id);

        // Test with value that doesn't match the rule
        $nextQuestion = $this->service->nextQuestion($this->survey, $question, [
            'value' => 25
        ]);

        // Should return null since there's no next question in same block and no next block
        $this->assertNull($nextQuestion);
    }

    public function test_get_survey_blocks()
    {
        // Create some blocks
        SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'B',
            'nama' => 'Block B',
            'urutan' => 2
        ]);

        SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1
        ]);

        $blocks = $this->service->getSurveyBlocks($this->survey->id);

        $this->assertCount(2, $blocks);
        // Should be ordered by urutan
        $this->assertEquals('A', $blocks->first()->kode);
        $this->assertEquals('B', $blocks->last()->kode);
    }
}
