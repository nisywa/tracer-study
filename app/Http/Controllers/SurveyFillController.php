<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Services\SurveyFlowService;
use App\Services\SurveyProgressService;
use App\Http\Requests\AnswerQuestionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Controller for handling survey filling process by respondents
 */
class SurveyFillController extends Controller
{
    protected $surveyFlowService;
    protected $progressService;

    public function __construct(SurveyFlowService $surveyFlowService, SurveyProgressService $progressService)
    {
        $this->surveyFlowService = $surveyFlowService;
        $this->progressService = $progressService;
    }

    /**
     * Start or resume a survey
     */
    public function start(Survey $survey)
    {
        // Check if survey is active
        if (!$this->isSurveyActive($survey)) {
            return view('surveys.fill.inactive', compact('survey'));
        }

        try {
            DB::beginTransaction();

            // Find or create SurveyUser
            $surveyUser = $this->findOrCreateSurveyUser($survey);

            // If user has already completed the survey
            if ($surveyUser->status === '1') {
                return redirect()->route('surveys.done', $survey);
            }

            // Determine the next question to show
            $nextQuestion = $this->determineNextQuestion($survey, $surveyUser);

            if (!$nextQuestion) {
                // No more questions, mark as completed
                $surveyUser->update([
                    'status' => '1',
                    'tanggal_mengisi' => now()
                ]);
                DB::commit();
                return redirect()->route('surveys.done', $survey);
            }

            DB::commit();
            return redirect()->route('surveys.show-question', [$survey, $nextQuestion]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in SurveyFillController::start', [
                'survey_id' => $survey->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memulai survei.');
        }
    }

    /**
     * Show a specific question
     */
    public function showQuestion(Survey $survey, TemplatePertanyaan $question)
    {
        // Guard: ensure question belongs to survey
        if ($question->id_survey !== $survey->id) {
            abort(404, 'Pertanyaan tidak ditemukan dalam survei ini.');
        }

        // Check if survey is active
        if (!$this->isSurveyActive($survey)) {
            return view('surveys.fill.inactive', compact('survey'));
        }

        // Get or create survey user
        $surveyUser = $this->findOrCreateSurveyUser($survey);

        // Calculate progress
        $progress = $this->progressService->getProgress($survey, $question);

        // Get answer options if question has them
        $answerOptions = [];
        if (in_array($question->tipe, ['radio', 'checkbox', 'select'])) {
            $answerOptions = TemplateJawaban::where('id_template_pertanyaan', $question->id)
                                           ->orderBy('urutan')
                                           ->get();
        }

        // Get existing answer if any
        $existingAnswer = SurveyUserJawaban::where('survey_user_id', $surveyUser->id)
                                          ->where('template_pertanyaan_id', $question->id)
                                          ->first();

        return view('surveys.fill.question', compact(
            'survey', 
            'question', 
            'surveyUser', 
            'progress', 
            'answerOptions', 
            'existingAnswer'
        ));
    }

    /**
     * Submit answer for a question
     */
    public function submitAnswer(AnswerQuestionRequest $request, Survey $survey, TemplatePertanyaan $question)
    {
        // Guard: ensure question belongs to survey
        if ($question->id_survey !== $survey->id) {
            abort(404, 'Pertanyaan tidak ditemukan dalam survei ini.');
        }

        try {
            DB::beginTransaction();

            // Get survey user
            $surveyUser = $this->findOrCreateSurveyUser($survey);

            // Save answer
            $this->saveAnswer($request, $surveyUser, $question);

            // Prepare answer data for flow service
            $givenAnswer = $this->prepareAnswerForFlowService($request, $question);

            // Get next question using flow service
            $nextQuestion = $this->surveyFlowService->nextQuestion($survey, $question, $givenAnswer);

            if (!$nextQuestion) {
                // Survey completed
                $surveyUser->update([
                    'status' => '1',
                    'tanggal_mengisi' => now(),
                    'current_question_id' => null
                ]);
                
                DB::commit();
                return redirect()->route('surveys.done', $survey)->with('success', 'Terima kasih telah mengisi survei!');
            }

            // Update current question
            $surveyUser->update(['current_question_id' => $nextQuestion->id]);

            DB::commit();
            return redirect()->route('surveys.show-question', [$survey, $nextQuestion]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in SurveyFillController::submitAnswer', [
                'survey_id' => $survey->id,
                'question_id' => $question->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }

    /**
     * Show completion page
     */
    public function done(Survey $survey)
    {
        $surveyUser = null;
        
        if (Auth::check()) {
            $surveyUser = SurveyUser::where('survey_id', $survey->id)
                                   ->where('user_id', Auth::id())
                                   ->first();
        } else {
            // For guest users, check session
            $guestToken = session('survey_guest_token_' . $survey->id);
            if ($guestToken) {
                $surveyUser = SurveyUser::where('survey_id', $survey->id)
                                       ->where('guest_token', $guestToken)
                                       ->first();
            }
        }

        return view('surveys.fill.done', compact('survey', 'surveyUser'));
    }

    /**
     * Check if survey is active
     */
    private function isSurveyActive(Survey $survey): bool
    {
        $now = now();
        $startDate = Carbon::parse($survey->tanggal_mulai);
        $endDate = Carbon::parse($survey->tanggal_selesai);

        return $now >= $startDate && $now <= $endDate;
    }

    /**
     * Find or create SurveyUser for the current session
     */
    private function findOrCreateSurveyUser(Survey $survey): SurveyUser
    {
        if (Auth::check()) {
            // Authenticated user
            return SurveyUser::firstOrCreate([
                'survey_id' => $survey->id,
                'user_id' => Auth::id()
            ], [
                'status' => '0',
                'guest_token' => null
            ]);
        } else {
            // Guest user - use session token
            $guestToken = session('survey_guest_token_' . $survey->id);
            
            if (!$guestToken) {
                $guestToken = uniqid('guest_' . $survey->id . '_', true);
                session(['survey_guest_token_' . $survey->id => $guestToken]);
            }

            return SurveyUser::firstOrCreate([
                'survey_id' => $survey->id,
                'guest_token' => $guestToken
            ], [
                'status' => '0',
                'user_id' => null
            ]);
        }
    }

    /**
     * Determine the next question to show (for start/resume logic)
     */
    private function determineNextQuestion(Survey $survey, SurveyUser $surveyUser): ?TemplatePertanyaan
    {
        // If user has a current question saved, resume from there
        if ($surveyUser->current_question_id) {
            $currentQuestion = TemplatePertanyaan::find($surveyUser->current_question_id);
            if ($currentQuestion && $currentQuestion->id_survey === $survey->id) {
                return $currentQuestion;
            }
        }

        // Get all questions ordered by block and question order
        $orderedQuestions = TemplatePertanyaan::where('id_survey', $survey->id)
            ->leftJoin('survey_blocks', 'survey_blocks.id', '=', 'template_pertanyaan.block_id')
            ->orderBy('survey_blocks.urutan')
            ->orderBy('template_pertanyaan.urutan')
            ->select('template_pertanyaan.*')
            ->get();

        if ($orderedQuestions->isEmpty()) {
            return null;
        }

        // Get answered question IDs
        $answeredQuestionIds = SurveyUserJawaban::where('survey_user_id', $surveyUser->id)
            ->pluck('template_pertanyaan_id')
            ->toArray();

        // Find first unanswered question
        $firstUnanswered = $orderedQuestions->first(function ($question) use ($answeredQuestionIds) {
            return !in_array($question->id, $answeredQuestionIds);
        });

        return $firstUnanswered ?: $orderedQuestions->first();
    }

    /**
     * Save answer to database
     */
    private function saveAnswer(AnswerQuestionRequest $request, SurveyUser $surveyUser, TemplatePertanyaan $question): void
    {
        $jawaban = '';

        switch ($question->tipe) {
            case 'radio':
            case 'select':
                $jawaban = $request->answer_option_id;
                break;
            case 'checkbox':
                $jawaban = json_encode($request->answer_option_id ?? []);
                break;
            case 'text':
            case 'textarea':
            case 'number':
            case 'date':
                $jawaban = $request->value ?? '';
                break;
        }

        SurveyUserJawaban::updateOrCreate([
            'survey_user_id' => $surveyUser->id,
            'template_pertanyaan_id' => $question->id
        ], [
            'jawaban' => $jawaban
        ]);
    }

    /**
     * Prepare answer data for SurveyFlowService
     */
    private function prepareAnswerForFlowService(AnswerQuestionRequest $request, TemplatePertanyaan $question): array
    {
        switch ($question->tipe) {
            case 'radio':
            case 'select':
                return ['answer_option_id' => $request->answer_option_id];
            
            case 'checkbox':
                return ['value' => $request->answer_option_id ?? []];
            
            case 'text':
            case 'textarea':
            case 'number':
            case 'date':
                return ['value' => $request->value ?? ''];
            
            default:
                return ['value' => $request->value ?? ''];
        }
    }
}
