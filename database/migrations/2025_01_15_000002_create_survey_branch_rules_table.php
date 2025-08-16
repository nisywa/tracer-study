<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_branch_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('survey')->onDelete('cascade');
            $table->foreignId('source_question_id')->constrained('template_pertanyaan')->onDelete('cascade');
            $table->foreignId('answer_option_id')->nullable()->constrained('template_jawaban')->onDelete('cascade');
            $table->enum('operator', ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'in', 'contains'])->nullable();
            $table->json('value_json')->nullable();
            $table->foreignId('target_block_id')->constrained('survey_blocks')->onDelete('cascade');
            $table->smallInteger('priority')->default(1);
            $table->timestamps();
            
            // Index for performance
            $table->index(['source_question_id', 'priority']);
            $table->index(['survey_id', 'source_question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_branch_rules');
    }
};
