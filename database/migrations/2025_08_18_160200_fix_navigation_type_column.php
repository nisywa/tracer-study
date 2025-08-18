<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom navigation_type dari ENUM ke VARCHAR untuk mendukung nilai dinamis
        if (Schema::hasTable('survey_blocks')) {
            // Drop foreign key sementara jika ada
            if (Schema::hasColumn('survey_blocks', 'target_section_id')) {
                Schema::table('survey_blocks', function (Blueprint $table) {
                    $table->dropForeign(['target_section_id']);
                });
            }

            // Ubah navigation_type dari ENUM ke VARCHAR
            DB::statement('ALTER TABLE survey_blocks MODIFY COLUMN navigation_type VARCHAR(50) DEFAULT "next"');

            // Restore foreign key
            if (Schema::hasColumn('survey_blocks', 'target_section_id')) {
                Schema::table('survey_blocks', function (Blueprint $table) {
                    $table->foreign('target_section_id')->references('id')->on('survey_blocks')->onDelete('set null');
                });
            }
        }

        // Juga update untuk tabel section_navigation_rules jika ada
        if (Schema::hasTable('section_navigation_rules')) {
            DB::statement('ALTER TABLE section_navigation_rules MODIFY COLUMN navigation_action VARCHAR(50)');
        }
    }

    public function down(): void
    {
        // Rollback ke ENUM jika diperlukan
        if (Schema::hasTable('survey_blocks')) {
            // Drop foreign key sementara
            if (Schema::hasColumn('survey_blocks', 'target_section_id')) {
                Schema::table('survey_blocks', function (Blueprint $table) {
                    $table->dropForeign(['target_section_id']);
                });
            }

            // Kembalikan ke ENUM
            DB::statement('ALTER TABLE survey_blocks MODIFY COLUMN navigation_type ENUM("next", "jump", "end") DEFAULT "next"');

            // Restore foreign key
            if (Schema::hasColumn('survey_blocks', 'target_section_id')) {
                Schema::table('survey_blocks', function (Blueprint $table) {
                    $table->foreign('target_section_id')->references('id')->on('survey_blocks')->onDelete('set null');
                });
            }
        }

        if (Schema::hasTable('section_navigation_rules')) {
            DB::statement('ALTER TABLE section_navigation_rules MODIFY COLUMN navigation_action ENUM("next", "jump_to_section", "end_survey")');
        }
    }
};
