<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SurveyBranchRule;
use App\Services\SurveyFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyFillTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $survey;
    protected $flowService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->flowService = app(SurveyFlowService::class);
        
        // Create a test survey
        $this->survey = Survey::create([
            'nama' => 'Test Survey',
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(7),
            'type_survei' => 'alumni',
            'deskripsi' => 'Test survey description'
        ]);
    }

    public function test_survey_start_redirects_to_first_question()
    {
        // Create blocks and questions
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => false
        ]);

        $question1 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Test Question 1',
            'tipe' => 'radio',
            'urutan' => 1
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('surveys.start', $this->survey));

        $response->assertRedirect(route('surveys.show-question', [$this->survey, $question1]));
    }

    public function test_question_display_shows_correctly()
    {
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => false
        ]);

        $question1 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'What is your favorite color?',
            'tipe' => 'radio',
            'urutan' => 1
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $question1->id,
            'pilihan_jawaban' => 'Red',
            'urutan' => 1
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $question1->id,
            'pilihan_jawaban' => 'Blue',
            'urutan' => 2
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('surveys.show-question', [$this->survey, $question1]));

        $response->assertStatus(200)
                ->assertSee('What is your favorite color?')
                ->assertSee('Red')
                ->assertSee('Blue');
    }

    public function test_answer_submission_and_flow()
    {
        // Create blocks
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => false
        ]);

        $blockB = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'B',
            'nama' => 'Block B',
            'urutan' => 2,
            'is_terminal' => true
        ]);

        // Create questions
        $question1 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Choose option',
            'tipe' => 'radio',
            'urutan' => 1
        ]);

        $question2 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockB->id,
            'pertanyaan' => 'Final question',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        // Create answer options
        $option1 = TemplateJawaban::create([
            'id_template_pertanyaan' => $question1->id,
            'pilihan_jawaban' => 'Skip to end',
            'urutan' => 1
        ]);

        $option2 = TemplateJawaban::create([
            'id_template_pertanyaan' => $question1->id,
            'pilihan_jawaban' => 'Continue normal',
            'urutan' => 2
        ]);

        // Create branch rule: if option1 selected, go to block B
        SurveyBranchRule::create([
            'survey_id' => $this->survey->id,
            'source_question_id' => $question1->id,
            'answer_option_id' => $option1->id,
            'target_block_id' => $blockB->id,
            'priority' => 1
        ]);

        // Submit answer that triggers branch rule
        $response = $this->actingAs($this->user)
                         ->post(route('surveys.submit-answer', [$this->survey, $question1]), [
                             'answer_option_id' => $option1->id
                         ]);

        $response->assertRedirect(route('surveys.show-question', [$this->survey, $question2]));
    }

    public function test_survey_completion()
    {
        $blockA = SurveyBlock::create([
            'survey_id' => $this->survey->id,
            'kode' => 'A',
            'nama' => 'Block A',
            'urutan' => 1,
            'is_terminal' => true
        ]);

        $question1 = TemplatePertanyaan::create([
            'id_survey' => $this->survey->id,
            'block_id' => $blockA->id,
            'pertanyaan' => 'Final question',
            'tipe' => 'text',
            'urutan' => 1
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('surveys.submit-answer', [$this->survey, $question1]), [
                             'value' => 'Test answer'
                         ]);

        $response->assertRedirect(route('surveys.done', $this->survey));
    }

    public function test_inactive_survey_shows_appropriate_message()
    {
        $inactiveSurvey = Survey::create([
            'nama' => 'Inactive Survey',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(7),
            'type_survei' => 'alumni'
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('surveys.start', $inactiveSurvey));

        $response->assertStatus(200)
                ->assertSee('Survei Tidak Tersedia')
                ->assertSee('Belum Dimulai');
    }
}
