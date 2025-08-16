<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Services\SurveyFlowService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManageSurveyBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:blocks 
                            {action : Action to perform (backfill|validate|demo)}
                            {--survey-id= : Survey ID for specific operations}
                            {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage survey blocks and branching rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'backfill':
                return $this->backfillBlocks();
            case 'validate':
                return $this->validateBranching();
            case 'demo':
                return $this->createDemo();
            default:
                $this->error("Unknown action: {$action}");
                $this->line('Available actions: backfill, validate, demo');
                return 1;
        }
    }

    /**
     * Backfill survey blocks from existing template_pertanyaan.blok values
     */
    private function backfillBlocks(): int
    {
        $surveyId = $this->option('survey-id');
        $dryRun = $this->option('dry-run');

        $query = Survey::query();
        if ($surveyId) {
            $query->where('id', $surveyId);
        }

        $surveys = $query->whereHas('questions', function ($q) {
            $q->whereNotNull('blok')->where('blok', '!=', '');
        })->get();

        if ($surveys->isEmpty()) {
            $this->info('No surveys found with blok values to backfill.');
            return 0;
        }

        $this->info("Found {$surveys->count()} surveys to process:");

        foreach ($surveys as $survey) {
            $this->line("- {$survey->nama} (ID: {$survey->id})");
        }

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        } else if (!$this->confirm('Do you want to proceed with backfilling blocks?')) {
            return 0;
        }

        $totalProcessed = 0;
        $totalBlocksCreated = 0;

        foreach ($surveys as $survey) {
            $this->info("\nProcessing: {$survey->nama}");

            // Get unique block values
            $blocks = DB::table('template_pertanyaan')
                ->where('id_survey', $survey->id)
                ->whereNotNull('blok')
                ->where('blok', '!=', '')
                ->select('blok')
                ->distinct()
                ->orderBy('blok')
                ->get();

            if ($blocks->isEmpty()) {
                $this->line('  No blocks to create');
                continue;
            }

            if (!$dryRun) {
                DB::beginTransaction();
            }

            try {
                $urutan = 1;
                $blockMapping = [];

                foreach ($blocks as $block) {
                    $existingBlock = SurveyBlock::where('survey_id', $survey->id)
                                               ->where('kode', $block->blok)
                                               ->first();

                    if ($existingBlock) {
                        $this->line("  Block {$block->blok} already exists, skipping");
                        $blockMapping[$block->blok] = $existingBlock->id;
                        continue;
                    }

                    if ($dryRun) {
                        $this->line("  Would create block: {$block->blok} (urutan: {$urutan})");
                        $blockId = 'dry-run-id';
                    } else {
                        $blockId = SurveyBlock::create([
                            'survey_id' => $survey->id,
                            'kode' => $block->blok,
                            'nama' => 'Blok ' . $block->blok,
                            'deskripsi' => 'Dibuat otomatis dari data sebelumnya',
                            'urutan' => $urutan,
                            'is_terminal' => false,
                        ])->id;

                        $this->line("  Created block: {$block->blok} (ID: {$blockId})");
                    }

                    $blockMapping[$block->blok] = $blockId;
                    $totalBlocksCreated++;
                    $urutan++;
                }

                // Update questions with block_id
                if (!$dryRun) {
                    foreach ($blockMapping as $kode => $blockId) {
                        if ($blockId !== 'dry-run-id') {
                            $updated = DB::table('template_pertanyaan')
                                ->where('id_survey', $survey->id)
                                ->where('blok', $kode)
                                ->update(['block_id' => $blockId]);

                            $this->line("  Updated {$updated} questions for block {$kode}");
                        }
                    }

                    DB::commit();
                } else {
                    foreach ($blockMapping as $kode => $blockId) {
                        $count = DB::table('template_pertanyaan')
                            ->where('id_survey', $survey->id)
                            ->where('blok', $kode)
                            ->count();

                        $this->line("  Would update {$count} questions for block {$kode}");
                    }
                }

                $totalProcessed++;

            } catch (\Exception $e) {
                if (!$dryRun) {
                    DB::rollback();
                }
                $this->error("  Failed to process survey {$survey->id}: " . $e->getMessage());
            }
        }

        $this->line('');
        if ($dryRun) {
            $this->info("DRY RUN COMPLETE:");
            $this->info("- Would process {$totalProcessed} surveys");
            $this->info("- Would create {$totalBlocksCreated} blocks");
        } else {
            $this->info("BACKFILL COMPLETE:");
            $this->info("- Processed {$totalProcessed} surveys");
            $this->info("- Created {$totalBlocksCreated} blocks");
        }

        return 0;
    }

    /**
     * Validate branching rules for potential issues
     */
    private function validateBranching(): int
    {
        $surveyId = $this->option('survey-id');
        $service = new SurveyFlowService();

        $query = Survey::query();
        if ($surveyId) {
            $query->where('id', $surveyId);
        }

        $surveys = $query->whereHas('branchRules')->get();

        if ($surveys->isEmpty()) {
            $this->info('No surveys found with branching rules to validate.');
            return 0;
        }

        $totalIssues = 0;

        foreach ($surveys as $survey) {
            $this->info("\nValidating: {$survey->nama} (ID: {$survey->id})");

            // Check for cycles
            $cycles = $service->detectCycles($survey->id);
            if (!empty($cycles)) {
                $this->error('  CYCLES DETECTED:');
                foreach ($cycles as $cycle) {
                    $this->error("    {$cycle}");
                    $totalIssues++;
                }
            } else {
                $this->line('  ✓ No cycles detected');
            }

            // Check for orphaned rules (pointing to non-existent blocks)
            $orphanedRules = DB::table('survey_branch_rules as sbr')
                ->leftJoin('survey_blocks as sb', 'sbr.target_block_id', '=', 'sb.id')
                ->where('sbr.survey_id', $survey->id)
                ->whereNull('sb.id')
                ->count();

            if ($orphanedRules > 0) {
                $this->error("  ORPHANED RULES: {$orphanedRules} rules point to non-existent blocks");
                $totalIssues += $orphanedRules;
            } else {
                $this->line('  ✓ No orphaned rules');
            }

            // Check for questions without blocks
            $questionsWithoutBlocks = TemplatePertanyaan::where('id_survey', $survey->id)
                ->whereNull('block_id')
                ->count();

            if ($questionsWithoutBlocks > 0) {
                $this->warn("  WARNING: {$questionsWithoutBlocks} questions are not assigned to any block");
            } else {
                $this->line('  ✓ All questions are assigned to blocks');
            }

            // Check for empty blocks
            $emptyBlocks = SurveyBlock::where('survey_id', $survey->id)
                ->doesntHave('questions')
                ->count();

            if ($emptyBlocks > 0) {
                $this->warn("  WARNING: {$emptyBlocks} blocks have no questions");
            } else {
                $this->line('  ✓ All blocks have questions');
            }
        }

        $this->line('');
        if ($totalIssues > 0) {
            $this->error("VALIDATION FAILED: {$totalIssues} issues found");
            return 1;
        } else {
            $this->info("VALIDATION PASSED: No critical issues found");
            return 0;
        }
    }

    /**
     * Create demo survey with blocks and branching
     */
    private function createDemo(): int
    {
        $this->info('Creating demo survey with blocks and branching rules...');

        if (!$this->confirm('This will create a new demo survey. Continue?')) {
            return 0;
        }

        try {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\SurveyBlockDemoSeeder'
            ]);

            $this->info('✓ Demo survey created successfully!');
            $this->line('');
            $this->info('You can now:');
            $this->line('1. Visit the admin panel to see the survey');
            $this->line('2. Manage blocks via the "Kelola Blok" button');
            $this->line('3. Set up additional branching rules for questions');
            $this->line('4. Test the survey flow as a user');

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create demo: ' . $e->getMessage());
            return 1;
        }
    }
}
