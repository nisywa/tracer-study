<?php
// filepath: /Users/miftahulhidayati/php-docker-dev/web-src/tracer-study/app/Exports/SurveyResultsExport.php

namespace App\Exports;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class SurveyResultsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $surveyId;
    protected $questions;
    protected $users;
    protected $answers;

    public function __construct($surveyId)
    {
        $this->surveyId = $surveyId;
        $this->prepareData();
    }

    /**
     * Prepare all data needed for the export
     */
    private function prepareData()
    {
        // Get survey information
        $survey = Survey::findOrFail($this->surveyId);

        // Get all questions for this survey ordered by blok and urutan
        $this->questions = TemplatePertanyaan::where('id_survey', $this->surveyId)
            ->orderBy('blok')
            ->orderBy('urutan')
            ->get();

        // Get all users who took this survey
        $this->users = SurveyUser::where('survey_id', $this->surveyId)
            ->where('status', true) // Only completed surveys
            ->with(['user']) // Eager load user info
            ->get();

        // Get all answers in one query to avoid n+1 problem
        $answers = SurveyUserJawaban::whereIn('survey_user_id', $this->users->pluck('id'))
            ->with(['surveyUser', 'template_pertanyaan'])
            ->get();

        // Reorganize answers into a more convenient format for quick lookup
        $this->answers = [];
        foreach ($answers as $answer) {
            $userId = $answer->survey_user_id;
            $questionId = $answer->template_pertanyaan_id;

            // Handle multiple choice answers (get text instead of ID)
            if ($answer->template_jawaban_id) {
                $option = TemplateJawaban::find($answer->template_jawaban_id);
                $answerText = $option ? $option->pilihan_jawaban : 'Unknown option';

                // For checkbox questions (multiple answers per question)
                if (isset($this->answers[$userId][$questionId])) {
                    if (is_array($this->answers[$userId][$questionId])) {
                        $this->answers[$userId][$questionId][] = $answerText;
                    } else {
                        // Convert existing single answer to array
                        $this->answers[$userId][$questionId] = [
                            $this->answers[$userId][$questionId],
                            $answerText
                        ];
                    }
                } else {
                    $this->answers[$userId][$questionId] = $answerText;
                }
            } else {
                // Text answers
                $this->answers[$userId][$questionId] = $answer->jawaban;
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = new Collection();

        foreach ($this->users as $user) {
            $row = [
                'User ID' => $user->user_id,
                'Nama' => $user->user->name ?? 'Unknown',
                'Email' => $user->user->email ?? 'Unknown',
                'Status' => $user->status ? 'Completed' : 'Pending',
                'Tanggal Mengisi' => $user->tanggal_mengisi ?? '-'
            ];

            // Add answers for each question
            foreach ($this->questions as $question) {
                $answerId = $question->id;

                // Get answer if exists, otherwise use default values
                if (isset($this->answers[$user->id][$answerId])) {
                    $answer = $this->answers[$user->id][$answerId];

                    // Handle array answers (checkboxes)
                    if (is_array($answer)) {
                        $row[$answerId] = implode(', ', $answer);
                    } else {
                        $row[$answerId] = $answer;
                    }
                } else {
                    $row[$answerId] = '-';
                }
            }

            $data->push($row);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'User ID',
            'Nama',
            'Email',
            'Status',
            'Tanggal Mengisi'
        ];

        // Add question text as headers
        foreach ($this->questions as $question) {
            // Add block name to question if available
            $prefix = $question->blok ? "[$question->blok] " : "";
            $headings[] = $prefix . $question->pertanyaan;
        }

        return $headings;
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // If $row is already an array, return it directly
        if (is_array($row)) {
            return $row;
        }

        // If $row is an object with toArray method, convert it
        if (is_object($row) && method_exists($row, 'toArray')) {
            return $row->toArray();
        }

        // Fallback - convert to array
        return (array) $row;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $survey = Survey::find($this->surveyId);
        return 'Survey: ' . ($survey->nama ?? 'Unknown');
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headers)
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            // Style for user information columns
            'A' => ['font' => ['bold' => true]],
            'B' => ['font' => ['bold' => true]],
            'C' => ['font' => ['bold' => true]],
            'D' => ['font' => ['bold' => true]],
            'E' => ['font' => ['bold' => true]]
        ];
    }
}
