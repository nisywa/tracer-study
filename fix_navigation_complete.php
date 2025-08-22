<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Current Navigation Mapping ===\n";

$survey = DB::table('survey')->orderBy('created_at', 'desc')->first();
$blocks = DB::table('survey_blocks')->where('survey_id', $survey->id)->orderBy('urutan')->get();

echo "Available blocks:\n";
foreach ($blocks as $block) {
    echo "Block Order {$block->urutan}: '{$block->nama}' (ID: {$block->id})\n";
}

echo "\nCurrent navigation options:\n";
$options = DB::table('template_jawaban')->whereIn('id', [39, 40, 41, 42])->get();
foreach ($options as $option) {
    echo "ID: {$option->id}, Option: '{$option->pilihan_jawaban}', Target: {$option->navigation_target}\n";
}

echo "\n=== Updating Navigation Targets ===\n";

// Current question is in block 3 (Kebekerjaan Lulusan)
// Options should navigate to:
// "Bekerja" -> block 4 (Pertanyaan khusus Bekerja/ Wiraswasta)
// "Wiraswasta" -> block 5 (Pertanyaan khusus wiraswasta)  
// "Melanjutkan pendidikan" -> block 6 (Pertanyaan khusus melanjutkan pendidikan)
// "tidak bekerja" -> block 7 (pertanyaan khusus tidak bekerja)

DB::table('template_jawaban')->where('id', 39)->update(['navigation_target' => 'block_4']);
echo "Updated option 'Bekerja' to block_4\n";

DB::table('template_jawaban')->where('id', 40)->update(['navigation_target' => 'block_5']);
echo "Updated option 'Wiraswasta' to block_5\n";

DB::table('template_jawaban')->where('id', 41)->update(['navigation_target' => 'block_6']);
echo "Updated option 'Melanjutkan pendidikan' to block_6\n";

DB::table('template_jawaban')->where('id', 42)->update(['navigation_target' => 'block_7']);
echo "Updated option 'tidak bekerja' to block_7\n";

echo "\n=== Final Navigation Logic Check ===\n";
$updatedOptions = DB::table('template_jawaban')->whereIn('id', [39, 40, 41, 42])->get();
foreach ($updatedOptions as $option) {
    $targetOrder = (int) substr($option->navigation_target, 6);
    $targetBlock = $blocks->firstWhere('urutan', $targetOrder);
    
    if ($targetBlock) {
        echo "✅ '{$option->pilihan_jawaban}' → {$option->navigation_target} → '{$targetBlock->nama}'\n";
    } else {
        echo "❌ '{$option->pilihan_jawaban}' → {$option->navigation_target} → BLOCK NOT FOUND\n";
    }
}

echo "\n=== Update Complete ===\n";
