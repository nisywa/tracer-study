@extends('surveys.fill.layout')

@section('title', $survey->nama)

@section('content')
<div x-data="surveyQuestion()" class="space-y-6">
    <!-- Survey Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $survey->nama }}</h2>
                @if($survey->deskripsi)
                    <p class="text-gray-600 mt-1">{{ $survey->deskripsi }}</p>
                @endif
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Pertanyaan</div>
                <div class="text-lg font-medium text-gray-900">
                    {{ $progress['current'] }} / {{ $progress['total'] }}
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>Progress</span>
                <span>{{ $progress['percent'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $progress['percent'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Question Card -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('surveys.submit-answer', [$survey, $question]) }}" method="POST" class="p-6">
            @csrf
            
            <!-- Block Info -->
            @if($question->block)
                <div class="mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $question->block->kode }} - {{ $question->block->nama }}
                    </span>
                </div>
            @endif

            <!-- Question Title -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    {{ $question->pertanyaan }}
                </h3>
                @if($question->deskripsi_pertanyaan)
                    <p class="text-gray-600 text-sm">
                        {{ $question->deskripsi_pertanyaan }}
                    </p>
                @endif
            </div>

            <!-- Answer Field Based on Question Type -->
            <div class="mb-6">
                @switch($question->tipe)
                    @case('radio')
                        @include('surveys.fill.partials.radio-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('checkbox')
                        @include('surveys.fill.partials.checkbox-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('select')
                        @include('surveys.fill.partials.select-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('text')
                        @include('surveys.fill.partials.text-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('textarea')
                        @include('surveys.fill.partials.textarea-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('number')
                        @include('surveys.fill.partials.number-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('date')
                        @include('surveys.fill.partials.date-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @default
                        @include('surveys.fill.partials.text-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                @endswitch
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span>Lanjutkan</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Tips:</strong> Jawab dengan jujur dan lengkap. Anda dapat mengubah jawaban sebelum melanjutkan ke pertanyaan berikutnya.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function surveyQuestion() {
    return {
        init() {
            // Auto-focus on first input
            const firstInput = document.querySelector('input[type="radio"]:first-of-type, input[type="checkbox"]:first-of-type, input[type="text"], textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        }
    }
}
</script>
@endsection
