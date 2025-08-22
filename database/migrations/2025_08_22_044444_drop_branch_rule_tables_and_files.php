<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop database tables
        Schema::dropIfExists('survey_branch_rules');
        Schema::dropIfExists('section_navigation_rules');

        // Remove physical files
        $filesToDelete = [
            app_path('Http/Controllers/BranchRuleController.php'),
            app_path('Models/SurveyBranchRule.php'),
            app_path('Models/SectionNavigationRule.php'),
        ];

        foreach ($filesToDelete as $file) {
            if (File::exists($file)) {
                File::delete($file);
                echo "Deleted: " . $file . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate survey_branch_rules table
        Schema::create('survey_branch_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_question_id')->constrained('template_pertanyaans')->onDelete('cascade');
            $table->foreignId('answer_option_id')->nullable()->constrained('template_jawabans')->onDelete('cascade');
            $table->string('operator')->nullable();
            $table->json('value_json')->nullable();
            $table->foreignId('target_block_id')->constrained('survey_blocks')->onDelete('cascade');
            $table->integer('priority')->default(0);
            $table->timestamps();
            
            $table->index(['survey_id', 'source_question_id']);
            $table->index(['survey_id', 'priority']);
        });

        // Recreate section_navigation_rules table
        Schema::create('section_navigation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_section_id')->constrained('survey_blocks')->onDelete('cascade');
            $table->string('navigation_action', 50);
            $table->foreignId('target_section_id')->nullable()->constrained('survey_blocks')->onDelete('cascade');
            $table->json('conditions')->nullable();
            $table->timestamps();
        });

        // Note: Physical files cannot be automatically restored.
        // You would need to restore them from version control or backup.
        echo "Tables recreated. Note: Physical files need to be restored manually from version control.\n";
    }
};
