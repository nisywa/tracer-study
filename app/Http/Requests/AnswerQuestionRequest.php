<?php

namespace App\Http\Requests;

use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request validation for answering survey questions
 */
class AnswerQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $question = $this->route('question');
        
        if (!$question instanceof TemplatePertanyaan) {
            return [];
        }

        return $this->getRulesForQuestionType($question);
    }

    /**
     * Get validation rules based on question type
     */
    private function getRulesForQuestionType(TemplatePertanyaan $question): array
    {
        switch ($question->tipe) {
            case 'radio':
            case 'select':
                return [
                    'answer_option_id' => [
                        'required',
                        'integer',
                        Rule::exists('template_jawaban', 'id')->where(function ($query) use ($question) {
                            return $query->where('id_template_pertanyaan', $question->id);
                        })
                    ]
                ];

            case 'checkbox':
                return [
                    'answer_option_id' => 'array|nullable',
                    'answer_option_id.*' => [
                        'integer',
                        Rule::exists('template_jawaban', 'id')->where(function ($query) use ($question) {
                            return $query->where('id_template_pertanyaan', $question->id);
                        })
                    ]
                ];

            case 'text':
                return [
                    'value' => 'required|string|max:255'
                ];

            case 'textarea':
                return [
                    'value' => 'required|string|max:2000'
                ];

            case 'number':
                return [
                    'value' => 'required|numeric'
                ];

            case 'date':
                return [
                    'value' => 'required|date'
                ];

            default:
                return [
                    'value' => 'required|string|max:1000'
                ];
        }
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'answer_option_id.required' => 'Pilih salah satu jawaban.',
            'answer_option_id.exists' => 'Pilihan jawaban tidak valid.',
            'answer_option_id.*.exists' => 'Salah satu pilihan jawaban tidak valid.',
            'value.required' => 'Jawaban wajib diisi.',
            'value.string' => 'Jawaban harus berupa teks.',
            'value.max' => 'Jawaban terlalu panjang.',
            'value.numeric' => 'Jawaban harus berupa angka.',
            'value.date' => 'Format tanggal tidak valid.',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        $question = $this->route('question');
        
        if (!$question instanceof TemplatePertanyaan) {
            return;
        }

        // For checkbox type, ensure answer_option_id is always an array
        if ($question->tipe === 'checkbox') {
            if (!$this->has('answer_option_id')) {
                $this->merge(['answer_option_id' => []]);
            } elseif (!is_array($this->answer_option_id)) {
                $this->merge(['answer_option_id' => [$this->answer_option_id]]);
            }
        }

        // Trim whitespace from text inputs
        if (in_array($question->tipe, ['text', 'textarea']) && $this->has('value')) {
            $this->merge(['value' => trim($this->value)]);
        }
    }
}
