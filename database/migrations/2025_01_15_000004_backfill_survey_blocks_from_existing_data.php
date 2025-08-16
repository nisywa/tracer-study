<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if blok column exists first
        if (!Schema::hasColumn('template_pertanyaan', 'blok')) {
            return; // Skip migration if column doesn't exist
        }

        // This migration backfills survey_blocks from existing template_pertanyaan.blok values
        // and updates template_pertanyaan.block_id to reference the new survey_blocks
        
        try {
            DB::beginTransaction();
            
            // Get all surveys that have questions with blok values
            $surveys = DB::table('survey')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('template_pertanyaan')
                          ->whereRaw('template_pertanyaan.id_survey = survey.id')
                          ->whereNotNull('blok')
                          ->where('blok', '!=', '');
                })
                ->get();

            foreach ($surveys as $survey) {
                // Get unique block values for this survey
                $blocks = DB::table('template_pertanyaan')
                    ->where('id_survey', $survey->id)
                    ->whereNotNull('blok')
                    ->where('blok', '!=', '')
                    ->select('blok')
                    ->distinct()
                    ->orderBy('blok')
                    ->get();

                $urutan = 1;
                $blockMapping = [];

                // Create survey_blocks entries
                foreach ($blocks as $block) {
                    $blockId = DB::table('survey_blocks')->insertGetId([
                        'survey_id' => $survey->id,
                        'kode' => $block->blok,
                        'nama' => 'Blok ' . $block->blok,
                        'deskripsi' => 'Blok yang dibuat secara otomatis dari data sebelumnya',
                        'urutan' => $urutan++,
                        'is_terminal' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $blockMapping[$block->blok] = $blockId;
                }

                // Update template_pertanyaan with block_id
                foreach ($blockMapping as $kode => $blockId) {
                    DB::table('template_pertanyaan')
                        ->where('id_survey', $survey->id)
                        ->where('blok', $kode)
                        ->update(['block_id' => $blockId]);
                }
            }

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove block_id from template_pertanyaan where it was auto-migrated
        DB::table('template_pertanyaan')
            ->whereNotNull('block_id')
            ->whereIn('block_id', function ($query) {
                $query->select('id')
                      ->from('survey_blocks')
                      ->where('deskripsi', 'like', '%dibuat secara otomatis%');
            })
            ->update(['block_id' => null]);

        // Delete auto-created blocks
        DB::table('survey_blocks')
            ->where('deskripsi', 'like', '%dibuat secara otomatis%')
            ->delete();
    }
};
