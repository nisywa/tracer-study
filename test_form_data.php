<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING FORM BUILDER DATA LOADING ===" . PHP_EOL;

// Simulate the FormBuilderController getData method
try {
    $surveyId = 1; // Test with existing survey
    
    // Get the survey with all related data (same as controller)
    $survey = App\Models\Survey::with([
        'surveyBlocks' => function($query) {
            $query->orderBy('urutan');
        },
        'surveyBlocks.questions' => function($query) {
            $query->orderBy('urutan');
        },
        'surveyBlocks.questions.templateJawaban' => function($query) {
            $query->orderBy('urutan');
        },
        'surveyBlocks.navigationRules'
    ])->findOrFail($surveyId);
    
    echo "Survey loaded: " . $survey->nama . PHP_EOL;
    echo "Blocks count: " . $survey->surveyBlocks->count() . PHP_EOL;
    
    // Transform data (same as controller)
    $formData = [
        'survey' => [
            'id' => $survey->id,
            'name' => $survey->nama,
            'description' => $survey->deskripsi,
            'status' => $survey->status,
            'created_at' => $survey->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $survey->updated_at->format('Y-m-d H:i:s')
        ],
        'sections' => [],
        'metadata' => [
            'total_sections' => $survey->surveyBlocks->count(),
            'total_questions' => $survey->surveyBlocks->sum(function($block) {
                return $block->questions->count();
            }),
            'has_navigation_rules' => $survey->surveyBlocks->flatMap->navigationRules->isNotEmpty()
        ]
    ];
    
    foreach ($survey->surveyBlocks as $blockIndex => $block) {
        $sectionData = [
            'id' => $block->id,
            'name' => $block->nama,
            'description' => $block->deskripsi,
            'questions' => [],
            'navigation' => [
                'type' => $block->navigation_type,
                'target_section_id' => $block->target_section_id,
                'is_terminal' => $block->is_terminal
            ],
            'metadata' => $block->metadata ? json_decode($block->metadata, true) : null
        ];
        
        foreach ($block->questions as $question) {
            $questionData = [
                'id' => $question->id,
                'question' => $question->pertanyaan,
                'description' => $question->deskripsi_pertanyaan,
                'type' => $question->tipe,
                'required' => $question->is_required,
                'visualization' => $question->visualisasi,
                'options' => $question->templateJawaban->pluck('pilihan_jawaban')->toArray(),
                'order' => $question->urutan
            ];
            
            $sectionData['questions'][] = $questionData;
        }
        
        $formData['sections'][] = $sectionData;
    }
    
    echo PHP_EOL . "=== TRANSFORMED DATA ===" . PHP_EOL;
    echo "Survey name: " . $formData['survey']['name'] . PHP_EOL;
    echo "Total sections: " . $formData['metadata']['total_sections'] . PHP_EOL;
    echo "Total questions: " . $formData['metadata']['total_questions'] . PHP_EOL;
    
    foreach ($formData['sections'] as $index => $section) {
        echo PHP_EOL . "Section " . ($index + 1) . ":" . PHP_EOL;
        echo "  ID: " . $section['id'] . PHP_EOL;
        echo "  Name: " . $section['name'] . PHP_EOL;
        echo "  Description: " . ($section['description'] ?: 'N/A') . PHP_EOL;
        echo "  Questions: " . count($section['questions']) . PHP_EOL;
        echo "  Navigation: " . ($section['navigation']['type'] ?: 'next') . PHP_EOL;
        
        foreach ($section['questions'] as $qIndex => $question) {
            echo "    Q" . ($qIndex + 1) . ": " . ($question['question'] ?: 'No question text') . PHP_EOL;
            echo "      Type: " . $question['type'] . PHP_EOL;
            echo "      Required: " . ($question['required'] ? 'Yes' : 'No') . PHP_EOL;
            if (!empty($question['options'])) {
                echo "      Options: " . implode(', ', $question['options']) . PHP_EOL;
            }
        }
    }
    
    echo PHP_EOL . "=== JSON OUTPUT ===" . PHP_EOL;
    echo json_encode([
        'success' => true,
        'data' => $formData,
        'message' => 'Data survey berhasil dimuat'
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
