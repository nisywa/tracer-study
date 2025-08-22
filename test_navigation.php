<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Survey Navigation Data ===\n";

// Get the latest survey with navigation rules
$survey = DB::table('survey')->orderBy('created_at', 'desc')->first();

if (!$survey) {
    echo "No surveys found\n";
    exit;
}

echo "Testing Survey: {$survey->nama} (ID: {$survey->id})\n\n";

// Get blocks for this survey
$blocks = DB::table('survey_blocks')
    ->where('survey_id', $survey->id)
    ->orderBy('urutan')
    ->get();

echo "Blocks found: " . count($blocks) . "\n";

foreach ($blocks as $block) {
    echo "\nBlock ID: {$block->id}, Name: {$block->nama}, Order: {$block->urutan}\n";
    
    // Get questions for this block
    $questions = DB::table('template_pertanyaan')
        ->where('block_id', $block->id)
        ->orderBy('urutan')
        ->get();
        
    foreach ($questions as $question) {
        echo "  Question ID: {$question->id}, Type: {$question->tipe}\n";
        echo "  Text: " . substr($question->pertanyaan, 0, 50) . "...\n";
        
        if (in_array($question->tipe, ['radio', 'select'])) {
            // Get options for this question
            $options = DB::table('template_jawaban')
                ->where('id_template_pertanyaan', $question->id)
                ->orderBy('urutan')
                ->get();
                
            foreach ($options as $option) {
                $navTarget = $option->navigation_target ?: 'NULL';
                echo "    Option ID: {$option->id}, Text: {$option->pilihan_jawaban}, Target: {$navTarget}\n";
                
                // Validate navigation target
                if ($option->navigation_target && strpos($option->navigation_target, 'block_') === 0) {
                    $targetOrder = (int) substr($option->navigation_target, 6);
                    $targetBlock = DB::table('survey_blocks')
                        ->where('survey_id', $survey->id)
                        ->where('urutan', $targetOrder)
                        ->first();
                        
                    if ($targetBlock) {
                        echo "      ✅ Valid target: Block '{$targetBlock->nama}' (ID: {$targetBlock->id})\n";
                    } else {
                        echo "      ❌ Invalid target: Block with order {$targetOrder} not found\n";
                    }
                }
            }
        }
    }
}

// Test the Laravel Models
echo "\n=== Testing with Laravel Models ===\n";

try {
    $surveyBlocks = \App\Models\SurveyBlock::with([
        'questions' => function ($query) {
            $query->with(['templateJawaban' => function ($q) {
                $q->select('id', 'id_template_pertanyaan', 'pilihan_jawaban', 'urutan', 'navigation_target')
                  ->orderBy('urutan');
            }])->orderBy('urutan');
        }
    ])
    ->where('survey_id', $survey->id)
    ->orderBy('urutan')
    ->get();

    echo "Laravel Models Test - Blocks loaded: " . $surveyBlocks->count() . "\n";
    
    foreach ($surveyBlocks as $block) {
        echo "\nBlock: {$block->nama} (ID: {$block->id}, Order: {$block->urutan})\n";
        
        foreach ($block->questions as $question) {
            if ($question->tipe === 'radio' && $question->templateJawaban->count() > 0) {
                echo "  Radio Question: " . substr($question->pertanyaan, 0, 50) . "...\n";
                foreach ($question->templateJawaban as $option) {
                    echo "    Option: {$option->pilihan_jawaban} -> {$option->navigation_target}\n";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error testing Laravel models: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
