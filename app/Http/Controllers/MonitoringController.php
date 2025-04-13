<?php

namespace App\Http\Controllers;

use App\Exports\MonitoringExport;
use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $survey = Survey::paginate(10);
        foreach ($survey as $srvy) {
            $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-Y");
            $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-Y");
            if (Carbon::parse($srvy->tanggal_selesai) >= now()) {
                $srvy->status = "Aktif";
            } else {
                $srvy->status = "Selesai";
            }
        }

        return view('admin.views.monitoring.index', compact('survey'));
    }

    /**
     * Show visualization page
     */
    public function grafik($id)
    {
        $survey = Survey::findOrFail($id);
        $questions = TemplatePertanyaan::where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->get();

        return view('admin.views.monitoring.grafik', compact('survey', 'questions'));
    }

    /**
     * Get chart data for a specific question
     */
    public function getChartData($surveyId, $questionId)
    {
        try {
            // Verify survey exists
            $survey = Survey::findOrFail($surveyId);

            // Verify question belongs to this survey
            $question = TemplatePertanyaan::where('id_survey', $surveyId)
                ->where('id', $questionId)
                ->firstOrFail();

            if (!$question->visualisasi) {
                throw new \Exception('Visualization type not set for this question');
            }

            // Get answers for completed surveys only
            Log::info('Starting chart data generation', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'question_type' => $question->tipe,
                'visualisasi' => $question->visualisasi
            ]);

            // Get the survey responses
            $surveyUsers = SurveyUser::where('survey_id', $surveyId)
                ->where('status', true)
                ->get();

            Log::debug('Survey users found', [
                'count' => $surveyUsers->count(),
                'ids' => $surveyUsers->pluck('id')->toArray()
            ]);

            // Get answers for this question
            $answers = SurveyUserJawaban::whereIn('survey_user_id', $surveyUsers->pluck('id'))
                ->where('template_pertanyaan_id', $questionId)
                ->get();

            Log::debug('Answers found', [
                'count' => $answers->count(),
                'answers' => $answers->pluck('jawaban')->toArray()
            ]);

            // Count frequency of each answer
            $data = [];
            foreach ($answers as $answer) {
                if (!isset($data[$answer->jawaban])) {
                    $data[$answer->jawaban] = 1;
                } else {
                    $data[$answer->jawaban]++;
                }
            }

            // Sort data by values for better visualization
            if ($question->tipe === 'radio') {
                arsort($data);
            }

            $chartColors = [
                '#4B0082', // Indigo
                '#0096FF', // Blue
                '#00FF7F', // Spring Green
                '#FFD700', // Gold
                '#FF69B4', // Hot Pink
                '#8B4513', // Saddle Brown
                '#4682B4', // Steel Blue
                '#D2691E', // Chocolate
                '#9370DB', // Medium Purple
                '#3CB371', // Medium Sea Green
            ];

            // Format data for chart
            $chartData = [
                'labels' => array_keys($data),
                'datasets' => [
                    [
                        'label' => $question->pertanyaan,
                        'data' => array_values($data),
                        'backgroundColor' => array_slice($chartColors, 0, count($data))
                    ]
                ]
            ];

            Log::info('Chart data generated:', [
                'question' => $question->pertanyaan,
                'type' => $question->visualisasi,
                'data' => $data
            ]);

            return response()->json([
                'type' => $question->visualisasi ?? 'bar',
                'data' => $chartData
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating chart data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error generating chart data',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $template_questions = TemplatePertanyaan::with(['survey_user_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->orderBy('urutan')
            ->get();

        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }

        return view('admin.views.monitoring.details', [
            'survey' => $survey,
            'template_questions' => $template_questions
        ]);
    }

    public function export($surveyId, Excel $excel){
        return $excel->download(new MonitoringExport($surveyId), '.xlsx'); 
    }
}
