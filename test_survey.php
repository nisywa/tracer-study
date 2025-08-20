<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SURVEY DATABASE CHECK ===" . PHP_EOL;
echo "Total surveys: " . App\Models\Survey::count() . PHP_EOL;
echo "Surveys with blocks: " . App\Models\Survey::whereHas('surveyBlocks')->count() . PHP_EOL;

// Get a survey with blocks for testing
$surveyWithBlocks = App\Models\Survey::whereHas('surveyBlocks')->first();
if ($surveyWithBlocks) {
    echo PHP_EOL . "=== SAMPLE SURVEY ===" . PHP_EOL;
    echo "ID: " . $surveyWithBlocks->id . PHP_EOL;
    echo "Name: " . $surveyWithBlocks->nama . PHP_EOL;
    echo "Blocks count: " . $surveyWithBlocks->surveyBlocks->count() . PHP_EOL;
    
    foreach ($surveyWithBlocks->surveyBlocks as $index => $block) {
        echo "  Block " . ($index + 1) . ": " . $block->nama . " (Questions: " . $block->questions->count() . ")" . PHP_EOL;
    }
} else {
    echo "No surveys with blocks found." . PHP_EOL;
}
