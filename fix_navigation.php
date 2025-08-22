<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Updating Navigation Targets ===\n";

// Current block structure:
// Block ID: 48, Order: 2 - "Identitas Lulusan"  
// Block ID: 49, Order: 3 - "Kebekerjaan Lulusan" (current question is here)
// Block ID: 50, Order: 4 - "Pertanyaan khusus Bekerja/ Wiraswasta"
// Block ID: 51, Order: 5 - "Pertanyaan khusus wiraswasta"
// Block ID: 52, Order: 6 - "Pertanyaan khusus melanjutkan pendidikan"

// Fix navigation targets:
// "Bekerja" should go to block 4 (Pertanyaan khusus Bekerja/ Wiraswasta)
// "Wiraswasta" should go to block 5 (Pertanyaan khusus wiraswasta)  
// "Melanjutkan pendidikan" should go to block 6 (Pertanyaan khusus melanjutkan pendidikan)

DB::table('template_jawaban')->where('id', 30)->update(['navigation_target' => 'block_4']);
echo "Updated option 'Bekerja' to block_4\n";

DB::table('template_jawaban')->where('id', 31)->update(['navigation_target' => 'block_5']);
echo "Updated option 'Wiraswasta' to block_5\n";

DB::table('template_jawaban')->where('id', 32)->update(['navigation_target' => 'block_6']);
echo "Updated option 'Melanjutkan pendidikan' to block_6\n";

echo "\n=== Updated Navigation Targets ===\n";
$options = DB::table('template_jawaban')->whereIn('id', [30, 31, 32])->get();
foreach ($options as $option) {
    echo "ID: {$option->id}, Option: '{$option->pilihan_jawaban}', Target: {$option->navigation_target}\n";
}

echo "\n=== Verification ===\n";
$survey = DB::table('survey')->orderBy('created_at', 'desc')->first();
$blocks = DB::table('survey_blocks')->where('survey_id', $survey->id)->orderBy('urutan')->get();

foreach ($blocks as $block) {
    echo "Block Order {$block->urutan}: '{$block->nama}' (ID: {$block->id})\n";
}

echo "\n=== Navigation Logic Check ===\n";
foreach ($options as $option) {
    $targetOrder = (int) substr($option->navigation_target, 6);
    $targetBlock = $blocks->firstWhere('urutan', $targetOrder);
    
    if ($targetBlock) {
        echo "✅ '{$option->pilihan_jawaban}' → {$option->navigation_target} → '{$targetBlock->nama}'\n";
    } else {
        echo "❌ '{$option->pilihan_jawaban}' → {$option->navigation_target} → BLOCK NOT FOUND\n";
    }
}

echo "\n=== Update Complete ===\n";
